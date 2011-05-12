<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneMenuEnseignant extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
    // Récupération des paramètres
    $ppo->nid   = $this->getParam('nid');
    $ppo->jour  = $this->getParam('date_jour');
    $ppo->mois  = $this->getParam('date_mois');
    $ppo->annee = $this->getParam('date_annee');

    $toReturn = $this->_usePPO ($ppo, '_menu_enseignant.tpl');
  }
}