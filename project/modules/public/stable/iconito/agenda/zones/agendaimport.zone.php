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

class ZoneAgendaImport extends CopixZone {

	function _createContent (&$toReturn) {
		
		$serviceAuth   = new AgendaAuth;
		$tpl = & new CopixTpl ();
		
		//gestion des erreurs
		if ($this->params['e'] == 1){
			$tpl->assign('showError', $this->params['e']);
		}
		
		$tpl->assign('arError'     , $this->params['errors']);
		$tpl->assign('importParams', $this->params['importParams']);
		
		//vérification des droits d'écriture sur les agendas
		$listeFiltre = $this->params['arTitleAgendasAffiches'];
		//on vérifie les droits de lecture des utilisateurs
		foreach((array)$listeFiltre as $key=>$title_agenda){
			//on vérifie si l'utilisateur a les droits de lecture sur la liste des agendas
			if($serviceAuth->getCapability($key) < $serviceAuth->getModerate()){
				unset($listeFiltre[$key]);
			}
		}
		
		$tpl->assign('arTitleAgendasAffiches', $listeFiltre);
		
		$toReturn = $tpl->fetch ('import.agenda.tpl');
		return true;
	}
}
?>
