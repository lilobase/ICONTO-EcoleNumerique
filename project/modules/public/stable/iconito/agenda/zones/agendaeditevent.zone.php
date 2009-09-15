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

require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'agendaauth.class.php');

class ZoneAgendaEditEvent extends CopixZone {
	function _createContent (&$toReturn) {
	
		$serviceAuth   = new AgendaAuth;
		$tpl = & new CopixTpl ();		

		//var_dump($this->params['toEdit']);
		
		////cas où on est passé par le prepareEdit
		//si un évènement est répété, la case doit être cochée
		if($this->params['toEdit']->everyday_event == 1 || $this->params['toEdit']->everyweek_event == 1 || $this->params['toEdit']->everymonth_event == 1 || $this->params['toEdit']->everyyear_event == 1){
			$this->params['toEdit']->repeat = 1;
		}
		
		//on met à jour la balise select
		if($this->params['toEdit']->everyday_event == 1){
			$this->params['toEdit']->repeat_event = "everyday_event";
		}
		if($this->params['toEdit']->everyweek_event == 1){
			$this->params['toEdit']->repeat_event = "everyweek_event";
		}
		if($this->params['toEdit']->everymonth_event == 1){
			$this->params['toEdit']->repeat_event = "everymonth_event";
		}
		if($this->params['toEdit']->everyyear_event == 1){
			$this->params['toEdit']->repeat_event = "everyyear_event";
		}
		if($this->params['toEdit']->endrepeat_event){
			$this->params['toEdit']->endrepeat_event = $this->params['toEdit']->endrepeat_event;
		}

		//gestion des erreurs
		if ($this->params['e'] == 1){
			$tpl->assign('showError', $this->params['e']);
		}		
		
		//vérification des droits d'écriture sur les agendas
		$listeFiltre = $this->params['arTitleAgendasAffiches'];
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
      if ($this->params['toEdit']->id_agenda == $idAgenda)
        $id_agenda = $idAgenda;
    }
    
    //print_r($_SESSION);
    
		$tpl->assign('arTitleAgendasAffiches', $listeFiltre);
		$tpl->assign('arError'    , $this->params['errors']);
		$tpl->assign('toEdit'     , $this->params['toEdit']);
		$tpl->assign ('wikibuttons_desc' , CopixZone::process ('kernel|wikibuttons', array('field'=>'desc_event', 'object'=>array('type'=>'MOD_AGENDA', 'id'=>$id_agenda))));
		
		$toReturn = $tpl->fetch ('editevent.agenda.tpl');
		return true;
	}
}
?>
