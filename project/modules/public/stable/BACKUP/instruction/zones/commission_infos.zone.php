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
 * Infos en entete d'une commission
 * @since 2009/08/25
 * @param object $rCommission Recordset de la commission
 * @param boolean $end Si on est sur la page de fin d'une commission
 */
 

class ZoneCommission_Infos extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->rCommission = $this->getParam ('rCommission');
		$ppo->page = $this->getParam ('page');


		$toReturn = $this->_usePPO ($ppo, 'commission_infos.tpl');
		
		return true;
	}
} ?>