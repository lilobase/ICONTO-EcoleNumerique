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
 * Le detail d'un dossier pendant une commission
 * @since 2009/08/19
 * @param object $rDemande Recordset de la demande
 * @param object $rCommission Recordset de la commission
 * @param boolean $end Si on est sur la page de fin d'une commission
 */
 
_classInclude ("kernel|DAORecordCommission2Demande");

class ZoneCommission_Dossier extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->rDossier = $this->getParam ('rDossier');
		$ppo->rCommission = $this->getParam ('rCommission');
		$ppo->errors = $this->getParam ('errors');
		$ppo->end = $this->getParam ('end');
		//Tools::print_r2($ppo->rDossier);
		
		$ppo->decisions = array(
			'ACCEPTE' => DAORecordCommission2Demande::DECISION_ACCEPTE,
			'AJOURNE' => DAORecordCommission2Demande::DECISION_AJOURNE,
			'REFUSE' => DAORecordCommission2Demande::DECISION_REFUSE,
			'AUCUN' => DAORecordCommission2Demande::DECISION_AUCUN,
			);
		
		$toReturn = $this->_usePPO ($ppo, 'commission_dossier.tpl');
		
		return true;
	}
} ?>