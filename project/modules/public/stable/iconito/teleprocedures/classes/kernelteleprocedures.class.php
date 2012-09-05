<?php

/**
 * Fonctions relatives au kernel et au module Teleprocedures
 *
 * @package Iconito
 * @subpackage	Liste
 */
class KernelTeleprocedures
{
    /**
     * Creation du module
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/23
     * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
     * @return integer Id cree ou NULL si erreur
     */
    public function create ($infos=array())
    {
        $dao = _dao("teleprocedures|teleprocedure");
        $new = _record("teleprocedures|teleprocedure");
        $new->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
        $new->date_creation = date("Y-m-d H:i:s");
        $dao->insert ($new);
        return ($new->id!==NULL) ? $new->id : NULL;
    }

    /**
     * Suppression définitive
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/23
     * @param integer $id Id du module
     * @return boolean true si la suppression s'est bien passée, false sinon
     */
    public function delete ($id)
    {
        $dao = _dao("teleprocedures|teleprocedure");
        $r = $dao->get($id);
        $res = false;
        if ($r) {
            // todo : supprimer en-dessous
            $dao->delete ($id);
            $res = true;
        }
        return $res;
    }




}

