<?php

/**
 * Surcharge de la DAO Kernel_tree_claniv
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_tree_claniv
{
    /**
     * Retourne les niveaux d'une classe
     *
     * @param int $idClasse Identifiant d'une classe
     * @return array
     */
    public function getByClasse ($idClasse)
    {
        $sql = 'SELECT * FROM kernel_bu_ecole_classe_niveau WHERE classe = :idClasse';

    $results = _doQuery ($sql, array (':idClasse'  => $idClasse));

    return $results;
    }

}