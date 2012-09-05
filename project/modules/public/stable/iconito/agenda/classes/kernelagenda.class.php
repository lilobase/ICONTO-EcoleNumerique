<?php

/**
 * Fonctions relatives au kernel et au module Agenda
 *
 * @package Iconito
 * @subpackage Agenda
 */

_classInclude('agenda|agendatype');

class KernelAgenda
{
    /**
     * Création d'un agenda
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/08/24
     * @param array $infos (option) informations permettant d'initialiser le blog. Index: title, node_type, node_id
     * @return integer l'Id de l'agenda créé ou NULL si erreur
     */
    public function create ($infos=array())
    {
        $daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');

        $res = null;

        $agenda = _record ('agenda|agenda');
        if ($infos['title'])
            $agenda->title_agenda = $infos['title'];
        else
            //$agenda->title_agenda = CopixI18N::get ('agenda|agenda.default.title');
            $agenda->title_agenda = "Agenda";
        $agenda->desc_agenda = $agenda->title_agenda;
        $agenda->type_agenda = AgendaType::getAgendaTypeForNode ($infos['node_type'], $infos['node_id']);

        $daoAgenda->insert($agenda);

        return ($agenda->id_agenda!==NULL) ? $agenda->id_agenda : NULL;
    }

    /**
     * Suppression d'un agenda
     *
     * Supprime un agenda, ses évènements, leçons etc.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/08/24
     * @param integer $id Id de l'agenda à supprimer
     * @return boolean true si la suppression s'est bien passée, false sinon
     */
    public function delete ($id_agenda)
    {
    // TODO
    Kernel::unregisterModule("MOD_AGENDA", $id_agenda);
    }

    /**
     * Statistiques d'un agenda
     *
     * Renvoie des éléments chiffrés relatifs à un agenda : nombre d'évènements...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/08/24
     * @param integer $id_agenda Id de l'agenda
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbEvenements"]
     */
    public function getStats ($id_agenda)
    {
        $res = array();
        $dao = _dao("agenda|agenda");
        $infos = $dao->getNbsEvenementsInAgenda($id_agenda);
        $res['nbEvenements'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbEvenements', array($infos[0]->nbEvenements)));
        return $res;

    }

    /**
     * Statistiques du module agenda
     *
     * Renvoie des éléments chiffrés relatifs aux agendas et dédiés à un utilisateur système : nombre d'évènements...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/20
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbAgendas"] ["nbEvenements"] ["nbLecons"]
     */
    public function getStatsRoot ()
    {
        $res = array();
        $sql = 'SELECT COUNT(A.id_agenda) AS nb FROM module_agenda_agenda A';
        $a = _doQuery($sql);
        $res['nbAgendas'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbAgendas', array($a[0]->nb)));
        $sql = 'SELECT COUNT(E.id_event) AS nb FROM module_agenda_event E';
        $a = _doQuery($sql);
        $res['nbEvenements'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbEvenements', array($a[0]->nb)));
        $sql = 'SELECT COUNT(L.id_lecon) AS nb FROM module_agenda_lecon L';
        $a = _doQuery($sql);
        $res['nbLecons'] = array ('name'=>CopixI18N::get ('agenda|agenda.stats.nbLecons', array($a[0]->nb)));
        return $res;
    }




}

