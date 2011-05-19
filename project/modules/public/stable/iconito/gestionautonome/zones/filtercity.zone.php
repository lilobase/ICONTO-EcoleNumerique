<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneFilterCity extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupérations des filtres en session
	  $ppo->selected = $this->getParam ('selected', null);
    
	  if (!is_null ($cityGroupId = $this->getParam('city_group_id', null))) {
	    
	    $cityDAO = _dao ('kernel|kernel_bu_ville');
	    $cities = $cityDAO->getByIdGrville ($cityGroupId);
	    
	    $ppo->citiesIds   = array('');
	    $ppo->citiesNames = array('');
	    
	    foreach ($cities as $city) {
	      
	      $ppo->citiesIds[]   = $city->id_vi;
  	    $ppo->citiesNames[] = $city->nom;
	    }
	  }

    $toReturn = $this->_usePPO ($ppo, '_filter_city.tpl');
  }
}