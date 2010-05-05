<?php

/**
 * Affichage des classes
 */
class ZoneClassroom extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  if (is_null($schoolId = $this->getParam('school_id'))) {
	    
	    $toReturn = '';
	    return;
	  }
	  
	  // Récupération de l'année scolaire
	  $grade = _sessionGet('grade');
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $ppo->classrooms = $classroomDAO->getBySchool ($schoolId, $grade);
	  
    $toReturn = $this->_usePPO ($ppo, '_classroom.tpl');
  }
}