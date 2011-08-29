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
	  $ppo->destinationClassroom = $this->getParam ('destinationClassroom');
	  $ppo->sourceClassroom = $this->getParam ('sourceClassroom');
	  $ppo->currentGrade = $this->getParam ('currentGrade');
	  $ppo->nextGrade = $this->getParam ('nextGrade');
	  
	  // Récupération des niveaux
    $ppo->sourceLevels = $ppo->sourceClassroom->getLevels ();
    $ppo->destinationLevels = $ppo->destinationClassroom->getLevels ();
    
    // Récupération des élèves sans affectation
    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
    $ppo->students = $studentDAO->findOldStudentsAssignmentsForNewAssignement ($ppo->sourceClassroom->id, $ppo->currentGrade->id_as, $ppo->nextGrade->id_as);
	  
    $toReturn = $this->_usePPO ($ppo, '_students_to_assign.tpl');
  }
}