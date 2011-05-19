<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneFilterClass extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
    
    // Récupérations des filtres en session
	  $ppo->selected = $this->getParam ('selected', null);
	  
	  if (!is_null ($schoolId = $this->getParam('school_id', null))) {

	    // Récupération de l'année scolaire
      if (is_null($grade = _sessionGet('grade'))) {

        $grade = Kernel::getAnneeScolaireCourante ()->id_as;
      }
      
	    // Récupération des écoles de la ville sélectionnée pour liste déroulante
	    $classDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	    $classes = $classDAO->getBySchool ($schoolId, $grade);
	    
    
      $ppo->classesIds   = array('');
      $ppo->classesNames = array('');
    
  	  foreach ($classes as $class) {
	    
  	    $ppo->classesIds[]   = $class->id;
  	    $ppo->classesNames[] = $class->nom;
  	  }
    }
    
    $toReturn = $this->_usePPO ($ppo, '_filter_class.tpl');
  }
}