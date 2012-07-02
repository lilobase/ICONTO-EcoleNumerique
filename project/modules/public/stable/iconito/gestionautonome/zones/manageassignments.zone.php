<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneManageAssignments extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $nodeId  = $this->getParam ('nodeId');
	  $ppo->filters = _sessionGet ('gestionautonome|manage_assignments_filters_'.$nodeId);
	  
	  $originAssignments = array();
	  $destinationAssignments = array();
	  
	  if ($ppo->filters['originUserType'] == 'USER_ELE') {
	    
	    // Récupération des élèves
	    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	    
	    $originAssignments        = $studentDAO->findForManageAssignments ($ppo->filters);
	    $destinationAssignments   = $studentDAO->findAssigned ($ppo->filters);
	  }
	  else {
	    
	    // Récupération des enseignants
  	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
  	  $originAssignments        = $personnelDAO->findTeachersForManageAssignments ($ppo->filters);
  	  
  	  if (isset($ppo->filters['destinationSchool']) || isset($ppo->filters['destinationClassroom'])) {
  	    
  	    $destinationAssignments   = $personnelDAO->findAssignedTeachers ($ppo->filters);
  	  }
	  }

	  foreach ($originAssignments as $originAssignment) {
	    
	    if ($originAssignment->nom_classe && $originAssignment->is_affect) {
	      
	      $ppo->originAssignments[$originAssignment->id_classe][$originAssignment->id_niveau][] = $originAssignment;
	      $ppo->classrooms[$originAssignment->id_classe] = $originAssignment->nom_classe;
	      $ppo->classroomLevels[$originAssignment->id_niveau] = $originAssignment->nom_niveau;
	    }
	    else {
	      
	      $ppo->classrooms[0] = 'Sans affectation';
	      $ppo->originAssignments[0][''][] = $originAssignment;
	    }
	  }
	  
	  if (!isset($ppo->filters['destinationClassroom'])) {
	    
	    $destinationClassrooms = _ioDAO('kernel|kernel_bu_ecole_classe')->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
  	  foreach ($destinationClassrooms as $destinationClassroom) {

  	    if ($ppo->filters['originUserType'] == 'USER_ELE') {

  	      $levels = $destinationClassroom->getLevels();
  	      foreach ($levels as $level) {

  	        $ppo->destinationAssignments[$destinationClassroom->id][$level->id_n] = array();
  	        $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
  	        $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
  	      }
  	    }
  	    else {

  	      $ppo->destinationAssignments[$destinationClassroom->id][''] = array();
  	      $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
  	    }
  	  }
	  }
	  
	  foreach ($destinationAssignments as $destinationAssignment) {
	    
	    $ppo->destinationAssignments[$destinationAssignment->id_classe][$destinationAssignment->id_niveau][] = $destinationAssignment;
	    $ppo->classrooms[$destinationAssignment->id_classe] = $destinationAssignment->nom_classe;
	    $ppo->classroomLevels[$destinationAssignment->id_niveau] = $destinationAssignment->nom_niveau;
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_manage_assignments.tpl');
  }
}