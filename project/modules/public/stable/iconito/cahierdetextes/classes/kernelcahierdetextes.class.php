<?php

/**
 * @package    Iconito
 * @subpackage Cahierdetextes
 * @author     Jérémy FOURNAISE
 */
class KernelCahierDeTextes
{
    /*
     * Crée un cahier de textes
     * Renvoie son ID ou NULL si erreur
     */

    function create()
    {

        $return = null;

        $dao = _dao("cahierdetextes|cahierdetextes");
        $new = _record("cahierdetextes|cahierdetextes");

        $dao->insert($new);

        if ( $new->id !== null ) {

            $return = $new->id;
        }

        return $return;
    }

    function getStatsRoot()
    {
        $res = array();

        /*
         * Nombre de cahier de textes
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_cahierdetextes';
        $a = _doQuery($sql);
        $res['nbCahierDeTextes'] = array('name' => CopixI18N::get('cahierdetextes|cahierdetextes.stats.nbCahierDeTextes', array($a[0]->nb)));

        /*
         * Nombre de domaines
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_cahierdetextes_domaine';
        $a = _doQuery($sql);
        $res['nbDomaines'] = array('name' => CopixI18N::get('cahierdetextes|cahierdetextes.stats.nbDomaines', array($a[0]->nb)));

        /*
         * Nombre de mémos
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_cahierdetextes_memo';
        $a = _doQuery($sql);
        $res['nbMemo'] = array('name' => CopixI18N::get('cahierdetextes|cahierdetextes.stats.nbMemos', array($a[0]->nb)));

        /*
         * Nombre de travail en classe
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_cahierdetextes_travail
            WHERE a_faire = 0';
        $a = _doQuery($sql);
        $res['nbTravailEnClasse'] = array('name' => CopixI18N::get('cahierdetextes|cahierdetextes.stats.nbTravailEnClasse', array($a[0]->nb)));

        /*
         * Nombre de travail à la maison
         */
        $sql = '
            SELECT COUNT(id) AS nb 
            FROM module_cahierdetextes_travail
            WHERE a_faire = 1';
        $a = _doQuery($sql);
        $res['nbTravailALaMaison'] = array('name' => CopixI18N::get('cahierdetextes|cahierdetextes.stats.nbTravailALaMaison', array($a[0]->nb)));

        return $res;
    }

}

