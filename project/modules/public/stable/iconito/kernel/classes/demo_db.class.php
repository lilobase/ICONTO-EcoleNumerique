<?php
/**
 * Demo - Base de données
 *
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: demo_db.class.php,v 1.2 2009-04-01 13:10:00 cbeyer Exp $
 * @author	Christophe Beyer <cbeyer@cap-tic.fr>
 */


class Demo_DB {

  
  function db_connect () {
  	global $params, $_conn;
  	switch ($params['driver']) {
  		case 'mysql' :
  			$_conn = mysql_connect($params['host'], $params['user'], $params['password']) or die ("Erreur : [".mysql_errno()."] ".mysql_error());
  			mysql_select_db($params['dataBase'],$_conn) or die ("Erreur : [".mysql_errno()."] ".mysql_error());
  			break;
  	}
  }
  
  function db_close () {
  	global $params, $_conn;
  	switch ($params['driver']) {
  		case 'mysql' :
  			mysql_close($_conn);
  			break;
  	}
  }
  
  
  function run_query ($query) {
  	global $params, $_conn;
  	switch ($params['driver']) {
  		case 'mysql' :
  			$res = mysql_query ($query, $_conn) or die ("Erreur $_conn (requête ".htmlentities($query).") : [".mysql_errno()."] ".mysql_error());
  			return $res;
  			break;
  	}
  }
  
  
  function move_next ($res) {
  	global $params;
  	switch ($params['driver']) {
  		case 'mysql' :
  			return mysql_fetch_array($res, MYSQL_ASSOC);
  			break;
  	}
  }
  
  
  function extract_db_infos () {
  	global $params;
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
  }

}

?>
