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
	    
	    $destinationFilters = array (
	      'grade'     => $ppo->filters['destinationGrade'],
	      'cityGroup' => $ppo->filters['destinationCityGroup'],
	      'city'      => $ppo->filters['destinationCity'],
	      'school'    => $ppo->filters['destinationSchool'],
	      'classroom' => $ppo->filters['destinationClassroom'],
	      'level'     => $ppo->filters['destinationLevel']
	    );
	    
	    // Récupération des élèves
	    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	    
	    $originAssignments        = $studentDAO->findForManageAssignments ($ppo->filters);
	    $destinationAssignments   = $studentDAO->findAssigned ($destinationFilters);
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
	  
	  // Construction du tableau des affectations de destination pour l'affichage
	  if (!isset($ppo->filters['destinationClassroom'])) {
	    
	    if ($ppo->filters['originUserType'] == 'USER_ELE') {
        
        if (isset ($ppo->filters['destinationLevel'])) {
          
          // On récupère toutes les classes disponibles pour l'école et le niveau de classe sélectionnés
          $destinationClassrooms = _ioDAO('kernel|kernel_bu_ecole_classe')->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
          foreach ($destinationClassrooms as $destinationClassroom) {
            
            $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);

            $ppo->destinationAssignments[$destinationClassroom->id][$ppo->filters['destinationLevel']] = array();
  	        $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
  	        $ppo->classroomLevels[$ppo->filters['destinationLevel']] = $classroomLevel->niveau_court;
          }
        }
        else {
          
          // On récupère toutes les classes disponibles pour l'école
          $destinationClassrooms = _ioDAO('kernel|kernel_bu_ecole_classe')->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
          foreach ($destinationClassrooms as $destinationClassroom) {
            
            $levels = $destinationClassroom->getLevels();
    	      foreach ($levels as $level) {

    	        $ppo->destinationAssignments[$destinationClassroom->id][$level->id_n] = array();
    	        $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
    	        $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
    	      }
          }
        }
	    }
	    else {
	      
	      if (isset ($ppo->filters['destinationLevel'])) {
          
          $destinationClassrooms = _ioDAO('kernel|kernel_bu_ecole_classe')->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
        }
        else {
          
          $destinationClassrooms = _ioDAO('kernel|kernel_bu_ecole_classe')->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
        }
        
        foreach ($destinationClassrooms as $destinationClassroom) {
          
          $ppo->destinationAssignments[$destinationClassroom->id][''] = array();
  	      $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
        }
	    }
	  }
	  else {
	    
	    // Récupération de la classe sélectionnée par les filtres
	    $destinationClassroom = _ioDAO('kernel|kernel_bu_ecole_classe')->get ($ppo->filters['destinationClassroom']);
	    
	    if ($ppo->filters['originUserType'] == 'USER_ELE') {
        
        if (isset ($ppo->filters['destinationLevel'])) {
          
          $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);
          
          $ppo->destinationAssignments[$destinationClassroom->id][$classroomLevel->id_n] = array();
          $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
          $ppo->classroomLevels[$classroomLevel->id_n] = $classroomLevel->niveau_court;
        }
        else {
          
          $levels = $destinationClassroom->getLevels();
          foreach ($levels as $level) {

            $ppo->destinationAssignments[$destinationClassroom->id][$level->id_n] = array();
            $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
            $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
          }
        }
      }
	    else {
	      
	      $ppo->destinationAssignments[$destinationClassroom->id][''] = array();
	      $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
	    }
	  }
	  
	  // Ajout des affectations au tableau des affectations de destination destiné à l'affichage
	  foreach ($destinationAssignments as $destinationAssignment) {
	    
	    $ppo->destinationAssignments[$destinationAssignment->id_classe][$destinationAssignment->id_niveau][] = $destinationAssignment;
	    $ppo->classrooms[$destinationAssignment->id_classe] = $destinationAssignment->nom_classe;
	    $ppo->classroomLevels[$destinationAssignment->id_niveau] = $destinationAssignment->nom_niveau;
	  }
	  
	  $ppo->openedClassrooms = _sessionGet ('gestionautonome|manage_assignment_classroom_state');
	  
    $toReturn = $this->_usePPO ($ppo, '_manage_assignments.tpl');
  }
}