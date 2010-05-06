<?php

/**
 * Affichage des groupes de villes
 */
class ZoneCitiesGroup extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
    
    $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');

    if (_currentUser ()->testCredential ('group:[Admin]@auth|dbgrouphandler')) {
      
      $criteria = _daoSp ();
      $criteria->orderBy ('nom_groupe');
      $ppo->citiesGroups = $citiesGroupDAO->findBy ($criteria);
    }
  
	  //$ppo->citiesGroups = $citiesGroupDAO->findByUserIdAndUserType ($user->getId (), $user->getExtra('type'));
	   
	  // Récupération des noeuds ouvert
	  $ppo->nodes = _sessionGet('cities_groups_nodes');
	  if (is_null($ppo->nodes)) {
	    
	    $ppo->nodes = array();
	  }

    $toReturn = $this->_usePPO ($ppo, '_cities_group.tpl');
  }
}