<?php

/**
* @package    Iconito
* @subpackage Gestion Autonome
* @author     Jérémy FOURNAISE
*/

class GestionAutonomeService
{
  /**
   * Supprime l'affectation d'un élève au sein d'une classe
   *
   * @param int $studentId    Identifiant de l'élève
   * @param int $classroomID  Classe
   */
  public static function removeStudentAssignment ($studentId, $classroomId)
  {
    // Récupération de la classe
    $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');
    $classroom = $classroomDAO->get ($classroomId);

    // Ajout d'un enregistrement de radiation
    $studentAdmissionDAO = _ioDAO ('kernel|kernel_bu_eleve_admission');
    $studentAdmission = _record ('kernel|kernel_bu_eleve_admission');

    $studentAdmission->admission_eleve          = $studentId;
    $studentAdmission->admission_etablissement  = $classroom->ecole;
    $studentAdmission->admission_annee_scol     = $classroom->annee_scol;
    $studentAdmission->admission_id_niveau      = '';
    $studentAdmission->admission_etat_eleve     = 3;
    $studentAdmission->admission_date           = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->admission_date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
    $studentAdmission->admission_code_radiation = '';
    $studentAdmission->admission_previsionnel   = '';

    $studentAdmissionDAO->insert ($studentAdmission);

    // Récupération de l'affectation de l'élève à la classe pour passage du flag current à 0
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
    if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $classroom->id, 1)) {

      $studentAssignment->affect_current = 0;
      $studentAssignmentDAO->update ($studentAssignment);
    }

    // Si l'utilisateur n'a pas d'autres affectations dans cette école : passage du flag inscr_current à 0
    if (!$studentAssignmentDAO->countCurrentAffectInSchool ($studentId, $classroom->ecole) > 0) {

      $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');
      $studentRegistration = $studentRegistrationDAO->getByStudentAndSchool ($studentId, $classroom->ecole);

      $studentRegistrationDAO->updateCurrentFlag ($studentRegistration->numero, 0);
    }
  }

  /**
   * Supprime les affectations d'un élève
   *
   * @param int   $studentId  Identifiant de l'élève
   * @param int   $grade      Année scolaire
   */
  public static function removeStudentAssignments ($studentId, $grade = null)
  {
    $studentAssignmentDAO = _ioDAO ('kernel|kernel_bu_ele_affect');
    $classroomDAO = _ioDAO ('kernel|kernel_bu_ecole_classe');

    // Récupération des affectations de l'élève pour passage du flag current à 0
    $studentAssignments = $studentAssignmentDAO->getByStudent ($studentId, $grade);
    foreach ($studentAssignments as $studentAssignment) {

      self::removeStudentAssignment ($studentId, $studentAssignment->affect_classe);
    }
  }

  /**
   * Supprime l'affectation d'une personne
   *
   * @param int         $personId      Identifiant de la personne
   * @param int         $reference     Identifiant du node de l'affectation
   * @param string      $type_ref      Type du node de l'affectation
   */
  public static function removePersonnelAssignment ($personId, $reference, $type_ref)
  {
    $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');

    if ($personEntity = $personEntityDAO->getByIdReferenceAndType ($personId, $reference, $type_ref)) {

      // Si on se trouve sur une ecole et que la personne (directeur) a une affectation dans une des classes
      if ($type_ref == 'ECOLE'
        && $personEntityDAO->hasTeacherRoleInSchool ($personId, $reference, true)
        && $personEntity->pers_entite_role == DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL) {

        // Mise à jour du rôle : directeur -> enseignant
        $personEntityDAO->updateRole ($personId, $reference, $type_ref, DAOKernel_bu_personnel_entite::ROLE_TEACHER);

        return 'principalRemoved';
      } else {

        // Suppression de l'affectation
        $personEntityDAO->delete ($personId, $reference, $type_ref);

        return 'personnelRemoved';
      }
    }
  }

  /**
   * Ajoute une affectation pour un élève (inscription / admission et affectation)
   * - sans suppression des autres affectations -
   *
   * @param int                         $studentId      Identifiant de l'élève
   * @param DAOKernel_bu_ecole_classe   $classroom      Classe
   * @param int                         $level          Niveau dans la classe
   */
  public static function addStudentAssignment ($studentId, $classroom, $level)
  {
    $studentAssignmentDAO   = _ioDAO ('kernel|kernel_bu_ele_affect');
    $studentAdmissionDAO    = _ioDAO ('kernel|kernel_bu_eleve_admission');
    $studentRegistrationDAO = _ioDAO ('kernel|kernel_bu_eleve_inscription');

    if (!$studentRegistration = $studentRegistrationDAO->getByStudentAndSchool ($studentId, $classroom->ecole)) {

      $studentRegistration = _record ('kernel|kernel_bu_eleve_inscription');

      $studentRegistration->eleve                   = $studentId;
      $studentRegistration->annee_scol              = $classroom->annee_scol;
      $studentRegistration->date_preinscript        = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentRegistration->date_effet_preinscript  = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentRegistration->date_inscript           = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentRegistration->date_effet_inscript     = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentRegistration->etablissement           = $classroom->ecole;
      $studentRegistration->etablissement_refus     = 0;
      $studentRegistration->id_niveau               = $level;
      $studentRegistration->id_typ_cla              = 11;
      $studentRegistration->vaccins_aj              = 0;
      $studentRegistration->attente                 = 0;
      $studentRegistration->derogation_dem          = 0;
      $studentRegistration->temporaire              = 0;
      $studentRegistration->current_inscr           = 1;

      $studentRegistrationDAO->insert ($studentRegistration);
    }

    // Admission de l'élève dans l'école
    if (!$studentAdmission = $studentAdmissionDAO->getByStudentAndSchool ($studentId, $classroom->ecole, DAOKernel_bu_eleve_admission::STATE_NEW)) {

      $studentAdmission = _record ('kernel|kernel_bu_eleve_admission');

      $studentAdmission->admission_eleve          = $studentId;
      $studentAdmission->admission_etablissement  = $classroom->ecole;
      $studentAdmission->admission_annee_scol     = $classroom->annee_scol;
      $studentAdmission->admission_id_niveau      = $level;
      $studentAdmission->admission_etat_eleve     = DAOKernel_bu_eleve_admission::STATE_NEW;
      $studentAdmission->admission_date           = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentAdmission->admission_date_effet     = CopixDateTime::timestampToYYYYMMDD (time ());
      $studentAdmission->admission_code_radiation = '';
      $studentAdmission->admission_previsionnel   = '';

      $studentAdmissionDAO->insert ($studentAdmission);
    }

    if ($studentAssignment = $studentAssignmentDAO->getByStudentAndClass ($studentId, $classroom->id, 1)) {

      $studentAssignment->affect_current = 0;
      $studentAssignmentDAO->update ($studentAssignment);
    }

    // Affectation de l'élève dans la classe
    $studentAssignment = _record ('kernel|kernel_bu_ele_affect');

    $studentAssignment->affect_eleve           = $studentId;
    $studentAssignment->affect_annee_scol      = $classroom->annee_scol;
    $studentAssignment->affect_classe          = $classroom->id;
    $studentAssignment->affect_niveau          = $level;
    $studentAssignment->affect_current         = 1;
    $studentAssignment->affect_previsionnel_cl = 0;

    $studentAssignmentDAO->insert ($studentAssignment);
  }

  /**
   * Ajoute une affectation pour une personne dans une classe
   *
   * @param int                         $personId     Identifiant de la personne
   * @param DAOKernel_bu_ecole_classe   $classroom    Classe
   * @param int                         $role         Role de l'affectation
   */
  public function addPersonAssignmentOnClassroom ($personId, $classroom, $role)
  {
    $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');
    if (!$personEntityDAO->getByIdReferenceAndType ($personId, $classroom->id, 'CLASSE')) {

      // Création de l'association kernel_bu_personnel_entite
      $newPersonEntity = _record ('kernel|kernel_bu_personnel_entite');

      $newPersonEntity->pers_entite_id_per    = $personId;
      $newPersonEntity->pers_entite_reference = $classroom->id;
      $newPersonEntity->pers_entite_type_ref  = 'CLASSE';
      $newPersonEntity->pers_entite_role      = $role;

      $personEntityDAO->insert ($newPersonEntity);
    }

    if (!$personEntityDAO->getByIdReferenceAndType ($personId, $classroom->ecole, 'ECOLE')) {

      // Création de l'association kernel_bu_personnel_entite ecole (pour les enseignants)
      $newPersonEntity = _record ('kernel|kernel_bu_personnel_entite');

      $newPersonEntity->pers_entite_id_per    = $personId;
      $newPersonEntity->pers_entite_reference = $classroom->ecole;
      $newPersonEntity->pers_entite_type_ref  = 'ECOLE';
      $newPersonEntity->pers_entite_role      = $role;

      $personEntityDAO->insert ($newPersonEntity);
    }
  }
}