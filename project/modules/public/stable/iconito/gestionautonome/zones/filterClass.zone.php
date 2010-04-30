<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneFilterClass extends CopixZone {

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
	  $ppo->schoolId   = $ppo->listFilters['school'];           
	  
	  // Récupération des écoles de la ville sélectionnée pour liste déroulante
	  $classes = _ioDAO ('kernel|kernel_bu_ecole_classe')->getBySchool ($ppo->schoolId);

	  foreach ($classes as $class) {
	    
	    $ppo->classesIds[]   = $class->id;
	    $ppo->classesNames[] = $class->nom;
	  }

    $toReturn = $this->_usePPO ($ppo, '_filter_class.tpl');
  }
}