<?php

/**
 * Fonctions diverses du module Stats
 *
 * @package Iconito
 * @subpackage Stats
 * @version $Id: statsservice.class.php,v 1.2 2007-06-15 15:05:48 cbeyer Exp $
 * @author Christophe Beyer
 * @copyright 2007 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */


class StatsService
{
    /**
     * Met à jour les données de la base. Complete les parents pour les modules dont ils ne sont pas renseignes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/06/12
     * @return mixed TODO
     */
    public function updateCron ()
    {
        // On prend les modules orphelins

        $sql = "SELECT DISTINCT(module_type) AS module_type, module_id FROM module_stats_logs WHERE (parent_type IS NULL OR parent_id IS NULL) AND module_type IN ('MOD_GROUPE')";
        //Kernel::deb ($sql);
        $list = _doQuery($sql);
        $i=0;
        foreach ($list as $e) {

            if ($e->module_type == 'MOD_GROUPE')
                $mod = 'CLUB';
            else
                $mod = $e->module_type;

            $parent = Kernel::getNodeParents ($mod, $e->module_id);
            if ($parent) {
                //Kernel::deb ($sql);
                $sql = "UPDATE module_stats_logs SET parent_type=:parent_type, parent_id=:parent_id WHERE module_type=:module_type AND module_id=:module_id";
                _doQuery ($sql, array (
                    ':parent_type' => $parent[0]['type'],
                    ':parent_id' => $parent[0]['id'],
                    ':module_type' => $e->module_type,
                    ':module_id' => $e->module_id,
                ));
            }
            //print_r($parent);
            $i++;
        }

        Kernel::deb ("$i mises à jour");

    }


}

