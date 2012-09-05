<?php

/**
 * Surcharge de la DAO Kernel_tree_cla
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAORecordKernel_tree_cla
{
    /**
     * Les niveaux d'une classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/11/19
     * @return array Liste des niveaux
     */
    public function getNiveaux ()
    {
        $sql = "SELECT NIV.* FROM kernel_bu_classe_niveau NIV, kernel_bu_ecole_classe_niveau CLA2NIV WHERE CLA2NIV.niveau=NIV.id_n AND CLA2NIV.classe=".$this->cla_id." ORDER BY NIV.id_n";
       $list = _doQuery ($sql);
        return $list;
    }

}