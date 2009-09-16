<?php
/**
 * @package petiteenfance
 * @subpackage kernel
 * @version   $Id: menu.zone.php 37 2009-08-10 10:34:42Z cbeyer $
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Zone affichant le menu d'un dossier
 * @since 2009/08/07
 * @param object $rDemande Recordset de la demande
 * @param string $tab Onglet selectionne
 */

class ZoneDossier_Menu extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->rDemande = $this->getParam ('rDemande');
		$ppo->tab = $this->getParam ('tab');
		
		$toReturn = $this->_usePPO ($ppo, 'dossier_menu.tpl');
		
		return true;
	}
} ?>