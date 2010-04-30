<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneFilterCity extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->role = $this->getParam ('role'); 
	  
	  // Récupérations des filtres en session	  
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }
    
    $cityDAO = _dao ('kernel|kernel_bu_ville');
    
	  if ($cityGroupId = $ppo->listFilters['groupcity']) {
	    
	    $cities = $cityDAO->getByIdGrville ($cityGroupId);
	    
	    foreach ($cities as $city) {
	      
	      $ppo->citiesIds[]   = $city->id_vi;
  	    $ppo->citiesNames[] = $city->nom;
	    }
	  }

    $toReturn = $this->_usePPO ($ppo, '_filter_city.tpl');
  }
}