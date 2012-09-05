<?php
/**
 * Fonctions relatives au kernel et au module VisioScopia
 *
 * @package Iconito
 * @subpackage	VisioScopia
 */

class KernelVisioScopia
{
    /**
     * Création d'une malle
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/06
     * @param array $infos (option) informations permettant d'initialiser la malle. Index: title, node_type, node_id
     * @return integer l'Id de la malle créée ou NULL si erreur
     */
    public function create ($infos=array())
    {
        $return = NULL;
        $dao = _dao("module_visioscopia");
        $new = _record("module_visioscopia");
        $new->date_creation = date("Y-m-d H:i:s");
        $dao->insert ($new);
        return $new->id;

    }

    /**
     * Suppression d'une malle
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/09
     * @param integer $id Id de la malle
     * @return boolean true si la suppression s'est bien passée, false sinon
     */
    public function delete ($id)
    {
        $dao = _dao("module_visioscopia");
        $dao->delete($id);

        return(true);
    }

    /**
     * Statistiques d'une malle
     *
     * Renvoie des éléments chiffrés relatifs à une malle : taille occupée (format "humain"), nombre de dossiers, nombre de fichiers
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbFiles"] ["nbFolders"] ["size"]
     */
    public function getStats ($malle)
    {
    }




}

