<?php
ob_start();
session_start();

$version = trim(file_get_contents('version.txt'));

$version_txt = ($version) ? $version : 'inconnue';


define( '_MAIN_TITLE', "Mise &agrave; jour de la base de donn&eacute;es d'<a href=\"../\">ICONITO - Ecole Num&eacute;rique</a> (version ".$version_txt.")" );


require_once ("install_check.class.php");
require_once ("install_design.class.php");
require_once ('../../utils/copix/copix.inc.php');
require_once ('../../project/project.inc.php');
require_once ("../../project/modules/public/stable/iconito/sysutils/classes/demo_tools.class.php");


define('_LOGO_GOOD', '<img src="images/accept.png" align="baseline" />&nbsp;&nbsp;');
define('_LOGO_WARNING', '<img src="images/error.png" align="baseline" />&nbsp;&nbsp;');
define('_LOGO_ERROR', '<img src="images/cancel.png" align="baseline" />&nbsp;&nbsp;');
define('_LOGO_INFO', '<img src="images/process_accept.png" align="baseline" />&nbsp;&nbsp;' );

$Demo_Tools = new Demo_Tools();

display_menu();
display_title();

require (COPIX_VAR_PATH . 'config/db_profiles.conf.php');

$dbOpt = $_db_profiles[$_db_default_profile];

$host = 'localhost';
$database = '';
if (preg_match('/dbname=([0-9A-Za-z-_\.]+);?(host=)?([0-9A-Za-z-_\.]+)?/', $dbOpt['connectionString'], $regs)) {
  if (isset($regs[3])) {
    $host = $regs[3];
  }
  $database = $regs[1];
  //echo '<pre>'; print_r($regs); echo '</pre>';
}

$_SESSION['install_iconito']['host'] = $host;
$_SESSION['install_iconito']['database'] = $database;
$_SESSION['install_iconito']['login'] = $dbOpt['user'];
$_SESSION['install_iconito']['password'] = $dbOpt['password'];

//echo '<pre>'; print_r($_SESSION['install_iconito']); echo '</pre>';

$connexion = check_mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']);


if (!$connexion) {
  display_message(_LOGO_ERROR . "Impossible de se connecter &agrave; la base de donn&eacute;es");
} else {
  display_message(_LOGO_GOOD . "Connexion &agrave; la base de donn&eacute;es &eacute;tablie");

  mysql_select_db($_SESSION['install_iconito']['database']);


  //do_mysql_importdump($filename, $connexion);
  $version = 0;
  $sql = "SELECT * FROM kernel_version_bdd ORDER BY version DESC, date DESC LIMIT 1";
  $list = do_mysql_runquery_list($sql, $connexion);
  if ($list) {
    while ($r = do_mysql_move_next($list)) {
      //print_r($r);
      $version = $r['version'];
    }
  }

  display_message(_LOGO_INFO . "Version d&eacute;tect&eacute;e : <b>$version</b>");

  $upgrades = get_upgrades ($version);
  if ($upgrades) {

    $upgradesInv = $upgrades;
    $last = array_pop($upgradesInv);
    $lastVersion = $last['numero'];
    
    display_message(_LOGO_INFO . "Version &agrave; installer : <b>$lastVersion</b>");

    if (isset($_GET['go']) && $_GET['go']) {
      echo '<hr />';

      //print_r($_SESSION);

      $stop = false;
      
      foreach ($upgrades as $upgrade) {
        //print_r($upgrade);
        if ($stop)
          continue;
        $run = do_mysql_importdump('../../instal/upgrade_bdd/'.$upgrade['file'], $connexion);
        if ($run) {
          display_message(_LOGO_GOOD . "Passage &agrave; la version ".$upgrade['numero']." r&eacute;ussi");
          
          $ip = $_SERVER['REMOTE_ADDR'];
          $sqlVersion = "INSERT INTO kernel_version_bdd SET date=DATE_FORMAT(NOW(),'%Y%m%d%H%i%s'), version=".$upgrade['numero'].", ip='".$ip."'";
          do_mysql_runquery ($sqlVersion, $connexion);
        }
        else {
          display_message(_LOGO_ERROR . "Probl&egrave;me de passage &agrave; la version ".$upgrade['numero']."");
          $stop = true;
        }
      }

      $folder = COPIX_TEMP_PATH.'cache/php/dao';
      $dirempty = $Demo_Tools->dirempty ($folder);
      if ($dirempty)
          display_message(_LOGO_GOOD . "Fichiers du cache PHP/DAO supprim&eacute;s");
      else
          display_message(_LOGO_ERROR . "Probl&egrave;me de suppression des fichiers du cache PHP/DAO");

      display_message(_LOGO_GOOD . "Mise &agrave; jour termin&eacute;e !");
      display_link("Cliquez ici pour rev&eacute;rifier si une mise &agrave; jour est disponible", 'upgrade_bdd.php');


    } else {
      display_link("Cliquez ici pour proc&eacute;der &agrave; la mise &agrave; jour", 'upgrade_bdd.php?go=1');
    }


    

  } else {

    display_message(_LOGO_WARNING . "Votre base de donn&eacute;es est &agrave; jour !");
  }
  

  


  
  
  
  
}




global $display_header;
if ($display_header)
  echo "</div>";



/**
 * Liste des fichiers a passer par rapport a la version courante
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2011/01/26
 * @param integer $iVersion Numero de version courante
 * @return 
 */
function get_upgrades ($iVersion) {

  $files = array();
  
  $dir = "../../instal/upgrade_bdd";
  if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
      while (($file = readdir($dh)) !== false) {
        if (preg_match('/^([0-9]{3}).sql$/', $file, $regs)) {
          $numero = $regs[1]*1;
          //echo "fichier : $file : num : $num";
          $files[$numero] = array (
            'numero' => $numero,
            'file' => $file,
          );
        }
      }
      closedir($dh);
      ksort($files);
      //print_r($files);
    }
  }

  foreach ($files as $k=>$r) {
    if ($iVersion >= $k) {
      unset ($files[$k]);
    }
  }

  //print_r($files);

  return $files;

}







?>