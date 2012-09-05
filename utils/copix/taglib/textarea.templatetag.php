<?php
/**
 * @package		copix
 * @subpackage	taglib
 * @author		Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright	CAP-TIC
 * @link		http://www.cap-tic.fr
 * @since 2009/08/13
 */

/**
 * @package		copix
 * @subpackage	taglib
 */
class TemplateTagTextarea extends CopixTemplateTag
{
    /**
    * Construction du textarea
    * @param	mixed	$pParams	tableau de paramètre ou clef
    * @return 	string	le textarea fabriqué
    * 	Parametres recommandes :
    * 		id : identifiant utile pour les labels, le javascript..
    * 		name : nom de l'input utile pour recuperer sa valeur avec php
    *
    * 	Autres parametres (liste non exhaustive)
    * 		value : valeur a afficher
    * 		readonly : si readonly
    */
    public function process($pParams)
    {
        extract ($pParams);

        if (!isset ($pParams['id']) && !isset ($pParams['name'])){
               throw new CopixTagException ("[CopixTagInput] Missing id or name parameter");
        }

        if (!isset ($pParams['id'])){
            $pParams['id'] = $pParams['name'];
        }elseif (!isset ($pParams['name'])){
            $pParams['name'] = $pParams['id'];
        }
        $readonly = '';
        if (isset ($pParams['readonly'])){
            if($pParams['readonly']){
                $readonly = 'readonly="readonly"';
            }
            unset ($pParams['readonly']);
        }

        if (isset ($pParams['value']) && !empty( $pParams['value']) ){
            $pParams['value'] = htmlspecialchars( $pParams['value'], ENT_QUOTES );
        }

        if (!isset($extra)) {
            $extra='';
        } else {
            unset($pParams['extra']);
        }
        $toReturn  = '<textarea '.$readonly.' '.$extra.' ';
        foreach ($pParams as $key=>$param) {
            if ($key!='value') {
                $toReturn .= $key.'="'.$param.'" ';
            }
        }
                $toReturn.='>';
                $toReturn .= $pParams['value'];
        $toReturn.='</textarea>';
        return $toReturn;
    }
}

