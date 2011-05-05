<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneTodoWorks extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
    if ( is_null($ppo->nid = $this->getParam('nid'))
      || is_null($ppo->day = $this->getParam('date_day')) 
      || is_null($ppo->month = $this->getParam('date_month')) 
      || is_null($ppo->year  = $this->getParam('date_year'))) {
        
      $toReturn = '';
    	return;  
    }
    
    $time = mktime(0, 0, 0, $ppo->month, $ppo->day, $ppo->year);
    
    // S'il s'agit d'un parent, récupération de l'identifiant de l'élève nécessaire
    if (Kernel::isParent()) {
      
      $studentAssignmentDAO = _ioDAO('kernel|kernel_bu_ele_affect');
      $currentAssignment = $studentAssignmentDAO->getCurrentAffectByStudent ($ppo->nid);
      
      $classId = $currentAssignment->affect_classe;
      $studentId  = $ppo->nid;
    }
	  
	  $workDAO = _ioDAO ('cahier|cahier_travail');
	  if (Kernel::isEleve()) {
	    
	    $ppo->works = $workDAO->findWorksForStudent($ppo->nid, DAOCahier_travail::TYPE_TODO, $time);
	  }
	  elseif (Kernel::isParent()) {
	    
	    $ppo->works = $workDAO->findWorksForStudent($studentId, DAOCahier_travail::TYPE_TODO, $time);
	  }
	  elseif (Kernel::isEnseignant()) {
	    
	    $ppo->works = $workDAO->findWorksForClassroom($ppo->nid, DAOCahier_travail::TYPE_TODO, $time);
	  }

	  $toReturn = $this->_usePPO ($ppo, '_todo_works.tpl');
  }
}