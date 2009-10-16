<?php
/**
 * @package		copix
 * @subpackage	project
 * @author		Croes Gérald
 * @copyright	Copix Team
 * @link		http://www.copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html Lesser GNU General Public Licence, see LICENCE file
 */

//var_dump(error_reporting(E_ALL));
//phpinfo();
 
//includes copix files.
//will define constants, paths, relative to copix.
$path = dirname (__FILE__);
require ($path . '/../utils/copix/copix.inc.php');
require ($path . '/../project/project.inc.php');

$GLOBALS['ChronoStart'] = microtime();
$GLOBALS['QueryCount'] = 0;

setlocale(LC_TIME, 'fr_FR');

if (!file_exists(COPIX_LOG_PATH.'.installed') || !file_exists('../project/config/copix.conf.php'))
   die( "Votre Iconito n'est pas encore install�. <a href=\"install/index.php\">Cliquez ici</a>." );

// tentative de création du controlleur
try {	
	$coord = new ProjectController ($path . '/../project/config/copix.conf.php');
	$coord->process ();

// on gère les exceptions de type CopixCredentialException différement, elles redirigent au lieu de s'afficher, et ne génèrent pas de log
} catch (CopixCredentialException $e) {
	header ('location: ' . CopixUrl::get ('auth||', array ('noCredential' => 1, 'auth_url_return' => _url ('#'))));
	exit ();
	
// toutes les exceptions de Copix passeront ici, sauf CopixCredentialException
} catch (CopixException $e) {
	$extras = array (
		'file' => $e->getFile (),
		'line' => $e->getLine (),
		'exception' => get_class ($e)
	);
	_log ($e->getMessage (), 'errors', CopixLog::EXCEPTION, $extras);
	
	// si l'exception générée est dans la création du coordinateur, on ne peut pas faire un bon affichage de l'exception
	if (!isset ($coord)) {
		echo $e->getMessage ();
	
	} else {
		// on vérifie que le coordinateur arrive bien à afficher l'exception, il se peut qu'il n'y arrive pas
		try {
			$coord->showException ($e);
			
		} catch (Exception $e2) {
			_log ($e2->getMessage (), 'errors', CopixLog::EXCEPTION);
			echo $e->getMessage () . '<br/>';
			echo $e2->getMessage ();
		}
	}

// les exceptions qui ne dépendent pas de Copix
} catch (Exception $e){
	echo $e->getMessage ();
}
?>