<?php
ob_start();
require_once ("install_check.class.php");
require_once ("install_design.class.php");
require_once ('../../utils/copix/copix.inc.php');
require_once ('../../project/project.inc.php');
require_once ('../../project/modules/public/stable/iconito/sysutils/classes/demo_tools.class.php');

define( '_LOGO_GOOD', '<img src="images/accept.png" align="baseline" />&nbsp;&nbsp;' );
define( '_LOGO_WARNING', '<img src="images/error.png" align="baseline" />&nbsp;&nbsp;' );
define( '_LOGO_ERROR', '<img src="images/cancel.png" align="baseline" />&nbsp;&nbsp;' );

if( !isset($_GET['step']) ) $step=1;
else $step=0+$_GET['step'];

$titles = array(
    1 => "Bienvenue",
    2 => "Droits des fichiers",
    3 => "V&eacute;rification de PHP",
    4 => "Configuration Mysql",
    5 => "Choix de la base",
    6 => "Cr&eacute;ation des tables",
    7 => "Import des donn&eacute;es",
    8 => "Mot de passe",
    9 => "Configuration",
    10 => "Fini !",
);

if( file_exists(COPIX_LOG_PATH.'.installed') && file_exists('../../project/config/copix.conf.php') ) {
    // display_menu();
    display_title();
    display_message( "ICONITO Ecole Num&eacute;rique est d&eacute;j&agrave; install&eacute;. Pour y acc&eacute;der, <a href=\"..\">cliquez ici</a> !" );
    display_message( "Si vous souhaitez refaire une installation, vous devez supprimer manuellement le fichier \".installed\" qui se trouve dans \"temp/log\" et recharger cette page." );
  close_page ();
    ob_flush();
    die();
}


switch( $step ) {
    case 1:
        display_menu();
        display_title();
        display_message( "Vous souhaitez installer ICONITO Ecole Num&eacute;rique ? <a href=\"index.php?step=".($step+1)."\">Cliquez ici</a> !" );
        break;

    case 2:
        display_menu();
        display_title();
        $data = check_rights();
        if( ! $data['caninstall'] ) {
            display_message( _LOGO_ERROR."Les fichiers et r&eacute;pertoires suivants doivent &ecirc;tres accessible en &eacute;criture pour Apache :" );
            display_list_start();
            foreach( $data['errors'] AS $file ) display_list_item( $file );
            display_list_stop();
            display_link( "Corrigez et cliquez ici pour r&eacute;essayer", 'index.php?step='.($step) );
        } else {
            display_message( _LOGO_GOOD."Les droits sur les fichiers et r&eacute;pertoires sont corrects" );

      // On vide le cache, si jamais il y en a :
      $tools = new Demo_Tools();
      $folder = COPIX_TEMP_PATH.'cache';
          $tools->dirempty ($folder);

            display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
        }
        break;

    case 3:
        display_menu();
        display_title();
        $data = check_php();

        foreach( $data['errors'] AS $error ) {
            $msg = '';
            if( $error['level']=='error' ) $msg.=_LOGO_ERROR;
            elseif( $error['level']=='warning' ) $msg.=_LOGO_WARNING;
            elseif( $error['level']=='good' ) $msg.=_LOGO_GOOD;
            $msg.=$error['message'];

            display_message( $msg );
        }

        if( ! $data['caninstall'] ) {
            display_link( "Corrigez et cliquez ici pour r&eacute;essayer", 'index.php?step='.($step) );
        } else {
            display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
        }

        break;

    case 4:
        display_menu();
        display_title();
        session_start();

        $data = check_mysql_login();
        if( is_array($data) ) {
            $_SESSION['install_iconito'] = array();
            $_SESSION['install_iconito']['host'] = $_POST['host'];
            $_SESSION['install_iconito']['login'] = $_POST['login'];
            $_SESSION['install_iconito']['password'] = $_POST['password'];
            if( $data['errors'][0]['level']=='good' ) {
                display_message( _LOGO_GOOD.$data['errors'][0]['message']);
                display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
                break;
            } else {
                display_message( _LOGO_ERROR.$data['errors'][0]['message']);
            }
        } else {
            display_message( "Afin de configurer votre base de donn&eacute;es, merci de renseigner les informations de connexion." );
        }
?>
<form method="post">
<table border="0">
<tr><td align="right">Serveur :</td><td><input name="host" value="<?php if(isset($_SESSION['install_iconito']['host'])) echo $_SESSION['install_iconito']['host']; else echo "localhost"; ?>" /> Vous pouvez pr&eacute;ciser le port. Exemple: <i>serveur:1512</i></td></tr>
<tr><td align="right">Login :</td><td><input name="login" value="<?php if(isset($_SESSION['install_iconito']['login'])) echo $_SESSION['install_iconito']['login']; ?>" /></td></tr>
<tr><td align="right">Mot de passe :</td><td><input name="password" type="password" value="<?php if(isset($_SESSION['install_iconito']['password'])) echo $_SESSION['install_iconito']['password']; ?>" /></td></tr>
<!--  <tr><td align="right">Base de donn&eacute;es :</td><td><input name="database" /></td></tr> -->
<tr><td align="right"></td><td><input type="submit" value="Valider" /></td></tr>
</table>
</form>
<?php
        break;

    case 5:
        display_menu();
        display_title();
        session_start();

        if( isset( $_POST['database'] ) && !("new_database" == $_POST['database'] && ""==trim($_POST['database_name']) ) ) {
            if( ereg( "^database_(.*)$", $_POST['database'], $regs ) ) $database = $regs[1];
            elseif( "new_database" == $_POST['database'] ) $database = $_POST['database_name'];

            $_SESSION['install_iconito']['database'] = $database;

            $tables = check_mysql_tables( $database );

            if( $tables == -1 ) {


                $result = check_mysql_createdatabase( $_SESSION['install_iconito']['database'] );
                if( $result ) {
                    display_message( _LOGO_GOOD."La base \"".$_SESSION['install_iconito']['database']."\" a &eacute;t&eacute; cr&eacute;&eacute;e." );
                    display_link( "Cliquez ici pour cr&eacute;er les tables", 'index.php?step='.($step+1) );
                } else {
                    display_message( _LOGO_ERROR."La base \"".$_SESSION['install_iconito']['database']."\" ne peut pas &ecirc;tre cr&eacute;&eacute;e. V&eacute;rifiez les droits d'acc&egrave;s &agrave; votre base de donn&eacute;es. N'utilisez pas de caract&egrave;res sp&eacute;ciaux." );
                    echo "Vous pouvez ";
                    display_link( "reconfigurer votre base de donn&eacute;es", 'index.php?step='.($step-1) );
                    echo " ou ";
                    display_link( "choisir une autre base", 'index.php?step='.($step) );
                }
                echo " (cette op&eacute;ration peut prendre quelques secondes...).";

                break;
            } elseif( count($tables) ) {
                display_message( _LOGO_WARNING."Attention, cette base n'est pas vide. Vous risquez d'effacer des donn&eacute;es !!!" );
                display_link( "Cliquez ici pour choisir une autre base", 'index.php?step='.($step) );
                echo " ou ";
                display_link( "cliquez ici pour &eacute;craser les tables existantes", 'index.php?step='.($step+1) );
                echo " (cette op&eacute;ration peut prendre quelques secondes...).";
                break;
            } else {
                display_message( _LOGO_GOOD."Cette base est vide." );
                display_link( "Cliquez ici pour cr&eacute;er les tables", 'index.php?step='.($step+1) );
                echo " (cette op&eacute;ration peut prendre quelques secondes...).";
                break;
            }
        }

        display_message( "Choisissez la base que vous souhaitez utiliser, ou cr&eacute;ez-en une." );

        $data = check_mysql_databases();

?>
<form method="post" name="selectdatabase">
<?php
        foreach( $data AS $base ) display_message( '<input type="radio" name="database" value="database_'.$base.'" id="database_'.$base.'"/> <label for="database_'.$base.'">'.$base.'</label>' );
display_message( '<input type="radio" name="database" value="new_database" id="new_database"/> <input name="database_name" onFocus="forms.selectdatabase.new_database.checked = true;" />' );
?>
<input type="submit" value="Valider" />
</form>
<?php
        break;

    case 6:
        display_menu();
        display_title();
        session_start();

        $result = check_mysql_importdump( '../../instal/iconito.sql' );
        if( $result ) {
            display_message( _LOGO_GOOD."Les tables ont &eacute;t&eacute; cr&eacute;&eacute;es." );
            display_link( "Cliquez ici pour importer les donn&eacute;es", 'index.php?step='.($step+1) );
        } else {
            display_message( _LOGO_ERROR."Erreur lors de la cr&eacute;ation des tables." );
            display_link( "V&eacute;rifiez vos identifiants", 'index.php?step='.($step-2) );
        }
        break;

    case 7:
        display_menu();
        display_title();
        session_start();

        $ok=true;
        $result = check_mysql_importdump( '../../instal/data.sql' );
        if( $result ) {
            display_message( _LOGO_GOOD."Les donn&eacute;es ont &eacute;t&eacute; import&eacute;es." );
        } else {
            display_message( _LOGO_ERROR."Erreur lors de l'importation des donn&eacute;es." );
            $ok=false;
        }

        if($ok) {
            $result = check_mysql_importdump( '../../instal/ressources.sql' );
            if( $result ) {
                display_message( _LOGO_GOOD."Les ressources ont &eacute;t&eacute; import&eacute;es." );
            } else {
                display_message( _LOGO_ERROR."Erreur lors de l'importation des ressources." );
                $ok=false;
            }
        }

        if($ok) {
            $result = check_mysql_importdump( '../../instal/gestionautonome_droits.sql' );
            if( $result ) {
                display_message( _LOGO_GOOD."Les droits d'acc&egrave;s pour la gestion autonome ont &eacute;t&eacute; import&eacute;s." );
            } else {
                display_message( _LOGO_ERROR."Erreur lors de l'importation des droits." );
                $ok=false;
            }
        }

        if($ok) {
            $result = check_mysql_importdump( '../../instal/gestionautonome_nullable.sql' );
            if( $result ) {
                display_message( _LOGO_GOOD."Les modifications des tables scolaires ont &eacute;t&eacute; import&eacute;es." );
            } else {
                display_message( _LOGO_ERROR."Erreur lors des modifications des tables scolaires." );
                $ok=false;
            }
        }

    if ($ok) {
      $tools = new Demo_Tools();
      $folders = array (
        'www/static/classeur/1-49376fcb9d',
        'www/static/prefs/avatar',
      );
      foreach ($folders as $folder) {
        $installFolder = $tools->installFolder ($folder, false);
        if (!$installFolder) {
          display_message( _LOGO_ERROR."Probl&egrave;me de mise en place du dossier ".$folder."." );
          $ok=false;
        }
      }
      if ($ok) {
        display_message( _LOGO_GOOD."Les dossiers et fichiers de l'&eacute;dito ont bien &eacute;t&eacute; mis en place." );
      }
    }

    if ($ok) {
          check_mysql_runquery("INSERT INTO version SET version='".$version."', date=NOW()");
    }


        if($ok) display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
        else {
            display_link( "V&eacute;rifiez vos identifiants", 'index.php?step='.($step-3) );
            echo " ou ";
            display_link( "Recr&eacute;ez vos tables", 'index.php?step='.($step-1) );
        }

        break;

    case 8:
        display_menu();
        display_title();
        session_start();

        $data = check_admin_password();

        if( count($data['errors']) ) foreach( $data['errors'] AS $error ) {
            $msg = '';
            if( $error['level']=='error' ) $msg.=_LOGO_ERROR;
            elseif( $error['level']=='warning' ) $msg.=_LOGO_WARNING;
            elseif( $error['level']=='good' ) $msg.=_LOGO_GOOD;
            $msg.=$error['message'];

            display_message( $msg );
        }

        if( $data['caninstall'] ) {
            set_admin_password( $_POST["passwd"] );
            display_message( _LOGO_GOOD."Votre mot de passe a &eacute;t&eacute; enregistr&eacute;." );
            display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
        } else {

            display_message( "Afin de prot&eacute;ger votre Iconito, merci de choisir le mot de passe d'administration." );
?>
<form method="post" name="selectpasswd">

<table border="0">
<tr><td align="right">Mot de passe :</td><td><input name="passwd" type="password" /></td></tr>
<tr><td align="right">Confirmez :</td><td><input name="passwd2" type="password" /></td></tr>
<tr><td></td><td><input type="submit" value="Valider" /></td></tr>
</table>

<p><b>Note :</b> <i>Votre mot de passe doit faire au minimum 6 caract&egrave;res et ne pas comporter que des lettres ou que des chiffres.</i></p>

</form>
<?php
        }
        break;

    case 9:
        display_menu();
        display_title();
        session_start();

        $data = check_admin_config();

        if( count($data['errors']) ) foreach( $data['errors'] AS $error ) {
            $msg = '';
            if( $error['level']=='error' ) $msg.=_LOGO_ERROR;
            elseif( $error['level']=='warning' ) $msg.=_LOGO_WARNING;
            elseif( $error['level']=='good' ) $msg.=_LOGO_GOOD;
            $msg.=$error['message'];

            display_message( $msg );
        }

        if( $data['caninstall'] ) {
            set_admin_config();
            display_message( _LOGO_GOOD."Vos pr&eacute;f&eacute;rences ont &eacute;t&eacute; enregistr&eacute;es." );
            display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
        } else {

            display_message( "Pour finir, vous pouvez configurer quelques fonctionnalit&eacute;s d'ICONITO EcoleNumerique." );
?>

<script type="text/javascript">
<!--
function toggle_mailEnabled()
{
    if( document.getElementById('conf_mailEnabled').checked ) {
        document.getElementById('conf_mailSmtpHost_box').style.visibility = 'visible';
        document.getElementById('conf_mailSmtpHost_box').style.display = 'block';
    } else {
        document.getElementById('conf_mailSmtpHost_box').style.visibility = 'hidden';
        document.getElementById('conf_mailSmtpHost_box').style.display = 'none';
    }
}
//-->
</script>

<style>
<!--
TABLE.conftable {
    margin: 10px;
    padding: 0px;
    border-spacing: 0px;
    border-collapse: collapse;
    border: 1px solid #CCC;
}

TABLE.conftable TD {
    padding: 5px;
    margin: 0px;
    background-color: #EEE;
}
-->
</style>
<form method="post" name="adminconfig">

<input name="conf" type="hidden" value="1" />

<label for="conf_mailEnabled"><strong>&raquo; Activer l'envoi de mails :</strong></label>
<input name="conf_mailEnabled" id="conf_mailEnabled" value="1" type="checkbox" onchange="javascript: toggle_mailEnabled();" /> Activer ?<br /><i>(&agrave; activer pour autoriser les alertes par mail)</i>

<div style="display: none; visibility: hidden;" id="conf_mailSmtpHost_box">
    <table class="conftable" cellspacing="0">
    <tr>
        <td align="right"><span>Serveur SMTP :</span></td>
        <td><input name="conf_mailSmtpHost" id="conf_mailSmtpHost" value="localhost" /></td>
    </tr>
    <tr>
        <td align="right"><span>Adresse mail d'envoi :</span></td>
        <td><input name="conf_mailFrom" id="conf_mailFrom" value="" /> (utilisez une adresse r&eacute;elle)</td>
    </tr>
    <tr>
        <td align="right"><span>Nom de l'exp&eacute;diteur :</span></td>
        <td><input name="conf_mailFromName" id="conf_mailFromName" value="Alerte Iconito" /></td>
    </tr>
    </table>
</div>

<br /><br />
<input type="submit" value="Valider" />
</form>
<?php
        }

        break;

    case 10:
        display_menu();
        display_title();
        session_start();

        $data = check_copy_files();
        if( $data['errors'] && count($data['errors']) ) {
            foreach( $data['errors'] AS $error ) {
                $msg = '';
                if( $error['level']=='error' ) $msg.=_LOGO_ERROR;
                elseif( $error['level']=='warning' ) $msg.=_LOGO_WARNING;
                elseif( $error['level']=='good' ) $msg.=_LOGO_GOOD;
                $msg.=$error['message'];

                display_message( $msg );
            }
        } else {
            display_message( _LOGO_GOOD."F&eacute;licitations, ICONITO Ecole Num&eacute;rique est install&eacute; !" );
            display_message( "Pour vous connecter, utilisez le login <b>admin</b> et le mot de passe d'administration choisi pr&eacute;c&eacute;dement." );
            display_link( "Cliquez ici pour y acc&eacute;der", ".." );
            display_message( "Afin de d&eacute;couvrir Iconito, vous pouvez utiliser le \"jeu d'essai\", un ensemble de comptes d'acc&egrave;s et de contenus de d&eacute;monstration. Pour l'installer, connectez-vous en administrateur et allez dans le module d'utilitaires syst&egrave;me." );
            // display_link( "Guide utilisateur Ecole Num&eacute;rique", "http://www.iconito.fr/telechargement/documentation/62-utilisation-ecole-numerique");
        }

        break;

    default:
        header("Location: index.php?step=1");
        break;
}


global $display_header;
if( $display_header ) echo "</div>";

close_page ();

