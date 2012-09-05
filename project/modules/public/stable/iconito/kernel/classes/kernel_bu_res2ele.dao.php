<?php

/**
 * Surcharge de la DAO Kernel_bu_res2ele
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_res2ele
{
    /**
     * Retourne l'affectation pour un responsable et un élève
     *
     * @param int $personId   Identifiant du responsable
     * @param int $studentId  Identifiant de l'élève
     *
     * @return DAORecord
     */
    public function getByPersonAndStudent ($personId, $studentId)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('res2ele_id_responsable', '=', $personId);
        $criteria->addCondition ('res2ele_id_beneficiaire', '=', $studentId);

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne l'affectation pour un responsable
     *
     * @param int $personId   Identifiant du responsable
     *
     * @return DAORecord
     */
    public function getByPerson ($personId)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('res2ele_id_responsable', '=', $personId);

        return $this->findBy ($criteria);
    }
}