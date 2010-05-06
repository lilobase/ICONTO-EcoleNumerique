<?php

/**
 * Affichage des groupes de villes
 */
class ZoneCitiesGroup extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
    
    $user = _currentUser ();

	  $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
	  $ppo->citiesGroups = $citiesGroupDAO->findByUserIdAndUserType ($user->getId (), $user->getExtra('type'));
	   
	  // Récupération des noeuds ouvert
	  $ppo->nodes = _sessionGet('cities_groups_nodes');
	  if (is_null($ppo->nodes)) {
	    
	    $ppo->nodes = array();
	  }

    $toReturn = $this->_usePPO ($ppo, '_cities_group.tpl');
  }
}