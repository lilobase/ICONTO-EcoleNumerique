<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneFilterSchool extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->role = $this->getParam ('role'); 
	  
	  // Récupérations des filtres en session
	  $ppo->listFilters = _sessionGet ('gestionautonome|addExisting');
	  if (!is_array ($ppo->listFilters)) {
	    
	    $ppo->listFilters = array ();
	  }

    // Récupération de la ville sélectionnée par le filtre
	  $ppo->cityId   = $ppo->listFilters['city'];           
	  
	  // Récupération des écoles de la ville sélectionnée pour liste déroulante
	  $schools = _ioDAO ('kernel|kernel_bu_ecole')->getByCity ($ppo->cityId);

	  foreach ($schools as $school) {
	    
	    $ppo->schoolsIds[]   = $school->numero;
	    $ppo->schoolsNames[] = $school->nom;
	  }

    $toReturn = $this->_usePPO ($ppo, '_filter_school.tpl');
  }
}