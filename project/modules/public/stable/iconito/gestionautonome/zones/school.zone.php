<?php

/**
 * Affichage des écoles
 */
class ZoneSchool extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  if (is_null($cityId = $this->getParam('city_id'))) {
	    
	    $toReturn = '';
	    return;
	  }
	  
	  $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
	  $ppo->schools = $schoolDAO->getByCity ($cityId);
	  
	  // Récupération des noeuds ouvert
	  $ppo->nodes = _sessionGet('schools_nodes');
	  if (is_null($ppo->nodes)) {
	    
	    $ppo->nodes = array();
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_school.tpl');
  }
}