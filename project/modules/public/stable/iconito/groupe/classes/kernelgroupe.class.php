<?php

/**
 * Fonctions relatives au kernel et au module Groupe
 *
 * @package Iconito
 * @subpackage	Groupe
 */
class KernelGroupe
{
    /**
     * Statistiques d'un groupe
     *
     * Renvoie des éléments chiffrés relatifs à un groupe : nombre de membres
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/17
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["Membres"]
     */
    public function getStats ($id_groupe)
    {
        //$kernelService = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
        $res = array();

    $members = $groupeService->getNbMembersInGroupe ($id_groupe);
        $res['nbMembers'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembers', array($members['inscrits'])));

        if ($members['waiting'])
            $res['nbMembersWaiting'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembersWaiting', array($members['waiting'])));

        return $res;
    }


    /**
     * Statistiques du module groupes de travail
     *
     * Renvoie des éléments chiffrés relatifs aux groupes de travail et dédiés à un utilisateur système : nombre de groupes, de modules...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/20
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbMessages"] ["nbMessages24h"]
     */
    public function getStatsRoot ()
    {
        $res = array();
        $sql = 'SELECT COUNT(id) AS nb FROM module_groupe_groupe';
        $a = _doQuery($sql);
        $res['nbGroupes'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbGroupes', array($a[0]->nb)));
        $sql = "SELECT COUNT(user_id) AS nb FROM kernel_link_user2node WHERE node_type='CLUB' AND droit>=".PROFILE_CCV_READ;
        $a = _doQuery($sql);
        $res['nbMembers'] = array ('name'=>CopixI18N::get ('groupe|groupe.stats.nbMembers', array($a[0]->nb)));
        return $res;
    }


}

