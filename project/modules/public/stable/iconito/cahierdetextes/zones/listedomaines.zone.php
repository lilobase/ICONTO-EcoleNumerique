<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneListeDomaines extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres 
	  $ppo->nid = $this->getParam('nid');
    
	  $domaineDAO = _ioDAO('cahierdetextes|cahierdetextesdomaine');
	  $ppo->domaines = $domaineDAO->findByClasse($ppo->nid);
	  
	  $toReturn = $this->_usePPO ($ppo, '_liste_domaines.tpl');
  }
}