<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneChangeClassroom extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->mode = $this->getParam ('mode');
	  $ppo->filters = _sessionGet ('gestionautonome|assignments_management_filters');
	  
	  $originAssignments      = array();
	  $destinationAssignments = array();
	  $withoutAssignments     = array();
	  
	  // Récupération des affectations
	  if ($ppo->filters['originUserType'] == 'USER_ELE') {
	    
	    $originFilters = array (
	      'grade'     => $ppo->filters['originGrade'],
	      'cityGroup' => $ppo->filters['originCityGroup'],
	      'city'      => $ppo->filters['originCity'],
	      'school'    => $ppo->filters['originSchool'],
	      'classroom' => isset ($ppo->filters['originClassroom']) ? $ppo->filters['originClassroom'] : null,
	      'level'     => isset ($ppo->filters['originLevel']) ? $ppo->filters['originLevel'] : null,
	      'lastname'  => isset ($ppo->filters['originLastname']) ? $ppo->filters['originLastname'] : null,
	      'firstname' => isset ($ppo->filters['originFirstname']) ? $ppo->filters['originFirstname'] : null,
	    );
	    
	    $destinationFilters = array (
	      'grade'     => $ppo->filters['destinationGrade'],
	      'cityGroup' => $ppo->filters['destinationCityGroup'],
	      'city'      => $ppo->filters['destinationCity'],
	      'school'    => $ppo->filters['destinationSchool'],
	      'classroom' => isset ($ppo->filters['destinationClassroom']) ? $ppo->filters['destinationClassroom'] : null,
	      'level'     => isset ($ppo->filters['destinationLevel']) ? $ppo->filters['destinationLevel'] : null,
	    );
	    
	    // Récupération des élèves
	    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
	    $originAssignments      = $ppo->mode == 'changeClassroom' ? $studentDAO->findStudentsForAssignment ($originFilters, $ppo->filters['originGrade']) : $studentDAO->findForManageAssignments ($ppo->filters);
	    $destinationAssignments = $studentDAO->findAssigned ($destinationFilters);
	  }
	  else {
	    
	    // Récupération des enseignants
  	  $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
  	  $originAssignments = $personnelDAO->findTeachersForManageAssignments ($ppo->filters);
  	  
  	  if (isset($ppo->filters['destinationSchool']) || isset($ppo->filters['destinationClassroom'])) {
  	    
  	    $destinationAssignments = $personnelDAO->findAssignedTeachers ($ppo->filters);
  	  }
	  }
    
    // Construction du tableau des affectations d'origine pour l'affichage
	  foreach ($originAssignments as $originAssignment) {
	    
	    if ($originAssignment->nom_classe && $originAssignment->is_affect) {
	      
	      $ppo->originAssignments[$originAssignment->id_niveau][$originAssignment->id_classe][] = $originAssignment;
	      $ppo->classrooms[$originAssignment->id_classe] = $originAssignment->nom_classe;
	      $ppo->classroomLevels[$originAssignment->id_niveau] = $originAssignment->nom_niveau;
	    }
	    else {
	      
	      $ppo->classrooms[0] = 'Sans affectation';
	      $withoutAssignments[] = $originAssignment;
	    }
	  }
	  
	  foreach($withoutAssignments as $withoutAssignment) {
	    
	    $ppo->originAssignments[''][0][] = $withoutAssignment;
	  }
	  
	  $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
	  
	  // Construction du tableau des affectations de destination pour l'affichage (Elèves)
	  if ($ppo->filters['originUserType'] == 'USER_ELE') {

      if (isset($ppo->filters['destinationClassroom'])) {

        // Récupération de la classe sélectionnée par les filtres
        $destinationClassroom = $classroomDAO->get ($ppo->filters['destinationClassroom']);

        if (isset ($ppo->filters['destinationLevel'])) {

          $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);

          $ppo->destinationAssignments[$classroomLevel->id_n][$destinationClassroom->id] = array();
          $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
          $ppo->classroomLevels[$classroomLevel->id_n] = $classroomLevel->niveau_court;
        }
        else {

          $levels = $destinationClassroom->getLevels();
          foreach ($levels as $level) {

            $ppo->destinationAssignments[$level->id_n][$destinationClassroom->id] = array();
            $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
            $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
          }
        }
      }
      else {

        if (isset ($ppo->filters['destinationLevel'])) {

          // On récupère toutes les classes disponibles pour l'école et le niveau de classe sélectionnés
          $destinationClassrooms = $classroomDAO->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
          foreach ($destinationClassrooms as $destinationClassroom) {

            $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);

            $ppo->destinationAssignments[$ppo->filters['destinationLevel']][$destinationClassroom->id] = array();
            $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
            $ppo->classroomLevels[$ppo->filters['destinationLevel']] = $classroomLevel->niveau_court;
          }
        }
        else {

          // On récupère toutes les classes disponibles pour l'école
          $destinationClassrooms = $classroomDAO->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
          foreach ($destinationClassrooms as $destinationClassroom) {

            $levels = $destinationClassroom->getLevels();
    	      foreach ($levels as $level) {

    	        $ppo->destinationAssignments[$level->id_n][$destinationClassroom->id] = array();
    	        $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
    	        $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
    	      }
          }
        } 
      }
    }
    // Construction du tableau des affectations de destination pour l'affichage (Enseignants)
    else {

      if (isset($ppo->filters['destinationClassroom'])) {
        
        $destinationClassroom = _ioDAO('kernel|kernel_bu_ecole_classe')->get ($ppo->filters['destinationClassroom']);
        
        $ppo->destinationAssignments[''][$ppo->filters['destinationClassroom']] = array();
        $ppo->classrooms[$ppo->filters['destinationClassroom']] = $destinationClassroom->nom;
      }
      else {
        
        if (isset ($ppo->filters['destinationLevel'])) {
          $destinationClassrooms = $classroomDAO->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
        }
        else {
          $destinationClassrooms = $classroomDAO->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
        }

        foreach ($destinationClassrooms as $destinationClassroom) {

          $ppo->destinationAssignments[''][$destinationClassroom->id] = array();
          $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
        }
      }
    }
	  
	  // Ajout des affectations au tableau des affectations de destination destiné à l'affichage
	  foreach ($destinationAssignments as $destinationAssignment) {
	    
	    $ppo->destinationAssignments[$destinationAssignment->id_niveau][$destinationAssignment->id_classe][] = $destinationAssignment;
	    $ppo->classrooms[$destinationAssignment->id_classe] = $destinationAssignment->nom_classe;
	    $ppo->classroomLevels[$destinationAssignment->id_niveau] = $destinationAssignment->nom_niveau;
	  }
	  
	  $ppo->openedClassrooms = _sessionGet ('gestionautonome|assignments_management_classroom_state');
	  
    $toReturn = $this->_usePPO ($ppo, '_change_classroom.tpl');
  }
}