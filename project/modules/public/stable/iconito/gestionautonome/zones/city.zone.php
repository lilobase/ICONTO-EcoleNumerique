<?php

/**
 * Affichage des villes
 */
class ZoneCity extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  if (is_null($citiesGroupId = $this->getParam('cities_group_id'))) {
	    
	    $toReturn = '';
	    return;
	  }
	  
	  $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
	  $ppo->cities = $cityDAO->getByIdGrville ($citiesGroupId);
	  
	  // Récupération des noeuds ouvert
	  $ppo->nodes = _sessionGet('cities_nodes');
	  if (is_null($ppo->nodes)) {
	    
	    $ppo->nodes = array();
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_city.tpl');
  }
}