<?php

/**
 * Fonctions relatives au kernel et au module Minimail
 *
 * @package Iconito
 * @subpackage Minimail
 */
class KernelMinimail
{
    /**
     * Statistiques du module minimail
     *
     * Renvoie des éléments chiffrés relatifs aux minimails et dédiés à un utilisateur système : nombre de messages...
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/20
     * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbMessages"] ["nbMessages24h"]
     */
    public function getStatsRoot ()
    {
        $res = array();
        $sql = 'SELECT MAX(id) AS nb FROM module_minimail_from';
        $a = _doQuery($sql);
        $res['nbMessages'] = array ('name'=>CopixI18N::get ('minimail|minimail.stats.nbMessages', array(1=>$a[0]->nb)));
        $sql = 'SELECT COUNT(id) AS nb FROM module_minimail_from WHERE UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(date_send)<=60*60*24';
        $a = _doQuery($sql);
        $res['nbMessages24h'] = array ('name'=>CopixI18N::get ('minimail|minimail.stats.nbMessages24h', array(1=>$a[0]->nb)));
        return $res;
    }


}

