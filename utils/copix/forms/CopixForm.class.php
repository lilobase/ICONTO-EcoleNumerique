<?php
/**
 * @package		copix
 * @subpackage	forms
 * @author		Salleyron Julien, Croës Gérald
 * @copyright	CopixTeam
 * @link			http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 * @experimental
 */


/**
 * Classe principale pour CopixForm
 * @package		copix
 * @subpackage	forms
 */
class CopixForm
{
    //Id du formulaire
    private $_id             = null;
    //Instance du datasource
    private $_datasource     = null;
    //Type de datasource
    private $_datasourceName = null;
    //Liste des paramètres passé au formulaire
    private $_params         = array ();
    //onDelete
    private $_onDelete       = 'generictools|copixforms|delete';
    //mode courant
    private $_mode           = 'edit';
    //mode par defaut
    private $_defaultMode    = 'edit';
    //Url de redirection apres suppression
    private $_deleteUrl      = null;
    //Valid url
    private $_onValid       = null;
    //Tableau contenant les pk pour ce datasource
    private $_arPkValue      = array ();
    //Mode creation ?
    private $_create         = null;
    //Les champs de type CopixField
    private $_fields         = array ();
    //Url d'action (redirection au submit du formulaire
    private $_action         = null;
    //En cours de delete ?
    private $_toDelete       = false;
    //Tableau contenant les erreurs
    private $_errors         = array();
    //Boolean pour savoir si les boutons on été affiché
    private $_deleteButton   = false;
    private $_editButton     = false;
    private $_submitButton   = false;
    private $_cancelButton   = false;

    //Record courant
    private $_record         = null;

    //Boolean pour savoir si on doit faire l'action d'enregistrement sur le datasource
    private $_mustDoRecord   = false;

    /**
     * _sleep
     * on détruit le datasource pour pas le mettre en session
     * et on remets a null les valeur des boutons
     */
    public function __sleep ()
    {
        $this->_editButton   = false;
        $this->_deleteButton = false;
        $this->_submitButton = false;
        $this->_cancelButton = false;
        $this->_toDelete     = false;
        return array_keys(get_object_vars($this));
    }

    public function getCurrentPk ()
    {
        return $this->_arPkValue;
    }

    /**
     * Constructeur
     *
     * @param string $pId l'identifiant de ce formulaire
     */
    public function __construct ($pId)
    {
        $this->_id = $pId;
    }

    public function getParam ($pParam, $pDefault = null)
    {
        return (isset ($this->_params[$pParam]) ? $this->_params[$pParam] : $pDefault);
    }


    /**
     * Attribut des errors
     *
     * @param array $pErrors Tableau contenant les erreurs a attribuer au formulaire
     */
    public function setErrors ($pErrors)
    {
        $this->_errors = $pErrors;
        foreach ($this->_errors as $key=>$error) {
            $field = $this->getField($key);
            if ($field!=null) {
                $field->addError ($error);
                unset ($this->_errors[$key]);
            }
        }
    }

    private function _fillWithDatasourceField ()
    {
        if ($this->getParam('fieldAuto', true)) {
            foreach ($this->_datasource->getFields () as $datasource_field) {
                $this->_fields[$datasource_field->fieldName] = $this->getField ($datasource_field->fieldName,array ('type'=>$datasource_field->type, 'label'=>$datasource_field->name), false);
            }
        }
    }

    private function _fillWithMagicRequest ()
    {
        $this->_arPkValue   = array();
        //Si on est pas en mode error, on recupère les valeurs des champs avec MagicRequest ou dans le template ou mode create
        if (_request ('error_'.$this->_id) == null) {
            $this->_create = false;

            // On récupère les clé primaire dans le datasource
            $arPk    = $this->_datasource->getPk ();
            $arValue = array ();
            foreach ($arPk as $pk) {
                //les pk préciser dans les paramètres surpasse ceux du request
                if ($this->getParam ($pk) !== null) {
                    $arValue[$pk] = $this->getParam ($pk);
                } elseif (_request ($pk) !== null) {
                    $arValue[$pk] = _request ($pk);
                } else {
                    //Si ni dans les params ni dans le request, on passe en mode création
                    $this->_create = true;
                    break;
                }
            }

            //Si pas le mode create on rempli les champs grace au datasource
            if (!$this->_create) {
                $result = false;
                $result = call_user_func_array (array($this->_datasource, 'get'), $arValue);
                // Si tout va bien on rempli, si on obtiens rien ou qu'il n'y a pas de datasource on passe en mode create
                if ($result) {
                    $this->_record = $result;
                } else {
                    $this->_create = true;
                }
            }
            // Si mode create on force le mode edit et on supprime le record
            if ($this->_create) {
                $this->_mode = 'edit';
                $this->_record = new StdClass;
            }
            $this->_arPkValue = $arValue;
        }
    }

    private function _loadDatasource ()
    {
        if ($this->_datasource !== null) {
            return true;
        }

        if ($this->getParam('datasource') === null && $this->getParam('dao') !== null) {
            $this->_params['datasource'] = 'dao';
        }

        if ($this->getParam('datasource') === null) {
            return false;
        }

        $this->_datasourceName = $this->getParam ('datasource');
        $this->_datasource = CopixDatasourceFactory::get ($this->_datasourceName,$this->_params);
        $this->_mustDoRecord = true;
        $this->_fillWithDatasourceField();

        return true;
    }


    public function setParams ($pParams)
    {
        $this->_params = $pParams;

        if ($this->_loadDatasource ()) {
            $this->_fillWithMagicRequest();
            if (CopixRequest::get('error_'.$this->_id) == null) {
                foreach ($this->_fields as $field) {
                     $field->fillFromRecord ($this->_record);
                }
            }
        }

        //Gestion du mode (on prends en paramètre sinon on prend dans le request)
        $this->setMode ($this->getParam ('mode', _request ('mode_'.$this->_id, $this->_defaultMode)));

        //Url de redirection apres un delete si rien de préciser on prend l'url de l'action
        $this->_deleteUrl   = isset ($pParams['deleteUrl']) ? $pParams['deleteUrl'] : $this->_action;

        //Si on doit delete, on passe en mode view
        if (_request ('delete_'.$this->_id) || (_request ('delete'))) {
            $this->_toDelete = true;
            $this->_mode='view';
        }

        $this->_onDelete = $this->getParam ('onDelete', 'generictools|copixforms|delete');
        $this->_action = $this->getParam ('action', 'generictools|copixforms|CheckRecord');
        $this->_onValid = $this->getParam ('onValid');

    }

    public function getHTMLHeader ()
    {
        //Rajoute l'encodage pour si il y a un champ qui upload
        $encoding = '';
        if ($this->getParam ('upload', false)) {
            $encoding = 'enctype="multipart/form-data" ';
        }
        //On génère les paramètres d'action du formulaire
        $arParamsUrl = array('url'=>CopixUrl::get("#"), 'form_id'=>$this->_id, 'onValid'=>$this->_onValid);
        //Gestion de l'entete du formulaire
        $toReturn = '<form method="POST" '.$encoding.'id="'.$this->_id.'" action="'._url($this->_action,$arParamsUrl).'" ><span id="error_formpost_'.$this->_id.'" class="copixforms_error">'.implode ('<br />',$this->_errors).'</span>';
        unset ($this->_errors);
        return $toReturn;
    }

    /**
     * Gestion de la fin du formulaire
     *
     * @return HTML
     */
    public function end ()
    {
        //Affichage des différents bouton a la fin du formulaire
        //$toReturn = $this->getButton ('edit').$this->getButton ('delete').$this->getButton ('submit').
        $toReturn = '</form>';

        //Gestion des messages d'erreurs non affichées dans le formulaire
        if (count($this->_errors)>0) {
            $htmlError = '';
            foreach ($this->_errors as $key=>$error) {
                if (is_array($error)) {
                    foreach ($error as $err) {
                        $htmlError .= $key.' : '.$err.'<br />';
                    }
                } else {
                    $htmlError .= $key.' : '.$error.'<br />';
                }
                unset($this->_errors[$key]);
            }
            _tag('mootools');
            CopixHTMLHeader::addJSCode("
            window.addEvent('domready', function () {
                $('error_formpost_".$this->_id."').setHTML('".stripslashes($htmlError)."');
            });

            ");
        }
        return $toReturn;
    }

    /**
     * Permet de récupérer un des boutons du formulaire
     *
     * @param string $pType type de bouton
     * @param string $pContent le HTML pour afficher le bouton, si il n'est pas renseigné, on obtiens le code par defaut du bouton
     * @return HTML du bouton
     */
    public function getButton ($pType, $pContent = null)
    {
        $toReturn = '';
        switch ($pType) {
            case 'delete':
                if ($this->_deleteButton || $this->_mode != 'view') {
                    return '';
                }
                $this->_deleteButton = true;
                if ($this->_toDelete) {
                    //$toReturn .= '<div id"deleter">Supprimer ? <a href="'._url ('generictools|copixforms|delete', array_merge ($this->_arPkValue, array ('url'=>$this->_deleteUrl, 'form_id'=>$this->_id))).'">yes</a> <a href="'._url ('#', array_merge ($this->_arPkValue, array ('delete_'.$this->_id=>null))).'">no</a></div>'
                    $urlYes = ($this->_onValid !== null) ? $this->_onValid : $this->_deleteUrl;
                    $urlNo = _url (($this->_deleteUrl != null) ? $this->_deleteUrl : '#', array_merge ($this->_arPkValue, array ('delete_'.$this->_id=>null)));
                    _tag ('confirm',array('yes'=>_url ($this->_onDelete, array_merge ($this->_arPkValue, array ('url'=>$urlYes, 'form_id'=>$this->_id))),'no'=>$urlNo),_i18n('copix:copixform.message.delete'));
                } else {
                    $toReturn .= '<a href="'._url ('#', array_merge ($this->_arPkValue, array('delete_'.$this->_id=>true,'form_id'=>$this->_id))).'" ><input type="button" value='._i18n('copix:copixform.button.delete').' /></a>';
                }
                break;
            case 'edit':
                //if ($this->_editButton || $this->_mode != 'view' || $this->_toDelete) {
                return '';
                //}
                $this->_editButton = true;
                $arTemp = array();
                if ($this->_datasourceName != null) {
                    $arTemp = $this->_datasource->getPk ();
                }
                $arPk=array();
                foreach ($arTemp as $temp) {
                    $arPk[$temp] = $this->_record->$temp;
                }
                $arPk['mode_'.$this->_id] = 'edit';
                $arPk['mode_id'] = $this->_id;
                $toReturn .= '<a href="'._url ('#',$arPk).'" ><input type="button" " value="'._i18n('copix:copixform.button.edit').'" /></a>';
                break;
            case 'submit':
                if ($this->_submitButton || $this->_mode == 'view') {
                    return '';
                }
                $this->_submitButton = true;
                if ($pContent == null) {
                    $toReturn .= '<input type="submit" id="submit" value="'._i18n('copix:copixform.button.submit').'" />';
                } else {
                    $toReturn .= '<span onClick="$(\'formpost_'.$this->_id.'\').submit()">'.$pContent.'</span>';
                }
                break;
            case 'cancel':
                if ($this->_cancelButton || $this->_mode == 'view' || ($this->_create && $this->_validUrl === null)) {
                    return '';
                }
                $this->_cancelButton = true;
                if ($this->_validUrl !== null) {
                    $toReturn .= '<a href="'._url ($this->_validUrl).'" ><input type="button" value="'._i18n('copix:copixform.button.cancel').'" /></a>';
                } else {
                    $toReturn .= '<a href="'._url ('#',array('error_'.$this->_id=>null,'mode_'.$this->_id=>'view')).'" ><input type="button" value="'._i18n('copix:copixform.button.cancel').'" /></a>';
                }
                break;
            default:
                throw new CopixFormException  (_i18n ('copix:copixform.message.unknownType',$pType));
        }
        return $toReturn;
    }

     /**
     * Rempli les champs du formulaire depuis CopixRequest
     * @return array retourne un tableau d'erreur, si le tableau est vide alors il n'y a pas d'erreur
     */
    public function createRecord ()
    {
        $validatorError = false;
        $this->_record = new StdClass ();
        foreach ($this->_fields as $key=>$field) {
            $field->fillFromRequest ();
            $field->fillRecord ($this->_record);
            /*$errorReport = $this->_fields[$key]->valid();
            if (!$validatorError) {
                $validatorError = $errorReport->haveErrors ();
            }*/
        }
        $check = null;
        if ($this->_datasourceName != null) {
            if (method_exists($this->_datasource,'check')) {
                //var_dump($this->_record);
                $check = $this->_datasource->check ($this->_record);
                if (is_array($check)) {
                    foreach ($check as $fieldKey=>$error) {
                        $validatorError = true;
                        $this->getField ($fieldKey)->getErrors()->addError ($error);
                    }
                }
            }
        }
        return ($validatorError);
    }

    /**
     * Lance la suppression d'un enregistrement
     * @param array $pParams Tableau de pk
     */
    public function delete ($pParams)
    {
        if ($this->_datasourceName == null) {
            throw new CopixFormException (_i18n('copix:copixform.message.noDatasource'));
        }
        $arPk = $this->_datasource->getPk ();
        $arValue = array ();

        foreach ($arPk as $pk) {
            if (!isset($pParams[$pk])) {
                throw new CopixFormException(_i18n('copix:copixform.message.noDelete'));
            }
            $arValue[$pk] = $pParams[$pk];
        }
        $result = call_user_func_array (array ($this->_datasource,'delete'),$arValue);
    }

    public function getHTML ($pTemplate = 'copix:templates/copixform.tpl')
    {
        $tpl = new CopixTPL ();
        $tpl->assign ('fields', $this->getFields ());
        return $tpl->fetch ($pTemplate);
    }

    public function getFields ()
    {
        return $this->_fields;
    }

    public function addField ($pField)
    {
        if (!is_array ($pField)) {
            $pField = array ($pField);
        }
        foreach ($pField as $field) {
            $pName = $field->getName ();
            $id = is_string ($pName) ? $pName : serialize($pName);
            if (isset ($this->_fields[$id])) {
                $field->merge ($this->_fields[$id]);
            }
            $this->_fields[$pName] = $field;
            $this->_fields[$pName]->setMode ($this->_mode);
            $this->_fields[$pName]->fillFromRecord ($this->_record);
        }

    }


    public function getField ($pName, $pParams = array (), $pAssert = true)
    {
        $id = is_string ($pName) ? $pName : serialize($pName);
        if (!isset($this->_fields[$id])) {
            if (isset ($pParams['kind']) && $pParams['kind']=='error') {
                return false;
            }
            $pParams['type'] = isset($pParams['type']) ? $pParams['type'] : null;
            $pParams['name'] = $pName;
            $this->_fields[$id] = CopixFieldFactory::get($pParams['type'], $pParams, $pAssert);
            if (!($this->_fields[$id] instanceof ICopixFieldForm)) {
                unset ($this->_fields[$id]);
                throw new CopixException ($pType.' n\'implémente pas ICopixFieldForm');
            }
            $this->_fields[$id]->setMode ($this->_mode);
            $this->_fields[$id]->fillFromRecord($this->_record);
        } else {
            foreach ($pParams as $key=>$params) {
                if ($key != 'validator') {
                    $this->_fields[$id]->setParam($key,$params);
                }
            }
        }
        if (CopixRequest::get('error_'.$this->_id)==null) {
            $this->_fields[$id]->setDefaultValue(isset($pParams['value']) ? $pParams['value'] : null);
        }

        return $this->_fields[$id];
    }

    /**
     * Gestion d'un champ
     *
     * @param string $pField Gestion des champs
     * @param array $pParams les paramètres
     * @return le HTML du champ
     */
    public function getFieldHTML ($pType, $pParams)
    {
        if ($pParams['type'] == 'submit') {
            $pParams['value'] = (isset ($pParams['value'])) ? 'value="'.$pParams['value'].'"' : null;
            return "<input type='submit' ".$pParams['value']." />";
        }

        $pParams['type'] = $pType;
        if (($field = $this->getField ($pParams['name'], $pParams)) === false) {
            return '';
        }

        if (isset ($pParams['validator'])) {
            $field->addValidator ($pParams['validator']);
        }

        if (!$this->_create || _request ('error_'.$this->_id) != null) {
            //$this->_fields[$id]->fillFromRequest ();
        } else {
            $field->reset ();
        }
        if (isset ($pParams['kind'])) {
            switch ($pParams['kind']) {
                case 'error':
                if (isset ($pParams['errortemplate'])) {
                    return $field->getHTMLError ($pParams['errortemplate']);
                } else {
                    return $field->getHTMLError ();
                }
                break;
                case 'field':
                    if (isset($pParams['mode']) && $pParams['mode'] == 'view') {
                        return $field->getHTMLFieldView ();
                    } else {
                        return $field->getHTMLField ();
                    }
            }
        }
        if (isset($pParams['mode']) && $pParams['mode'] == 'view') {
            return $field->getHTML('view');
        } else {
            return $field->getHTML();
        }
    }

    /**
     * Lance les checks des differents champs
     */
    public function doValid ()
    {
        if ($this->createRecord ()) {
            throw new CopixFormCheckException ($this->_errors);
        }
    }

    /**
     * Lance l'enregistrement
     * Teste la validité des champs
     * @return array les pk créé
     */
    public function doRecord ()
    {
        if (!$this->_mustDoRecord) {
            return array ();
        }
        if ($this->_datasourceName == null) {
            throw new CopixFormException (_i18n('copix:copixform.message.noDatasource'));
        }
        $this->errors = array();
        $result = null;
        try {
            if (!isset($this->_create) || $this->_create) {
                CopixLog::log('create : '.var_export($this->_record,true),'copixforms');
                $result = $this->_datasource->save($this->_record);
            } else {
                CopixLog::log('update : '.var_export($this->_record,true),'copixforms');
                $result = $this->_datasource->update($this->_record);
            }
        } catch (CopixDAOCheckException $e) {
            $this->errors = $e->getErrors();
            CopixLog::log(serialize($this->errors));
            throw new CopixFormCheckException ($e->getErrors());
        }
        $arPk = array ();
        foreach ($this->_datasource->getPk () as $pk) {
            $arPk[$pk] = isset($result->$pk) ? $result->$pk : null;
        }
        return $arPk;
    }

    //Parti qui gère les droits

    public function setMode ($pMode)
    {
        $this->_mode = $pMode;
    }
}
