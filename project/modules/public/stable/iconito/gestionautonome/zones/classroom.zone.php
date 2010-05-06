<?php

/**
 * Affichage des classes
 */
class ZoneClassroom extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  $user = _currentUser ();
	  
	  if (is_null($schoolId = $this->getParam('school_id'))) {
	    
	    $toReturn = '';
	    return;
	  }

	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  $ppo->classrooms = $classroomDAO->findByUserIdAndUserType ($schoolId, $user->getId (), $user->getExtra('type'));

    $toReturn = $this->_usePPO ($ppo, '_classroom.tpl');
  }
}