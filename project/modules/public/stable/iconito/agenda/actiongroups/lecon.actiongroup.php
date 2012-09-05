<?php
/**
* Actiongroup du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|agendaservices');
_classInclude('agenda|dateservices');
_classInclude('agenda|agendatype');
_classInclude('agenda|agendaauth');

class ActionGroupLecon extends CopixActionGroup
{
    /**
    * Fonction qui est appelée lorsque l'on veut modifier une leçon
    * Récupère l'objet en base de données et le stocke en session
    * @author Audrey Vassal <avassal@sqli.com>
    * @return redirige vers l'action "edit" de l'actiongroup
    */
    public function doPrepareEdit ()
    {
        $serviceAuth   = new AgendaAuth;
        $serviceType   = new AgendaType;
        $serviceAgenda = new AgendaService;

        if (!_request('id_lecon')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.missingParameters'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        $daoLecon = & CopixDAOFactory::getInstanceOf ('lecon');
        if (!$toEdit = $daoLecon->get (_request('id_lecon'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.unableToFind'),
                        'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        //on vérifie si l'utilisateur a les droits d'écriture de leçons sur l'agenda et que c'est un agenda de classe
        if($serviceAuth->getCapability($toEdit->id_agenda) < $serviceAuth->getWriteLecon() || $serviceAgenda->getTypeAgendaByIdAgenda($toEdit->id_agenda) != $serviceType->getClassRoom()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        $this->_setSessionLecon($toEdit);
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|lecon|edit'));
    }


    /**
    * Fonction qui est appelée lorsque l'on veut insérer une nouvelle leçon
    * Créé un objet vide  et initialise la propriété id_agenda
    * Stock l'objet en session
    * @author Audrey Vassal <avassal@sqli.com>
    * @return redirige vers l'action "edit" de l'actiongroup
    */
    public function doCreate ()
    {
        $serviceAuth   = new AgendaAuth;
        $serviceType   = new AgendaType;
        $serviceAgenda = new AgendaService;

        if (!_request('id_agenda') || !_request('date')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.error.missingParameters'),
            'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //on vérifie si l'utilisateur a les droits d'écriture de leçon sur l'agenda
        //et que l'agenda est un agenda de classe
        if($serviceAuth->getCapability(_request('id_agenda')) < $serviceAuth->getWriteLecon()  || $serviceAgenda->getTypeAgendaByIdAgenda(_request('id_agenda')) != $serviceType->getClassRoom()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        $lecon = & CopixDAOFactory::createRecord ('lecon');
        $lecon->id_agenda  = _request('id_agenda');
        $lecon->date_lecon = _request('date');
        $this->_setSessionLecon($lecon);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|lecon|edit'));
    }


    /**
    * Récupère l'objet en session
    * Appelle les zones agendamenu et agendaeditlecon
    * @author Audrey Vassal <avassal@sqli.com>
    */
    public function processGetEdit ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_agenda.css"));

    require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');

        $serviceAuth   = new AgendaAuth;
        $serviceType   = new AgendaType;
        $serviceAgenda = new AgendaService;
      $serviceDate   = new DateService();

        if (!$toEdit = $this->_getSessionLecon ()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.unableToGetEdited'),
            'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        $serviceAuth->getCapability($toEdit->id_agenda);

        if($serviceAuth->getCapability($toEdit->id_agenda) < $serviceAuth->getWriteLecon() || $serviceAgenda->getTypeAgendaByIdAgenda($toEdit->id_agenda) != $serviceType->getClassRoom()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        $listAgendas   = $serviceAgenda->getAvailableAgenda();
        $listAgendasAffiches = $serviceAgenda->getAgendaAffiches();

        //template pour agenda
        $tplAgenda = new CopixTpl();
        $tplAgenda->assign ('MAIN_AGENDA', CopixZone::process('agenda|agendaeditlecon', array('e'=>_request('e'), 'errors'=>_request('errors'), 'toEdit'=>$toEdit)));

        //template principal
        $tpl = new CopixTpl();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('agenda|agenda.title.lecon', array('jour'=>CopixDateTime::yyyymmddToDate($toEdit->date_lecon))));

    $menu = $serviceAgenda->getAgendaMenu('');
        $tpl->assign ('MENU', $menu);

        $tpl->assign ('MAIN'      , $tplAgenda->fetch('agenda|main.agenda.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Fonction qui est appelée lorsque l'on valide la saisie d'un évènement
    * Met à jour l'objet avec les données du formulaire
    * Vérifie les informations saisies dans le formulaire
    * @author Audrey Vassal <avassal@sqli.com>
    * @return redirige vers l'action "getVueSemaine" de l'actiongroup agenda
    */
    public function doValid ()
    {
        $serviceAuth   = new AgendaAuth;
        $serviceAgenda = new AgendaService;
        $serviceType   = new AgendaType;

        if (!$toValid = $this->_getSessionLecon()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('agenda.error.cannotFindSession'),
            'back'=>CopixUrl::get ('agenda|agenda|vueSemaine')));
        }

        if($serviceAuth->getCapability($toValid->id_agenda) < $serviceAuth->getWriteLecon() || $serviceAgenda->getTypeAgendaByIdAgenda($toValid->id_agenda) != $serviceType->getClassRoom()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('agenda.error.enableToWrite'),
                        'back'=>CopixUrl::get('agenda|agenda|vueSemaine')));
        }

        //demande de mettre l'objet à jour en fonction des valeurs saisies dans le formulaire
        $this->_validFromForm ($toValid);

        $errors = $this->_check();

        if (count($errors)>0){
            $this->_setSessionLecon($toValid);
            return CopixActionGroup::process('agenda|Lecon::getEdit', array('e'=>1, 'errors'=>$errors));
        } else{
            $daoLecon = & CopixDAOFactory::getInstanceOf ('lecon');
            $record = & CopixDAOFactory::createRecord ('lecon');

            $criteres = _daoSp();
            $criteres->addCondition('id_lecon', '=', $toValid->id_lecon);
            $resultat = $daoLecon->findBy($criteres);

            $modif = false;
            if (count($resultat) > 0){//modification
                $record = $resultat[0];
                $modif = true;
            }

            $record->id_agenda        = $toValid->id_agenda;
            $record->desc_lecon       = $toValid->desc_lecon;
            $record->date_lecon       = $toValid->date_lecon;

            if ($modif){
                $daoLecon->update ($record);
            } else{
                $daoLecon->insert ($record);
            }

            //on vide la session
            $this->_setSessionLecon(null);

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('agenda|agenda|vueSemaine'));
        }
    }

    //vérification des entrées du formulaire
    public function _check ()
    {
        $toReturn = array();
        if(_request('desc_lecon') == null || _request('desc_lecon') == ''){
            $toReturn[] = CopixI18N::get('agenda|agenda.error.nodesclecon');
        }
        return $toReturn;
    }

    /**
    * Mise en session des paramètres de la leçon en édition
    * @access: private.
    */
    public function _setSessionLecon ($toSet)
    {
        $tmp = _ioDao('lecon');
        $toSession = ($toSet !== null) ? serialize($toSet) : null;
        _sessionSet('modules|agenda|edited_lecon', $toSession);
    }

    /**
    * Récupération en session des paramètres de la leçon en édition
    * @access: private.
    */
    public function _getSessionLecon ()
    {
        $tmp = _ioDao('lecon');
        $inSession = _sessionGet ('modules|agenda|edited_lecon');
        return ($inSession) ? unserialize ($inSession) : null;
    }

    /**
    * @access: private.
    */
    public function _validFromForm (& $toUpdate)
    {
        $toCheck = array ('desc_lecon');
        foreach ($toCheck as $elem){
            if (_request($elem)){
                $toUpdate->$elem = _request($elem);
            }
        }
    }
}
