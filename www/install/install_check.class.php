<?php

function check_rights() {
	$data = array();
	$data['errors'] = array();
	
	$files = array(
		COPIX_LOG_PATH,
		COPIX_VAR_PATH,
		COPIX_VAR_PATH.'config/',
		COPIX_CACHE_PATH,
		COPIX_CACHE_PATH.'php',
		COPIX_CACHE_PATH.'php/templates',
		COPIX_CACHE_PATH.'html',
		COPIX_CACHE_PATH.'html/templates',
		COPIX_CACHE_PATH.'html/default',
		COPIX_CACHE_PATH.'html/zones',
		COPIX_PROJECT_PATH,
		COPIX_PROJECT_PATH.'config',
		COPIX_PROJECT_PATH.'../www/static',
		COPIX_PROJECT_PATH.'../www/static/album',
		COPIX_PROJECT_PATH.'../www/static/malle',
		COPIX_PROJECT_PATH.'../www/static/prefs',
		COPIX_PROJECT_PATH.'../www/static/prefs/avatar',
		COPIX_PROJECT_PATH.'../data',
		COPIX_PROJECT_PATH.'../data/album',
		COPIX_PROJECT_PATH.'../data/malle',
		COPIX_PROJECT_PATH.'../data/minimail',
	);

	$data['errors'] = array();
	$data['caninstall'] = true;

	foreach($files as $file){
		$file = realpath($file);
		if(!is_writable($file)){
			$data['caninstall'] = false;
			$data['errors'][]= $file;
		}
	}

	return $data;
}
	
function check_php () {
	$data = array();
	$data['errors'] = array();
	$data['caninstall'] = true;

	$ver = explode( '.', PHP_VERSION );
	$ver_num = $ver[0] . $ver[1] . $ver[2];

	if ( $ver_num < 420 )
	{
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_version',
			'message' => 'Votre version de PHP est trop vieille. Installez PHP 4.2 minimum ou PHP 5.',
		);
	}
	elseif ( $ver_num < 500 )
	{
		$data['errors'][] = array(
			'level' => 'warning',
			'code' => 'php_version',
			'message' => 'Vous utilisez PHP '.PHP_VERSION.' !<br />Cette version, elle arrive en fin de vie et il n\'y aura plus de mise à jour à partir de 2008.<br />Même si Iconito fonctionne avec cette version, si vous le pouvez, passez en PHP 5.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_version',
			'message' => 'Votre version de PHP ('.PHP_VERSION.') est supportée par Iconito.',
		);
	}
	
	if( ini_get('session.auto_start') ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'session_autostart',
			'message' => 'Vous devez désactiver la création de session automatique. Pour cela, modifiez la directive "session.auto_start" dans votre php.ini (pour tous vos sites), dans la configuration de votre virtualhost dans la configuration d\'Apache (pour ce site spécifiquement), ou dans le .htaccess d\'Iconito (dans le répertoire "Iconito/www").',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'session_autostart',
			'message' => 'La création de session automatique est désactivée.',
		);
	}
	
	$php_extensions = get_loaded_extensions();

	if( ! in_array('xml',$php_extensions) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_ext_xml',
			'message' => 'Vous devez activer l\'extension "xml" dans PHP.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_ext_xml',
			'message' => 'L\'extension "xml" est activée.',
		);
	}

	if( ! in_array('session',$php_extensions) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_ext_session',
			'message' => 'Vous devez activer l\'extension "session" dans PHP.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_ext_session',
			'message' => 'L\'extension "session" est activée.',
		);
	}

	if( ! in_array('mysql',$php_extensions) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_ext_mysql',
			'message' => 'Vous devez activer l\'extension "mysql" dans PHP.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_ext_mysql',
			'message' => 'L\'extension "mysql" est activée.',
		);
	}

	if( ! in_array('gd',$php_extensions) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_ext_gd',
			'message' => 'Vous devez activer l\'extension "gd" dans PHP.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_ext_gd',
			'message' => 'L\'extension "gd" est activée.',
		);
		
		if( ! (imagetypes() & IMG_GIF) ) {
			$data['caninstall'] = true;
			$data['errors'][] = array(
				'level' => 'warning',
				'code' => 'php_gd_gif',
				'message' => 'Votre version de "gd" ne supporte pas l\'écriture du format GIF. Il est préférable de mettre à jour cette extension.',
			);
		} else {
			$data['errors'][] = array(
				'level' => 'good',
				'code' => 'php_gd_gif',
				'message' => 'Votre version de "gd" supporte le format GIF.',
			);
		}
	}

	if( ! in_array('zlib',$php_extensions) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'php_ext_zlib',
			'message' => 'Vous devez activer l\'extension "zlib" dans PHP.',
		);
	} else {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'php_ext_zlib',
			'message' => 'L\'extension "zlib" est activée.',
		);
	}

	return $data;
}

function check_mysql_login() {
	if( !isset($_POST['login']) || trim($_POST['login'])=='' ) return;
	
	$link = @mysql_connect($_POST['host'], $_POST['login'], $_POST['password']);
	if( $link ) {
		$data['errors'][] = array(
			'level' => 'good',
			'code' => 'mysql_connect',
			'message' => 'Connexion MySQL réussie.',
		);
		mysql_close($link);
	} else {
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'mysql_connect',
			'message' => 'Connexion MySQL impossible. Vérifiez vos identifiants.',
		);
	}
	
	return $data;
}

function check_mysql_databases() {
	$data = array();
	session_start();
	$link = @mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']) or die("Problème de base mysql");
	$db_list = @mysql_list_dbs($link);
	if( $db_list != false ) {
		while ($row = mysql_fetch_object($db_list)) {
			$data[] = $row->Database;
		}
	}
	return $data;
}

function check_mysql_tables($database) {
	$data = array();
	session_start();
	$link = @mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']) or die("Problème de base mysql");

	$db_selected = mysql_select_db($database, $link);
	if (!$db_selected) {
		return -1;
	}

	$sql = "SHOW TABLES FROM $database";
	$result = mysql_query($sql);
	
	while ($row = mysql_fetch_array($result)) {
		$data[] = $row[0];
	}
	return $data;
}

function check_mysql_createdatabase( $database ) {
	if( -1 != check_mysql_tables( $database )) return false;
	session_start();
	$link = @mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']) or die("Problème de base mysql");

	$sql = "CREATE DATABASE $database";
	if (mysql_query($sql, $link)) {
		return true;
	} else {
		return false;
	}
}

function check_mysql_importdump( $filename ) {
	session_start();
	$link = @mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']) or die("Problème de base mysql");
	if( !$link ) die( "Erreur de connexion MySQL" );
	
	mysql_select_db($_SESSION['install_iconito']['database']);

	return( do_mysql_importdump( $filename, $link) );	
}

function do_mysql_importdump( $filename, $link ) {
	/* Read the file */
	$lines = file($filename);

	if(!$lines)
	{
		echo "cannot open file $filename";
		return false;
	}

	$scriptfile = false;

	/* Get rid of the comments and form one jumbo line */
	foreach($lines as $line)
	{
		$line = trim($line);

		if(!ereg('^--', $line) && !ereg('^#', $line) )
		{
			$scriptfile.="\n".$line;
		}
	}

	if(!$scriptfile)
	{
		echo "no text found in $filename";
		return false;
	}

	/* Split the jumbo line into smaller lines */

	// $queries = explode(';', $scriptfile);
	$queries = split(";[[:space:]]*\n", $scriptfile);

	/* Run each line as a query */

	foreach($queries as $query)
	{
		$query = trim($query);
		if($query == "") { continue; }
		/* */
		if(!mysql_query($query.';', $link) )
		{
			echo "Erreur : ".mysql_error();
			return false;
		}
		/* */
		// if( 1||!ereg("^INSERT ", $query) ) echo "<pre>---\n".$query."\n---</pre>";
	}

	return true;
}



// CB
function check_mysql_runquery( $query ) {
	session_start();
	$link = @mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']) or die("Problème de base mysql");
	if( !$link ) die( "Erreur de connexion MySQL" );
	
	mysql_select_db($_SESSION['install_iconito']['database']);

	return( do_mysql_runquery( $query, $link) );	
}

function do_mysql_runquery( $query, $link ) {
	if(!mysql_query($query.';', $link) )
	{
		echo "Erreur : ".mysql_error();
		return false;
	}
	return true;
}






function check_admin_password() {
	$data['caninstall'] = false;
	$data['errors'] = array();
	
	if( !isset($_POST["passwd"]) ) return $data;
	
	if( trim($_POST["passwd"]) =='' ) {
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'passwd_empty',
			'message' => '<b>Votre mot de passe est vide !</b>',
		);
	} else {
		if( strlen($_POST["passwd"])<6 ) {
			$data['errors'][] = array(
				'level' => 'error',
				'code' => 'passwd_tooshort',
				'message' => '<b>Votre mot de passe est trop court</b> : 6 caractères minimum.',
			);
		}
		if( ereg('^[a-z]*$',$_POST["passwd"]) || ereg('^[A-Z]*$',$_POST["passwd"]) || ereg('^[0-9]*$',$_POST["passwd"]) ) {
			$data['errors'][] = array(
				'level' => 'error',
				'code' => 'passwd_tooeasy',
				'message' => '<b>Votre mot de passe est trop simple</b> : Mélangez les minuscules et majuscules ou ajouter des chiffres ou des symboles.',
			);
		}
		if( $_POST["passwd"] != $_POST["passwd2"] ) {
			$data['errors'][] = array(
				'level' => 'error',
				'code' => 'passwd_diff',
				'message' => '<b>Merci de confirmer votre mot de passe</b> : Saisissez deux fois la même chose.',
			);
		}
	}
		
	if( count($data['errors'])==0 ) $data['caninstall'] = true;
	
	return $data;
}

function set_admin_password( $passwd ) {
	session_start();
	$link = mysql_connect($_SESSION['install_iconito']['host'], $_SESSION['install_iconito']['login'], $_SESSION['install_iconito']['password']);
	if( !$link ) die( "Erreur de connexion MySQL : ".mysql_error() );
	mysql_select_db($_SESSION['install_iconito']['database']);

	$query = "UPDATE copixuser SET password_cusr = MD5( '".addslashes($passwd)."' ) WHERE id_cusr=1;";
	mysql_query($query, $link) or die( mysql_error() );
}

function check_admin_config() {
	
	$data['caninstall'] = true;
	$data['errors'] = array();
	
	if( !isset($_POST["conf"]) ) $data['caninstall'] = false;
	
	if( false !== strpos($_POST["conf_mailFrom"].$_POST["conf_mailFromName"].$_POST["conf_mailSmtpHost"], '"' ) ) {
		$data['caninstall'] = false;
		$data['errors'][] = array(
			'level' => 'error',
			'code' => 'conf_quote',
			'message' => 'Vous ne pouvez pas utiliser le caractère double-guillemet (")',
		);
	}
	
	return $data;
}

function set_admin_config() {
	$string = do_read_file( COPIX_PROJECT_PATH."project.default.xml" );
	if( isset($_POST["conf_mailEnabled"]) && $_POST["conf_mailEnabled"] ) {
		$patterns[0] = '/captioni18n="parameter.mailEnabled"  default="0"/';
		$patterns[1] = '/captioni18n="parameter.mailFrom"  default="nobody@iconito.fr"/';
		$patterns[2] = '/captioni18n="parameter.mailFromName"  default="Iconito"/';
		$patterns[3] = '/captioni18n="parameter.mailSmtpHost"  default="localhost"/';
		$replacements[0] = 'captioni18n="parameter.mailEnabled"  default="1"';
		$replacements[1] = 'captioni18n="parameter.mailFrom"  default="'.stripslashes($_POST["conf_mailFrom"]).'"';
		$replacements[2] = 'captioni18n="parameter.mailFromName"  default="'.stripslashes($_POST["conf_mailFromName"]).'"';
		$replacements[3] = 'captioni18n="parameter.mailSmtpHost"  default="'.stripslashes($_POST["conf_mailSmtpHost"]).'"';
		$string = preg_replace($patterns, $replacements, $string);
	}
	do_write_file( COPIX_PROJECT_PATH."project.xml", $string );
	
}

function check_copy_files() {
	session_start();
	
	$string = do_read_file( COPIX_PROJECT_PATH."config/copix.conf.default.php" );
	do_write_file( COPIX_PROJECT_PATH."config/copix.conf.php", $string );
	
	
	$string = do_read_file( COPIX_VAR_PATH."config/profils.copixdb.default.xml" );
	$patterns[0] = '/dataBase="iconito"/';
	$patterns[1] = '/host="localhost"/';
	$patterns[2] = '/user="root"/';
	$patterns[3] = '/password=""/';
	$replacements[0] = 'dataBase="'.addslashes($_SESSION['install_iconito']['database']).'"';
	$replacements[1] = 'host="'.addslashes($_SESSION['install_iconito']['host']).'"';
	$replacements[2] = 'user="'.addslashes($_SESSION['install_iconito']['login']).'"';
	$replacements[3] = 'password="'.addslashes($_SESSION['install_iconito']['password']).'"';
	$string = preg_replace($patterns, $replacements, $string);
	do_write_file( COPIX_VAR_PATH."config/profils.copixdb.xml", $string );
	
	do_write_file( COPIX_LOG_PATH.'.installed', '' );
	
	return $data;
}

function do_read_file( $filename ) {
	$buffer = file_get_contents( $filename );
	return( $buffer );
}

function do_write_file( $filename, $string ) {
	$f=@fopen($filename,"w");
	if (!$f) {
		return false;
	} else {
		fwrite($f,$string);
		fclose($f);
		return true;
	}
}

?>
