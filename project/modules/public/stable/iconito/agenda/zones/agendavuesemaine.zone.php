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

require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'dateservices.class.php');
require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'agendaservices.class.php');
require_once (COPIX_MODULE_PATH.'agenda/'.COPIX_CLASSES_DIR.'agendaauth.class.php');

class ZoneAgendaVueSemaine extends CopixZone {
	
	function _createContent (&$toReturn) {
		
		$service       = new DateService;
		$serviceAgenda = new AgendaService();
		$serviceType   = new AgendaType();
		$serviceAuth   = new AgendaAuth;
		
		//on determine la date du jour en timestamp
		$dimanche = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 0);
		$lundi    = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 1);
		$mardi    = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 2);
		$mercredi = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 3);
		$jeudi    = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 4);
		$vendredi = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 5);
		$samedi   = $service->numweekToDate($this->params['elementsSemaineAffichee']->numSemaine, $this->params['elementsSemaineAffichee']->annee, 6);
		
		
		$tpl = & new CopixTpl ();
		
		//date du jour
		$tpl->assign('dimanche'        , date('d', $dimanche));
		$tpl->assign('lundi'           , date('d', $lundi));
		$tpl->assign('mardi'           , date('d', $mardi));
		$tpl->assign('mercredi'        , date('d', $mercredi));
		$tpl->assign('jeudi'           , date('d', $jeudi));
		$tpl->assign('vendredi'        , date('d', $vendredi));
		$tpl->assign('samedi'          , date('d', $samedi));
		
		$tpl->assign('date_deb'        , date('Ymd', $lundi));
		$tpl->assign('date_fin'        , date('Ymd', $dimanche));
		$tpl->assign('moisDebutSemaine', $service->moisNumericToMoisLitteral(date('m', $lundi)));
		$tpl->assign('moisFinSemaine'  , $service->moisNumericToMoisLitteral(date('m', $dimanche)));
		
		$tpl->assign('semaine'         , $this->params['elementsSemaineAffichee']->numSemaine);
		$tpl->assign('annee'           , $this->params['elementsSemaineAffichee']->annee);
		
		//on v�rifie si un agenda de classe est affich�
		//$lecon = false;
		$readLecon  = false;
		$writeLecon = false;
		$idAgendaScolaire = null;
		$agendasAffiches = $this->getParam('agendasAffiches',null);
		foreach($this->params['elementsSemaineAffichee']->agendas as $id_agenda){
			if($serviceAgenda->getTypeAgendaByIdAgenda($id_agenda) == $serviceType->getClassRoom()){
				//on v�rifie si l'utilisateur peut �crire des le�ons
				if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteLecon()){
					$writeLecon = true;
					//$lecon = true;
					$idAgendaScolaire = $id_agenda;
					break;
				}
				//on v�rifie si l'utilisateur peut lire les le�ons
				if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getRead()){
					$readLecon = true;
					//$lecon = true;
					//$idAgendaScolaire = $id_agenda;
					//break;
				}
			}
		}
		
		//on v�rifie si l'utilisateur a les droits d'�criture sur un des agendas affich�s
		$writeAgenda = false;
		$agendasAffiches = $this->getParam('agendasAffiches',null);
		foreach($this->params['elementsSemaineAffichee']->agendas as $id_agenda){
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
				$writeAgenda = true;
				break;
			}
		}
		
		//on construit un tableau de droits pour chaque agenda affich�
		$arDroits = array();
		foreach($this->params['elementsSemaineAffichee']->agendas as $id_agenda){
			
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getRead()){
				$arDroits[$id_agenda]->canRead = true;
			}
			else{
				$arDroits[$id_agenda]->canRead = false;
			}
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getWriteAgenda()){
				$arDroits[$id_agenda]->canWrite = true;
			}
			else{
				$arDroits[$id_agenda]->canWrite = false;
			}
			if($serviceAuth->getCapability($id_agenda) >= $serviceAuth->getModerate()){
				$arDroits[$id_agenda]->canModerate = true;
			}
			else{
				$arDroits[$id_agenda]->canModerate = false;
			}
		}
		
		
		//on construit le tableau de couleurs associ�es au type d'agenda
		$arColorByIdAgenda = array();
		foreach($this->params['elementsSemaineAffichee']->agendas as $id_agenda){
			$arColor = $serviceType->getColors($serviceAgenda->getTypeAgendaByIdAgenda($id_agenda));
			$i = 0;
			foreach($arColorByIdAgenda as $idAgenda=>$couleurAgenda){	
				if($arColorByIdAgenda[$idAgenda] == $arColor[$i]){
					$i = $i + 1;
				}
			}
			if($i < count($arColor)){
				$arColorByIdAgenda[$id_agenda] = $arColor[$i];
			}
			else{
				$arColorByIdAgenda[$id_agenda] = $arColor[0];
			}
		}		

		$tpl->assign('arColorByIdAgenda', $arColorByIdAgenda);		
		
		//on d�termine l'heure de d�but et de fin pour l'affichage du calendrier
		$tpl->assign('heure_deb'       , $this->params['heureDeb']);
		$tpl->assign('heure_fin'       , $this->params['heureFin']);

		$tpl->assign('arEventByDay'   , $this->params['arEventByDay']);
				
		$tpl->assign('readLecon'     , $readLecon);
		$tpl->assign('writeLecon'    , $writeLecon);
		$tpl->assign('agendaScolaire', $idAgendaScolaire);
		$tpl->assign('arLecons'      , $this->params['arLecons']);
		
		$tpl->assign('writeAgenda' , $writeAgenda);
		$tpl->assign('arDroits'    , $arDroits);
		$tpl->assign('todayJour' , date('d'));
		$tpl->assign('todaySemaine' , date('W'));
		$tpl->assign('todayAnnee' , date('Y'));
		
		//param�tres pour passer d'une semaine � l'autre
		$tpl->assign('semaine_precedente', $service->dateToWeeknum(mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)-7, date('Y', $lundi))));
		$tpl->assign('semaine_suivante'  , $service->dateToWeeknum(mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)+7, date('Y', $lundi))));
		$tpl->assign('annee_precedente'  , date('Y', mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)-4, date('Y', $lundi))));
		$tpl->assign('annee_suivante'    , date('Y', mktime(0, 0, 0, date('m', $lundi), date('d', $lundi)+10, date('Y', $lundi))));

		$toReturn = $tpl->fetch ('vuesemaine.agenda.ptpl');
		return true;
	}
}
?>