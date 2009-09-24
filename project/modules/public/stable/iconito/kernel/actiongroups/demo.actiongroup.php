<?php
/**
 * Demo - ActionGroup
 *
 * Fonctions relatives au jeu d'essai de démo
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: demo.actiongroup.php,v 1.5 2009-04-01 13:10:00 cbeyer Exp $
 * @author	Christophe Beyer <fmossmann@cap-tic.fr>
 */

_classInclude('kernel|demo_db');
_classInclude('kernel|demo_auth');
_classInclude('kernel|demo_tools');
_classInclude('admin2|admin');

class ActionGroupDemo extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}
	
	
  /**
   * Regarde si la démo est déjà installé
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/10/26
   */
  function status () {
    
    $errors = array();

    if (!Demo_Auth::canInstall())
      $errors[] = CopixI18N::get ('kernel|demo.error.noRights');
    
    if ($errors)
      return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get()));
    
    $tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel|demo.titlePage'));
		$tplDemo = & new CopixTpl ();
		$tplDemo->assign ("installed", CopixConfig::get ('kernel|jeuEssaiInstalled'));
		$tpl->assign ("MAIN", $tplDemo->fetch("demo_status.tpl"));
		$tpl->assign ('MENU', Admin::getMenu());
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

	}
  
  /**
   * Installe le jeu d'essai
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/10/26
   */
  function install () {
    global $params;
    $db = new Demo_DB();
    $tools = new Demo_Tools();
    $db->extract_db_infos ();
    $db->db_connect ();
    $fileSQL = '../instal/demo/jeu_essai.sql';
    
    $errors = array();
    
    if (!Demo_Auth::canInstall())
      $errors[] = CopixI18N::get ('kernel|demo.error.noRights');
    elseif (CopixConfig::get ('kernel|jeuEssaiInstalled') == 1)
      $errors[] = CopixI18N::get ('kernel|demo.error.alreadyInstalled');
    elseif (!is_file($fileSQL))
      $errors[] = CopixI18N::get ('kernel|demo.error.noFileSql');
    
    if ($errors)
      return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get()));

    $contents = file_get_contents ($fileSQL);
    $lines = explode (";\n", $contents);
    foreach ($lines as $line) {
      $line = trim($line);
      if ($line) {
        //print_r("<br>***line=".$line);
        $db->run_query ($line);
      }
    }
    $db->db_close ();
      
    // Copie des dossiers (pas de slashs à la fin!)
    $tools->installFolder ('www/static/malle/1_9a4ba0cdef');
    $tools->installFolder ('var/data/blog/logos');
    $tools->installFolder ('www/static/album/1_be8550b87c');
    $tools->installFolder ('www/static/album/2_cf057489c9');
    $tools->installFolder ('www/static/album/3_c996b6cf13');
    $tools->installFolder ('www/static/prefs/avatar');
    
    // Fin
    CopixConfig::set ('kernel|jeuEssaiInstalled', 1);
    
    $tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel|demo.titlePage'));
		$tplDemo = & new CopixTpl ();
		//$tplDemo->assign ("toto", 1);
		$tpl->assign ("MAIN", $tplDemo->fetch("demo_install.tpl"));
		$tpl->assign ('MENU', Admin::getMenu());
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
  }
}
?>
