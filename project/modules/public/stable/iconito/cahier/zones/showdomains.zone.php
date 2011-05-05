<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneShowDomains extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres    
    if (is_null($ppo->nid = $this->getParam('nid'))) {
        
      $toReturn = '';
      return;  
    }
    
	  $domainDAO = _ioDAO('cahier|cahier_domaine');
	  $ppo->domains = $domainDAO->findByIdClass($ppo->nid);
	  
	  $toReturn = $this->_usePPO ($ppo, '_show_domains.tpl');
  }
}