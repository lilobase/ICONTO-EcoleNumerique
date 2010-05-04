<?php

/**
 * Affichage des groupes de villes
 */
class ZoneCitiesGroup extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_group_villes');
	  $ppo->citiesGroup = $citiesGroupDAO->find ();
	  
    $toReturn = $this->_usePPO ($ppo, '_cities_group.tpl');
  }
}