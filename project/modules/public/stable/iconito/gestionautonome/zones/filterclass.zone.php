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
	  $ppo->selected        = $this->getParam ('selected', null);
	  $ppo->withLabel       = $this->getParam ('with_label', true);
	  $ppo->withEmpty       = $this->getParam ('with_empty', true);
	  $ppo->withEmptyLabel  = $this->getParam ('with_empty_label', false);
	  
	  $grade = $this->getParam ('grade', _sessionGet('grade', Kernel::getAnneeScolaireCourante ()->id_as));
	  
	  if (!is_null ($schoolId = $this->getParam('school_id', null))) {
      
	    // Récupération des écoles de la ville sélectionnée pour liste déroulante
	    $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	    if (_currentUser ()->testCredential ('module:school|'.$schoolId.'|classroom|create@gestionautonome')) {

        $classes = $classroomDAO->getBySchool ($schoolId, $grade);
  	  }
  	  else {

        $groups = _currentUser ()->getGroups ();
        $classes = $classroomDAO->findBySchoolIdAndUserGroups ($schoolId, $groups['gestionautonome|iconitogrouphandler'], $grade);
  	  }	    
    
      $ppo->classesIds   = array();
      $ppo->classesNames = array();

  	  foreach ($classes as $class) {
  	    
  	    $levels = $class->getLevels();
  	    $classLevels = array();
  	    foreach ($levels as $level) {
  	      
  	      $classLevels[] = $level->niveau_court;
  	    }
  	    $ppo->classesIds[]   = $class->id;
  	    $ppo->classesNames[] = $class->nom.' ('.implode(', ', $classLevels).')';
  	  }
    }
    
    $toReturn = $this->_usePPO ($ppo, '_filter_class.tpl');
  }
}