<?php
/**
 * @package petiteenfance
 * @subpackage kernel
 * @version   $Id: menu.zone.php 38 2009-08-10 15:22:15Z cbeyer $
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Zone affichant le menu
 */
class ZoneMenu extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->level_0 = $this->getParam ('level_0');
		$ppo->level_1 = $this->getParam ('level_1');
		
		$ppo->user = _currentUser ();
		
		$toReturn = $this->_usePPO ($ppo, 'menu.tpl');
		
		return true;
	}
} ?>
