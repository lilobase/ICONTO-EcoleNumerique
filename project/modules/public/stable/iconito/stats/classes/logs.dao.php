<?php

/**
 * @package Iconito
 * @subpackage Stats
 * @version $Id: logs.dao.class.php,v 1.1 2007-06-15 15:05:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


class DAOLogs
{
    /**
    * Renvoie les stats sur un module (toutes les actions)
    *
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2007/06/14
    * @param array $params Tous les paramètres nécessaires (module_type, module_id, date_debut, date_fin...)
    * @return array $stats Statistiques
  */
    public function getStatsModule ($params)
    {
        //print_r($params);
        $stats = array();
        $query = "SELECT COUNT(id) AS nb, action FROM module_stats_logs WHERE";
        $query .= " module_type='".$params['module_type']."'";
        $query .= " AND module_id=".$params['module_id']."";
        if ($params['date_debut'])
            $query .= " AND DATE_FORMAT(date, '%Y-%m-%d')>='".$params['date_debut']."'";
        if ($params['date_fin'])
            $query .= " AND DATE_FORMAT(date, '%Y-%m-%d')<='".$params['date_fin']."'";
        $query .= " GROUP BY action ORDER BY nb DESC";
        //Kernel::deb($query);
        $result = _doQuery ($query);
        foreach ($result as $r)
            $stats[] = $r;
        return $stats;
    }

    /**
    * Renvoie les stats sur une action module (pour trouver les articles les plus cliqués par ex)
    *
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2007/06/14
    * @param array $params Tous les paramètres nécessaires (module_type, module_id, action, date_debut, date_fin...)
    * @return array $stats Statistiques
  */
    public function getStatsModuleAction ($params)
    {
        //print_r($params);
        $stats = array();
        $query = "SELECT COUNT(id) AS nb, objet_a FROM module_stats_logs WHERE";
        $query .= " module_type='".$params['module_type']."'";
        $query .= " AND module_id=".$params['module_id']."";
        $query .= " AND action='".$params['action']."'";
        if ($params['date_debut'])
            $query .= " AND DATE_FORMAT(date, '%Y-%m-%d')>='".$params['date_debut']."'";
        if ($params['date_fin'])
            $query .= " AND DATE_FORMAT(date, '%Y-%m-%d')<='".$params['date_fin']."'";
        $query .= " GROUP BY objet_a ORDER BY nb DESC";
        //Kernel::deb($query);
        $result = _doQuery ($query);
    foreach ($result as $r)
            $stats[] = $r;
        return $stats;
    }






}

