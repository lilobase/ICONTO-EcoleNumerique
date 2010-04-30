<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneFilterGroupCity extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->role = $this->getParam ('role'); 
	  
	  // Récupérations des filtres en session
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }

	  $cityGroups = _ioDAO ('kernel_bu_groupe_villes')->findAll ();
	  
	  foreach ($cityGroups as $cityGroup) {
	    
	    $ppo->cityGroupsIds[]   = $cityGroup->id_grv;
	    $ppo->cityGroupsNames[] = $cityGroup->nom_groupe;
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_filter_groupcity.tpl');
  }
}