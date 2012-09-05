<?php
/**
 * Demo - Base de données
 *
 * @package	Iconito
 * @subpackage	Sysutils
 * @version   $Id: demo_db.class.php,v 1.2 2009-04-01 13:10:00 cbeyer Exp $
 * @author	Christophe Beyer <cbeyer@cap-tic.fr>
 */


class Demo_DB
{
    public function db_connect ()
    {
        global $params, $_conn;
        switch ($params['driver']) {
            case 'mysql' :
            case 'pdo_mysql' :
                $_conn = mysql_connect($params['host'], $params['user'], $params['password']) or die ("Erreur : [".mysql_errno()."] ".mysql_error());
                mysql_select_db($params['dataBase'],$_conn) or die ("Erreur : [".mysql_errno()."] ".mysql_error());
                break;
        }
    }

    public function db_close ()
    {
        global $params, $_conn;
        switch ($params['driver']) {
            case 'mysql' :
            case 'pdo_mysql' :
                mysql_close($_conn);
                break;
        }
    }


    public function run_query ($query)
    {
        global $params, $_conn;
        switch ($params['driver']) {
            case 'mysql' :
            case 'pdo_mysql' :
                $res = mysql_query ($query, $_conn) or die ("Erreur $_conn (requête ".htmlentities($query).") : [".mysql_errno()."] ".mysql_error());
                return $res;
                break;
        }
    }


    public function move_next ($res)
    {
        global $params;
        switch ($params['driver']) {
            case 'mysql' :
            case 'pdo_mysql' :
                return mysql_fetch_array($res, MYSQL_ASSOC);
                break;
        }
    }


    public function extract_db_infos ()
    {
        global $params;

        /*
        $file = COPIX_VAR_PATH.'config/profils.copixdb.xml';
        if (file_exists($file)) {
            $params = array ('driver'=>'', 'dataBase'=>'', 'host'=>'', 'user'=>'', 'password'=>'');
            if ($handle = fopen($file, "r")) {
                while (!feof($handle)) {
                    $buffer = fgets($handle, 4096);
                    if (ereg("^[[:space:]]*([a-zA-Z]+)=\"([^\"]+)\"", $buffer, $regs)) {
                        //print_r($regs);
                        switch ($regs[1]) {
                            case 'driver' :
                            case 'dataBase' :
                            case 'host' :
                            case 'user' :
                            case 'password' :
                                $params[$regs[1]] = $regs[2];
                        }
                    }
                }
                //print_r($params);
                fclose($handle);
                if ($params['driver'] == 'mysql') {
                    if ($params['dataBase'] && $params['host'] && $params['user']) {
                    } else
                    die ("Les champs dataBase, host et user doivent être complétés");
                } else
                die ("Mise à jour hors MySql non implémentée");
            } else
            die ("Impossible d'ouvrir le fichier ".$file."");
        } else
        die ("Fichier ".$file." introuvable");
        */

        $file = COPIX_VAR_PATH.'config/db_profiles.conf.php';
        include($file);

        if( isset( $_db_profiles[$_db_default_profile] ) ) {

            // $_db_profiles[$_db_default_profile]['connectionString']

            $params = array (
                'driver'=>$_db_profiles[$_db_default_profile]['driver'],
                // 'dataBase'=>$_db_profiles[$_db_default_profile]['driver'],
                // 'host'=>$_db_profiles[$_db_default_profile]['driver'],
                'user'=>$_db_profiles[$_db_default_profile]['user'],
                'password'=>$_db_profiles[$_db_default_profile]['password']
            );

            $connectionString_data = split(';',$_db_profiles[$_db_default_profile]['connectionString']);
            foreach( $connectionString_data AS $connectionString_item ) {
                list($a,$b) = split('=',$connectionString_item);
                if( $a=='dbname') $params['dataBase'] = $b;
                if( $a=='host') $params['host'] = $b;
            }

        } else
        die ("Fichier ".$file." non conforme");

        // die( "<pre>".print_r($params,true)."</pre>" );
    }

}

