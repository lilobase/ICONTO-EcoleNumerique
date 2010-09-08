<?php


class ZoneDashboardWelcome extends CopixZone {

	function _createContent (&$toReturn) {
		$ppo = new CopixPPO ();		
		$toReturn = "";
		
		$toReturn = $this->_usePPO ($ppo, 'dashboardwelcome.tpl');
		return true;
	}



}
?>
