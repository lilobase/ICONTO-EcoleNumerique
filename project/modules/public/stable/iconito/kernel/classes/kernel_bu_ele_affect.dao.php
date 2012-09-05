<?php

/**
 * Surcharge de la DAO Kernel_bu_ele_affect
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele_affect
{
    /**
     * Retourne l'affectation pour un élève et une classe
     *
     * @param int $studentId Identifiant d'un élève
     * @param int $classId   Identifiant d'une classe
     *
     * @return DAORecord
     */
    public function getByStudentAndClass ($studentId, $classId, $current = null)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('affect_eleve', '=', $studentId);
        $criteria->addCondition ('affect_classe', '=', $classId);
        if (!is_null ($current)) {

           $criteria->addCondition ('affect_current', '=', $current);
        }

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne les associations d'un élève
     *
     * @param int     $studentId  Identifiant d'un élève
     * @param string  $grade      Année scolaire
     *
     * @return CopixDAORecordIterator
     */
    public function getByStudent ($studentId, $grade = null)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('affect_eleve', '=', $studentId);

        if (!is_null ($grade)) {

          $criteria->addCondition ('affect_annee_scol', '=', $grade);
        }

        return $this->findBy ($criteria);
    }

    /**
     * Retourne l'association courante d'un élève
     *
     * @param int $studentId Identifiant d'un élève
     *
     * @return DAORecord
     */
    public function getCurrentAffectByStudent ($studentId)
    {
      $criteria = _daoSp ();
        $criteria->addCondition ('affect_eleve', '=', $studentId);
        $criteria->addCondition ('affect_current', '=', 1);

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
    * Vérifie si un élève à des affectations dans une école donnée
    *
    * @param int $studentId Identifiant d'un élève
    * @param int $schoolId  Identifiant de l'école
    *
    * return int
    */
    public function countCurrentAffectInSchool ($studentId, $schoolId)
    {
      $sql = 'SELECT count(kernel_bu_eleve_affectation.id) as count
              FROM `kernel_bu_eleve_affectation`
              INNER JOIN kernel_bu_ecole_classe ON (kernel_bu_ecole_classe.ecole = '.$schoolId.')
              WHERE kernel_bu_ecole_classe.id = kernel_bu_eleve_affectation.classe
              AND kernel_bu_eleve_affectation.eleve = '.$studentId.'
              AND kernel_bu_eleve_affectation.current=1';


    $results = _doQuery ($sql);

    return $results[0]->count;
    }

    /**
     * Retourne l'affectation pour un élève et une année scolaire
     *
     * @param int $studentId Identifiant d'un élève
     * @param int $gradeId   Identifiant de l'année scolaire
     *
     * @return DAORecord
     */
    public function getByStudentAndGrade ($studentId, $gradeId, $current = null)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('affect_eleve', '=', $studentId);
        $criteria->addCondition ('affect_annee_scol', '=', $gradeId);
        if (!is_null ($current)) {

           $criteria->addCondition ('affect_current', '=', $current);
        }

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

}
