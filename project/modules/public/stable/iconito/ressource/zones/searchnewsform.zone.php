<?php

class ZoneSearchNewsForm extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = new CopixTpl ();
		$toReturn = $tpl->fetch ('searchnewsform.tpl');
		return true;
	}
}

?>
