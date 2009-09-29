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

_classInclude('agenda|agendaservices');
_classInclude('agenda|agendaauth');

class ZoneAgendaMenu extends CopixZone {

	function _createContent (&$toReturn) {
		
		$serviceAuth   = new AgendaAuth;
		$serviceType   = new AgendaType;
		$serviceAgenda = new AgendaService;
		
    
    
		$tpl = & new CopixTpl ();
		
		$agendaAffiches = AgendaService::getAgendaAffiches();

    $ableToWrite = $ableToModerate = false;
		//on vérifie les droits des utilisateurs sur la liste des agendas affichés
		foreach((array)$this->getParam('listAgendasAffiches') as $id_agenda){
			//on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
      //print_r($serviceAuth->getWriteAgenda());
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
				$ableToWrite = true;
				break;
			}
		}
		
		//on vérifie les droits des utilisateurs sur la liste des agendas affichés
		foreach((array)$this->getParam('listAgendasAffiches') as $id_agenda){
			//on vérifie si l'utilisateur a les droits d'import
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getModerate()){
				$ableToModerate = true;
				break;
			}
		}		

		$listeFiltre = $this->getParam('listAgendas');
		//on vérifie les droits de lecture des utilisateurs
		foreach((array)$listeFiltre as $key=>$agenda){
			//on vérifie si l'utilisateur a les droits de lecture sur la liste des agendas
			if($serviceAuth->getCapability($agenda->id_agenda) < $serviceAuth->getRead()){
				unset($listeFiltre[$key]);
			}
		}
				
		//on construit le tableau de couleurs associées au type d'agenda
		$arColorByIdAgenda = array();
		foreach((array)$listeFiltre as $agenda){
      //print_r("ID=".$agenda->id_agenda);
      //print_r($agenda);

			$arColor = $serviceType->getColors($serviceAgenda->getTypeAgendaByIdAgenda($agenda->id_agenda));
			$i = 0;
			foreach($arColorByIdAgenda as $idAgenda=>$couleurAgenda){	
				if($arColorByIdAgenda[$idAgenda] == $arColor[$i]){
					$i = $i + 1;
				}
			}
			if($i < count($arColor)){
				$arColorByIdAgenda[$agenda->id_agenda] = $arColor[$i];
			}
			else{
				$arColorByIdAgenda[$agenda->id_agenda] = $arColor[0];
			}
		}		
		
		
		//var_dump($listeFiltre);
		
    //die();
  	$tpl->assign('parent'             , $this->getParam('parent'));
		$tpl->assign('arColorByIdAgenda'  , $arColorByIdAgenda);
		$tpl->assign('listAgendas'        , $listeFiltre);
		$tpl->assign('ableToWrite'        , $ableToWrite);
		$tpl->assign('ableToModerate'     , $ableToModerate);
		$tpl->assign('agendasSelectionnes', $agendaAffiches);
		$tpl->assign('text', CopixI18N::get ('agenda|agenda.menu.agenda'));
		
		$toReturn = $tpl->fetch ('menu.agenda.tpl');
		return true;
	}
}
?>
