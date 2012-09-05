<?php

/**
 * Fonctions relatives au kernel et au module Contact
 *
 * @package Iconito
 * @subpackage	Forum
 */
class KernelContact
{
    /**
     * Création d'un module de contacts
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/08/25
   * @param array $infos (option) Infos sur le module. [title], [subtitle], [node_type], [node_id]
     * @return integer l'Id du module cree ou NULL si erreur
     */
    public function create ($infos=array())
    {
        $record = _record('contact|contacts');
        $newForum->titre = (isset($infos['title']) && $infos['title']) ? $infos['title'] : '';
        $newForum->date_creation = date("Y-m-d H:i:s");
        _dao('contact|contacts')->insert ($record);
        return ($record->id!==NULL) ? $record->id : NULL;
    }

    /**
     * Suppression d'un module de contacts
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/08/25
     * @param integer $id Id du module a supprimer
     * @return boolean true si la suppression s'est bien passée, false sinon
     */
    public function delete ($idContacts)
    {
        $res = false;
        if ($record = _dao('contact|contacts')->get($idContacts)) {
            _dao('contact|contacts')->delete ($idContacts);
            $res = true;
        }
    Kernel::unregisterModule("MOD_CONTACT", $idContacts);
        return $res;
    }

    /**
     * Statistiques d'un module de contacts
     *
     * Renvoie des éléments chiffrés relatifs à un module de contacts : nombre de messages...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/08/25
     * @param integer Id du module a analyser
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées.
     */
    public function getStats ($idContacts)
    {
        $res = array();
        return $res;
    }


    /**
     * Statistiques d'un module de contacts
     *
     * Renvoie des éléments chiffrés relatifs à un module de contacts : nombre de messages...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/08/25
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées.
     */
    public function getStatsRoot ()
    {
        $res = array();
        return $res;
    }


}

