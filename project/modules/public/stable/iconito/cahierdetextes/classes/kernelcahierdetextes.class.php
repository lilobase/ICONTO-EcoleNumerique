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

    public function create()
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

    public function getStatsRoot()
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

    public function getNotifications(&$module, &$lastvisit)
    {
        $module->notification_number = 0;
        $module->notification_message = "";

        // echo "<pre>"; print_r($module); die();

        // Le module CahierDetextes ne fonctionne actuellement que pour les classes.
        if( $module->node_type == 'BU_CLASSE' ) $id_eleve = _currentUser()->getExtra('id');
        elseif( $module->node_type == 'USER_ELE' ) $id_eleve = $module->node_id;
        else return true;

        // Les notifications sont pour les élèves uniquement.
        if( _currentUser()->getExtra("type") == 'USER_ENS' ) return true;

        $news_travaux = _doQuery('SELECT *
            FROM module_cahierdetextes_travail2eleve T2E
            JOIN module_cahierdetextes_travail T
                ON T2E.module_cahierdetextes_travail_id=T.id
            WHERE T2E.kernel_bu_eleve_idEleve=:id_eleve
                AND T.supprime=0
                AND T.timestamp>:lastvisit
                AND T.a_faire=1
            ', array(
                ':id_eleve'=>_currentUser()->getExtra('id'),
                ':lastvisit'=>$lastvisit->date
            ));

        $news_memos = _doQuery('SELECT *
            FROM module_cahierdetextes_memo2eleve M2E
            JOIN module_cahierdetextes_memo M
                ON M2E.module_cahierdetextes_memo_id=M.id
            WHERE M2E.kernel_bu_eleve_idEleve=:id_eleve
                AND M.supprime=0
                AND M.timestamp>:lastvisit
            ', array(
                ':id_eleve'=>_currentUser()->getExtra('id'),
                ':lastvisit'=>$lastvisit->date
            ));

        $module->notification_number = count($news_travaux)+count($news_memos);
        $module->notification_message = '';
        if(count($news_travaux)) $module->notification_message.= (count($news_travaux)>1?CopixI18N::get('cahierdetextes|cahierdetextes.notification.travaux_n', array(count($news_travaux))):CopixI18N::get('cahierdetextes|cahierdetextes.notification.travaux_1', array(count($news_travaux))));
        if(count($news_travaux)&&count($news_memos)) $module->notification_message.=' et ';
        if(count($news_memos  )) $module->notification_message.= (count($news_memos  )>1?CopixI18N::get('cahierdetextes|cahierdetextes.notification.memos_n'  , array(count($news_memos  ))):CopixI18N::get('cahierdetextes|cahierdetextes.notification.memos_1'  , array(count($news_memos  ))));
        $module->notification_message = ucfirst($module->notification_message);
        return true;
    }


}

