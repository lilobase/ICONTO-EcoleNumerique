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

class ZoneAgendaEditLecon extends CopixZone {
	function _createContent (&$toReturn) {
		
		$tpl  = & new CopixTpl ();		
		$serviceDate   = new DateService();
		
		if ($this->params['e'] == 1){
			$tpl->assign('showError', $this->params['e']);
		}
		
		$tpl->assign('listAgendas', $this->params['listAgendas']);
		$tpl->assign('arError'    , $this->params['errors']);
		$tpl->assign('toEdit'     , $this->params['toEdit']);
		
		
		//$moisLiteral = $serviceDate->moisNumericToMoisLitteral(substr($this->params['toEdit']->date_lecon, 4, 2));
		//$dayLiteral  = $serviceDate->dayNumericToDayLitteral($this->params['toEdit']->date_lecon);
		//$tpl->assign('literalDay', $dayLiteral);
		//$tpl->assign('month'     , $moisLiteral);
		//$tpl->assign('day'       , substr($this->params['toEdit']->date_lecon, 6, 2));
		//$tpl->assign('year'      , substr($this->params['toEdit']->date_lecon, 0, 4));
		
		$toReturn = $tpl->fetch ('editlecon.agenda.tpl');
		return true;
	}
}
?>
