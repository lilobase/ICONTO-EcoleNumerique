<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneAffichageMenu extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
    $ppo->nid   = $this->getParam('nid');
    $ppo->jour  = $this->getParam('date_jour');
    $ppo->mois  = $this->getParam('date_mois');
    $ppo->annee = $this->getParam('date_annee');
    
    $ppo->typeUtilisateur = _currentUser()->getExtra('type');
    
    $toReturn = $this->_usePPO ($ppo, '_affichage_menu.tpl');
  }
}