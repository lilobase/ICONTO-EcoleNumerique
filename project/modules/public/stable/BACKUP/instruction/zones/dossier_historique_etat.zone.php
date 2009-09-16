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
 * Zone affichant l'historique de l'evolution de l'etat d'un dossier
 * @since 2009/08/13
 * @param object $rDemande Recordset de la demande
 */

class ZoneDossier_historique_etat extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->rDemande = $this->getParam ('rDemande');
		
		$list = _ioDAO('kernel|demande_historique_etat')->findByDemande($ppo->rDemande->id);
		//$list = _ioDAO('kernel|demande_historique_etat')->findAll();
		$ppo->list = $list;
		
		$toReturn = $this->_usePPO ($ppo, 'dossier_historique_etat.tpl');
		
		return true;
	}
} ?>