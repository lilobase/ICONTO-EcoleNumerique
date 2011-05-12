<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneMenuParent extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
    $ppo->nid   = $this->getParam('nid');
    $ppo->jour  = $this->getParam('date_jour');
    $ppo->mois  = $this->getParam('date_mois');
    $ppo->annee = $this->getParam('date_annee');
    
    // Récupération du nombre de mémo en attente de signature
    $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
    $ppo->nombreMemos = $memoDAO->retrieveNombreMemosNonSignesParEleve($ppo->nid);

    $toReturn = $this->_usePPO ($ppo, '_menu_parent.tpl');
  }
}