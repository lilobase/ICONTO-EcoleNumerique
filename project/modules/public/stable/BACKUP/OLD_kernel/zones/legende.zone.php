<?php
/**
 * @package petiteenfance
 * @subpackage kernel
 * @version   $Id: legende.zone.php 38 2009-08-10 15:22:15Z cbeyer $
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Legende dans une page
 * @param string $actions Actions utilisees dont on veut afficher la legende, separees par des virgules
 * @param string $titles Titres a utiliser, au lieu des libelles par defaut des actions
 * @since 2009/03/19
 */
class ZoneLegende extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();
		$pActions = $this->getParam ('actions');
		$pTitles = $this->getParam ('titles');
		
		$pActions = str_replace (' ','',$pActions);
		$pTitles = str_replace (' ','',$pTitles);
		
		$ppo->tabActions = explode(",", $pActions);
		$ppo->tabTitles = explode(",", $pTitles);
		
		$toReturn = $this->_usePPO ($ppo, 'legende.tpl');
		
		return true;
	}
} ?>