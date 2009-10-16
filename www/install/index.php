<?php
ob_start();
require_once ("install_check.class.php");
require_once ("install_design.class.php");
require_once ('../../utils/copix/copix.inc.php');
require_once ('../../project/project.inc.php');

define( '_LOGO_GOOD', '<img src="images/accept.png" align="baseline" />&nbsp;&nbsp;' );
define( '_LOGO_WARNING', '<img src="images/error.png" align="baseline" />&nbsp;&nbsp;' );
define( '_LOGO_ERROR', '<img src="images/cancel.png" align="baseline" />&nbsp;&nbsp;' );

if( !isset($_GET['step']) ) $step=1;
else $step=0+$_GET['step'];

$titles = array(
	1 => "Bienvenue",
	2 => "Droits des fichiers",
	3 => "Vérification de PHP",
	4 => "Configuration Mysql",
	5 => "Choix de la base",
	6 => "Création des tables",
	7 => "Import des données",
	8 => "Mot de passe",
	9 => "Configuration",
	10 => "Fini !",
);

if( file_exists(COPIX_LOG_PATH.'.installed') && file_exists('../../project/config/copix.conf.php') ) {
	// display_menu();
	display_title();
	display_message( "ICONITO EcoleNumerique est déjà installé. Pour y accéder, <a href=\"..\">cliquez ici</a> !" );
	display_message( "Si vous souhaitez refaire une installation, vous devez supprimer manuellement le fichier \".installed\" qui se trouve dans \"temp/log\" et recharger cette page." );
	die();
}


switch( $step ) {
	case 1:
		display_menu();
		display_title();
		display_message( "Vous souhaitez installer ICONITO EcoleNumerique ? <a href=\"index.php?step=".($step+1)."\">Cliquez ici</a> !" );
		break;

	case 2:
		display_menu();
		display_title();
		$data = check_rights();
		if( ! $data['caninstall'] ) {
			display_message( _LOGO_ERROR."Les fichiers et répertoires suivants doivent êtres accessible en écriture pour Apache :" );
			display_list_start();
			foreach( $data['errors'] AS $file ) display_list_item( $file );
			display_list_stop();
			display_link( "Corrigez et cliquez ici pour réessayer", 'index.php?step='.($step) );
		} else {
			display_message( _LOGO_GOOD."Les droits sur les fichiers et répertoires sont corrects" );
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
			display_link( "Corrigez et cliquez ici pour réessayer", 'index.php?step='.($step) );
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
			display_message( "Afin de configurer votre base de données, merci de renseigner les information de connexion." );
		}
?>
<form method="post">
<table border="0">
<tr><td align="right">Serveur :</td><td><input name="host" value="<?php if(isset($_SESSION['install_iconito']['host'])) echo $_SESSION['install_iconito']['host']; else echo "localhost"; ?>" /> (vous pouvez préciser le port. Exemple : <tt>serveur:3306</tt>)</td></tr>
<tr><td align="right">Login :</td><td><input name="login" value="<?php if(isset($_SESSION['install_iconito']['login'])) echo $_SESSION['install_iconito']['login']; ?>" /></td></tr>
<tr><td align="right">Mot de passe :</td><td><input name="password" type="password" value="<?php if(isset($_SESSION['install_iconito']['password'])) echo $_SESSION['install_iconito']['password']; ?>" /></td></tr>
<!--  <tr><td align="right">Base de données :</td><td><input name="database" /></td></tr> -->
<tr><td align="right"></td><td><input type="submit" value="Valider" /></td></tr>
</table>
</form>
<?php
		break;

	case 5:
		display_menu();
		display_title();
		
		if( isset( $_POST['database'] ) && !("new_database" == $_POST['database'] && ""==trim($_POST['database_name']) ) ) {
			if( ereg( "^database_(.*)$", $_POST['database'], $regs ) ) $database = $regs[1];
			elseif( "new_database" == $_POST['database'] ) $database = $_POST['database_name'];

			session_start();
			$_SESSION['install_iconito']['database'] = $database;

			$tables = check_mysql_tables( $database );
			
			if( $tables == -1 ) {
				
				
				$result = check_mysql_createdatabase( $_SESSION['install_iconito']['database'] );
				if( $result ) {
					display_message( _LOGO_GOOD."La base \"".$_SESSION['install_iconito']['database']."\" a été créée." );
					display_link( "Cliquez ici pour créer les tables", 'index.php?step='.($step+1) );
				} else {
					display_message( _LOGO_ERROR."La base \"".$_SESSION['install_iconito']['database']."\" ne peut pas être créée. Vérifiez les droits d'accès à votre base de données. N'utilisez pas de caractères spéciaux." );
					echo "Vous pouvez ";
					display_link( "reconfigurer votre base de données", 'index.php?step='.($step-1) );
					echo " ou ";
					display_link( "choisir une autre base", 'index.php?step='.($step) );
				}

				break;
			} elseif( count($tables) ) {
				display_message( _LOGO_WARNING."Attention, cette base n'est pas vide. Vous risquez d'effacer des données !!!" );
				display_link( "Cliquez ici pour choisir une autre base", 'index.php?step='.($step) );
				echo " ou ";
				display_link( "cliquez ici pour écraser les tables existantes", 'index.php?step='.($step+1) );
				break;
			} else {
				display_message( _LOGO_GOOD."Cette base est vide." );
				display_link( "Cliquez ici pour créer les tables", 'index.php?step='.($step+1) );
				break;
			}
		}
		
		display_message( "Choisissez la base que vous souhaitez utiliser, ou créez-en une." );
		
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

		$result = check_mysql_importdump( '../../instal/iconito.sql' );
		if( $result ) {
			display_message( _LOGO_GOOD."Les tables ont été créées." );
			display_link( "Cliquez ici pour importer les données", 'index.php?step='.($step+1) );
			echo " (cette opération peut prendre du temps...).";
		} else {
			display_message( _LOGO_ERROR."Erreur lors de la création des tables." );
			display_link( "Vérifiez vos identifiants", 'index.php?step='.($step-2) );
		}
		break;

	case 7:
		display_menu();
		display_title();

		$result = check_mysql_importdump( '../../instal/data.sql' );
		if( $result ) {
			check_mysql_runquery("INSERT INTO version SET version='".$version."', date=NOW()");
			display_message( _LOGO_GOOD."Les données ont été importées." );
			display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
		} else {
			display_message( _LOGO_ERROR."Erreur lors de l'importation des données." );
			display_link( "Vérifiez vos identifiants", 'index.php?step='.($step-3) );
			echo " ou ";
			display_link( "recréez vos tables", 'index.php?step='.($step-1) );
		}
		break;
	
	case 8:
		display_menu();
		display_title();
		
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
			display_message( _LOGO_GOOD."Votre mot de passe a été enregistré." );
			display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
		} else {
		
			display_message( "Afin de protéger votre Iconito, merci de choisir le mot de passe d'administration." );
?>
<form method="post" name="selectpasswd">

<table border="0">
<tr><td align="right">Mot de passe :</td><td><input name="passwd" type="password" /></td></tr>
<tr><td align="right">Confirmez :</td><td><input name="passwd2" type="password" /></td></tr>
<tr><td></td><td><input type="submit" value="Valider" /></td></tr>
</table>

<p><b>Note :</b> <i>Votre mot de passe doit faire au minimum 6 caractères et ne pas comporter que des lettres ou que des chiffres.</i></p>

</form>
<?php
		}	
		break;

	case 9:
		display_menu();
		display_title();
		
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
			display_message( _LOGO_GOOD."Vos préférences ont été enregistrées." );
			display_link( "Cliquez ici pour continuer", 'index.php?step='.($step+1) );
		} else {
		
			display_message( "Pour finir, vous pouvez configurer quelques fonctionnalités d'ICONITO EcoleNumerique." );
?>

<script type="text/javascript">
<!--
function toggle_mailEnabled() {
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
<input name="conf_mailEnabled" id="conf_mailEnabled" value="1" type="checkbox" onchange="javascript: toggle_mailEnabled();" /> Activer ?<br /><i>(à activer pour autoriser les alertes par mail)</i>

<div style="display: none; visibility: hidden;" id="conf_mailSmtpHost_box">
	<table class="conftable" cellspacing="0">
	<tr>
		<td align="right"><span>Serveur SMTP :</span></td>
		<td><input name="conf_mailSmtpHost" id="conf_mailSmtpHost" value="localhost" /></td>
	</tr>
	<tr>
		<td align="right"><span>Adresse mail d'envoi :</span></td>
		<td><input name="conf_mailFrom" id="conf_mailFrom" value="" /> (utilisez une adresse réelle)</td>
	</tr>
	<tr>
		<td align="right"><span>Nom de l'expéditeur :</span></td>
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
			display_message( _LOGO_GOOD."F&eacute;licitations, ICONITO EcoleNumerique est installé !" );
			display_message( "Pour vous connecter, utilisez le login <b>admin</b> et le mot de passe d'administration choisi précédement." );
			display_link( "Cliquez ici pour y acc&eacute;der", ".." );
			display_message( "Afin de découvrir Iconito, vous pouvez utiliser le \"jeu d'essai\", un ensemble de comptes d'accès et de contenus de démonstration. Pour l'installer, connectez-vous en administrateur et allez dans le module d'administration." );
		}
		
		break;
		
	default:
		header("Location: index.php?step=1");
		break;
}


global $display_header;
if( $display_header ) echo "</div>";
?>
