<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Sébastien CAS
*/
class ZoneStudentsToAssign extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->destinationClassroom  = $this->getParam ('destinationClassroom');
	  $ppo->sourceClassroom       = $this->getParam ('sourceClassroom');
	  $ppo->currentGrade          = $this->getParam ('currentGrade');
	  $ppo->nextGrade             = $this->getParam ('nextGrade');
	  $ppo->oldGrade              = $this->getParam ('oldGrade');
	  $ppo->schoolId              = $this->getParam ('schoolId');
	  
	  // Récupération des niveaux
    $ppo->sourceLevels      = $ppo->sourceClassroom ? $ppo->sourceClassroom->getLevels () : null;
    $ppo->destinationLevels = $ppo->destinationClassroom ? $ppo->destinationClassroom->getLevels () : null;
    
    // Récupération des élèves sans affectation
    $studentDAO     = _ioDAO ('kernel|kernel_bu_ele');
    if ($ppo->sourceClassroom) {
      
      $ppo->students  = $studentDAO->getStudentsByClass ($ppo->sourceClassroom->id);
    }
    else {
      
      $ppo->students  = $studentDAO->getStudentsWithoutAssignmentBySchool ($ppo->schoolId);
    }
	  
    $toReturn = $this->_usePPO ($ppo, '_students_to_assign.tpl');
  }
}