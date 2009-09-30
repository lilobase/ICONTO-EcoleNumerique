<?php

/**
 * Zone qui affiche la page d'accueil
 * 
 * @package Iconito
 * @subpackage Welcome
 */

class ZoneWelcome extends CopixZone {

	/**
	 * Affiche la page d'accueil
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/05
	 */

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		CopixHTMLHeader::addCSSLink (_resource("styles/module_welcome.css"));
		$toReturn = $tpl->fetch('welcome_'.CopixI18N::getLang().'.tpl');
		return true;
	}
}
?>
