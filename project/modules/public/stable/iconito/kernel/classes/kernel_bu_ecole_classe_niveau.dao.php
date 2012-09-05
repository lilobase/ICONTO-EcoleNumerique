<?php

class DAORecordKernel_bu_ecole_classe_niveau
{
}

/**
 * Surcharge de la DAO Kernel_bu_ecole_classe_niveau
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ecole_classe_niveau
{
    /**
     * Retourne les associations classe-niveau pour une classe donnée
     *
     * @param int $classId Identifiant d'une classe
     *
     * @return CopixDAORecordIterator
     */
    public function getByClass ($classId)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('classe', '=', $classId);

        return $this->findBy ($criteria);
    }
}