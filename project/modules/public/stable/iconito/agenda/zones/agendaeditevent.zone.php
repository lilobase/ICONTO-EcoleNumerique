<?php
/**
* Zone du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|agendaauth');

class ZoneAgendaEditEvent extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $serviceAuth   = new AgendaAuth;
        $tpl = new CopixTpl ();

        $toEdit = $this->getParam('toEdit');
        //var_dump($toEdit);

        ////cas où on est passé par le prepareEdit
        //si un évènement est répété, la case doit être cochée
        if($toEdit->everyday_event == 1 || $toEdit->everyweek_event == 1 || $toEdit->everymonth_event == 1 || $toEdit->everyyear_event == 1){
            $toEdit->repeat = 1;
        }

        //on met à jour la balise select
        if($toEdit->everyday_event == 1){
            $toEdit->repeat_event = "everyday_event";
        }
        if($toEdit->everyweek_event == 1){
            $toEdit->repeat_event = "everyweek_event";
        }
        if($toEdit->everymonth_event == 1){
            $toEdit->repeat_event = "everymonth_event";
        }
        if($toEdit->everyyear_event == 1){
            $toEdit->repeat_event = "everyyear_event";
        }
        if(isset($toEdit->endrepeat_event)){
            $toEdit->endrepeat_event = $toEdit->endrepeat_event;
        }

        //gestion des erreurs
        if ($this->getParam('e') == 1){
            $tpl->assign('showError', $this->getParam('e'));
        }

        //vérification des droits d'écriture sur les agendas
        $listeFiltre = $this->getParam('arTitleAgendasAffiches');
        //on vérifie les droits de lecture des utilisateurs
        foreach((array)$listeFiltre as $key=>$title_agenda){
            //on vérifie si l'utilisateur a les droits de lecture sur la liste des agendas
            if($serviceAuth->getCapability($key) < $serviceAuth->getWriteAgenda()){
                unset($listeFiltre[$key]);
            }
        }

    //print_r($listeFiltre);
        $id_agenda = null;
    foreach ($listeFiltre as $idAgenda=>$title) {
      if ($toEdit->id_agenda == $idAgenda)
        $id_agenda = $idAgenda;
    }

        $tpl->assign('arTitleAgendasAffiches', $listeFiltre);
        $tpl->assign('arError'    , $this->getParam('errors'));
        $tpl->assign('toEdit'     , $toEdit);
        $tpl->assign ('wikibuttons_desc' , CopixZone::process ('kernel|wikibuttons', array('field'=>'desc_event', 'object'=>array('type'=>'MOD_AGENDA', 'id'=>$id_agenda))));

        $toReturn = $tpl->fetch ('editevent.agenda.tpl');
        return true;
    }
}
