<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneManageAssignments extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération du context en session
      $ppo->filters = _sessionGet ('gestionautonome|assignments_management_filters');
      $ppo->openedClassrooms = _sessionGet ('gestionautonome|assignments_management_classroom_state');

      // Initialisation des tableaux des affectations
      $originAssignments      = array();
      $destinationAssignments = array();
      $withoutAssignments     = array();

      $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
      $groups = _currentUser ()->getGroups ();

      // Récupération des éléves ou des enseignants
      if ($ppo->filters['originUserType'] == 'USER_ELE') {

        // Récupération des élèves
        $studentDAO = _ioDAO ('kernel|kernel_bu_ele');

        if (isset ($ppo->filters['originSchool']) && !is_null ($ppo->filters['originSchool'])) {

          if ($ppo->filters['mode'] == 'changeClassroom') {

            $originAssignments = $studentDAO->findStudentsForAssignment ($ppo->filters['originGrade'], array (
              'cityGroup' => $ppo->filters['originCityGroup'],
              'city'      => $ppo->filters['originCity'],
              'school'    => $ppo->filters['originSchool'],
              'classroom' => $ppo->filters['originClassroom'],
              'level'     => $ppo->filters['originLevel'],
              'lastname'  => $ppo->filters['originLastname'],
              'firstname' => $ppo->filters['originFirstname']
            ));
          } else {
            $originAssignments = $studentDAO->findForManageAssignments ($ppo->filters['originGrade'], $ppo->filters);
          }
        }

        if (isset ($ppo->filters['destinationSchool']) && !is_null ($ppo->filters['destinationSchool'])) {

          $destinationAssignments = $studentDAO->findAssigned (
            array(
              'grade'     => $ppo->filters['destinationGrade'],
              'cityGroup' => $ppo->filters['destinationCityGroup'],
              'city'      => $ppo->filters['destinationCity'],
              'school'    => $ppo->filters['destinationSchool'],
              'classroom' => $ppo->filters['destinationClassroom'],
              'level'     => $ppo->filters['destinationLevel'],
            ),
            !_currentUser()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|teacher|update@gestionautonome') ? $groups['gestionautonome|iconitogrouphandler'] : null
          );
        }
      } else {

      // Récupération des enseignants
      $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
      if (isset ($ppo->filters['originSchool']) && !is_null ($ppo->filters['originSchool'])) {
        $originAssignments = $personnelDAO->findTeachersForManageAssignments ($ppo->filters);
      }

      if (isset($ppo->filters['destinationSchool']) && !is_null ($ppo->filters['destinationSchool'])) {

        $destinationAssignments = $personnelDAO->findAssignedTeachers (
          $ppo->filters,
          !_currentUser()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|teacher|update@gestionautonome') ? $groups['gestionautonome|iconitogrouphandler'] : null
        );
      }
      }

      foreach ($originAssignments as $originAssignment) {

        if (($ppo->filters['originUserType'] == 'USER_ELE' && $originAssignment->current)
          || $ppo->filters['originUserType'] == 'USER_ENS' && $originAssignment->type_ref == "CLASSE") {

          $ppo->originAssignments[$originAssignment->id_niveau][$originAssignment->id_classe][] = $originAssignment;
          $ppo->classrooms[$originAssignment->id_classe] = $originAssignment->nom_classe;
          $ppo->classroomLevels[$originAssignment->id_niveau] = $originAssignment->nom_niveau;
        } elseif (is_null($ppo->filters['originClassroom']) || $ppo->filters['mode'] != 'changeClassroom') {

          $ppo->classrooms[0] = 'Sans affectation';
          $withoutAssignments[] = $originAssignment;
        }
      }

      foreach($withoutAssignments as $withoutAssignment) {

      $ppo->originAssignments[''][0][] = $withoutAssignment;
    }

      // Construction du tableau des affectations de destination pour l'affichage
      if (!isset($ppo->filters['destinationClassroom'])) {

        if ($ppo->filters['originUserType'] == 'USER_ELE') {

        if (isset ($ppo->filters['destinationLevel']) && !is_null ($ppo->filters['destinationLevel'])) {

          // On récupère toutes les classes disponibles pour l'école et le niveau de classe sélectionnés
          if (_currentUser ()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|classroom|create@gestionautonome')) {

            $destinationClassrooms = $classroomDAO->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
          } else {

            $destinationClassrooms = $classroomDAO->findBySchoolIdAndUserGroups ($ppo->filters['destinationSchool'], $groups['gestionautonome|iconitogrouphandler'], $ppo->filters['destinationGrade'], $ppo->filters['destinationLevel']);
          }

          foreach ($destinationClassrooms as $destinationClassroom) {

            $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);

            $ppo->destinationAssignments[$ppo->filters['destinationLevel']][$destinationClassroom->id] = array();
              $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
              $ppo->classroomLevels[$ppo->filters['destinationLevel']] = $classroomLevel->niveau_court;
          }
        } else {

          // On récupère toutes les classes disponibles pour l'école
          if (_currentUser ()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|classroom|create@gestionautonome')) {

            $destinationClassrooms = $classroomDAO->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
          } else {

            $destinationClassrooms = $classroomDAO->findBySchoolIdAndUserGroups ($ppo->filters['destinationSchool'], $groups['gestionautonome|iconitogrouphandler'], $ppo->filters['destinationGrade']);
          }

          foreach ($destinationClassrooms as $destinationClassroom) {

            $levels = $destinationClassroom->getLevels();
              foreach ($levels as $level) {

                $ppo->destinationAssignments[$level->id_n][$destinationClassroom->id] = array();
                $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
                $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
              }
          }
        }
        } else {

          if (isset ($ppo->filters['destinationLevel']) && !is_null ($ppo->filters['destinationLevel'])) {

          if (_currentUser ()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|classroom|create@gestionautonome')) {

            $destinationClassrooms = $classroomDAO->getBySchoolAndLevel ($ppo->filters['destinationSchool'], $ppo->filters['destinationLevel'], $ppo->filters['destinationGrade']);
          } else {

            $destinationClassrooms = $classroomDAO->findBySchoolIdAndUserGroups ($ppo->filters['destinationSchool'], $groups['gestionautonome|iconitogrouphandler'], $ppo->filters['destinationGrade'], $ppo->filters['destinationLevel']);
          }
        } else {

          if (_currentUser ()->testCredential ('module:school|'.$ppo->filters['destinationSchool'].'|classroom|create@gestionautonome')) {

            $destinationClassrooms = $classroomDAO->getBySchool ($ppo->filters['destinationSchool'], $ppo->filters['destinationGrade']);
          } else {

            $destinationClassrooms = $classroomDAO->findBySchoolIdAndUserGroups ($ppo->filters['destinationSchool'], $groups['gestionautonome|iconitogrouphandler'], $ppo->filters['destinationGrade']);
          }
        }

        foreach ($destinationClassrooms as $destinationClassroom) {

          $ppo->destinationAssignments[''][$destinationClassroom->id] = array();
            $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
        }
        }
      } else {

        // Récupération de la classe sélectionnée par les filtres
        $destinationClassroom = $classroomDAO->get ($ppo->filters['destinationClassroom']);
        if ($ppo->filters['originUserType'] == 'USER_ELE') {

        if (isset ($ppo->filters['destinationLevel']) && !is_null ($ppo->filters['destinationLevel'])) {

          $classroomLevel = _ioDAO('kernel|kernel_bu_classe_niveau')->get ($ppo->filters['destinationLevel']);

          $ppo->destinationAssignments[$classroomLevel->id_n][$destinationClassroom->id] = array();
          $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
          $ppo->classroomLevels[$classroomLevel->id_n] = $classroomLevel->niveau_court;
        } else {

          $levels = $destinationClassroom->getLevels();
          foreach ($levels as $level) {

            $ppo->destinationAssignments[$level->id_n][$destinationClassroom->id] = array();
            $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
            $ppo->classroomLevels[$level->id_n] = $level->niveau_court;
          }
        }
      } else {

          $ppo->destinationAssignments[''][$destinationClassroom->id] = array();
          $ppo->classrooms[$destinationClassroom->id] = $destinationClassroom->nom;
        }
      }

      // Ajout des affectations au tableau des affectations de destination destiné à l'affichage
      foreach ($destinationAssignments as $destinationAssignment) {

        $ppo->destinationAssignments[$destinationAssignment->id_niveau][$destinationAssignment->id_classe][] = $destinationAssignment;
        $ppo->classrooms[$destinationAssignment->id_classe] = $destinationAssignment->nom_classe;
        $ppo->classroomLevels[$destinationAssignment->id_niveau] = $destinationAssignment->nom_niveau;
      }

    $toReturn = $this->_usePPO ($ppo, '_manage_assignments.tpl');
  }
}