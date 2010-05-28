<?php


class ZoneViewHelp extends CopixZone {

	function _createContent (&$toReturn) {
		$ppo = new CopixPPO ();		
		$toReturn = "";
		
		$ppo->aide = _i18n('aide|aide.guide_ext');
		
		
		$toReturn = $this->_usePPO ($ppo, 'viewhelp.tpl');
		return true;
	}



}
?>
