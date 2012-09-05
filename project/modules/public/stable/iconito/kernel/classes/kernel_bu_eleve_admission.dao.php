<?php

/**
 * Surcharge de la DAO Kernel_bu_ele_admission
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_eleve_admission
{
  const STATE_NEW = 1;
  const STATE_CANCELED = 3;

    /**
     * Retourne l'admission pour un élève et une école
     *
     * @param int $studentId  Identifiant d'un élève
     * @param int $schoolId   Identifiant d'une école
     *
     * @return DAORecord
     */
    public function getByStudentAndSchool ($studentId, $schoolId, $state = null)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('admission_eleve', '=', $studentId);
        $criteria->addCondition ('admission_etablissement', '=', $schoolId);

        if (!is_null ($state)) {

          $criteria->addCondition ('admission_etat_eleve', '=', $state);
        }

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

}