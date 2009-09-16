<?php
/**
* @package  Iconito
* @subpackage Prefs
* @version   $Id: prefs.actiongroup.php,v 1.8 2007-12-20 09:46:27 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2005 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/


require_once (COPIX_MODULE_PATH.'prefs/'.COPIX_CLASSES_DIR.'prefs.class.php');


class ActionGroupPrefs extends CopixActionGroup {

	function getPrefs () {
		
		if( !isset($_SESSION["user"]->bu) || !isset($_SESSION["user"]->bu["type"]) || !isset($_SESSION["user"]->bu["id"]) ) {
	      return CopixActionGroup::process ('genericTools|Messages::getError',
	      array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'),
	      'back'=>CopixUrl::get ('auth|default|login')));
		}
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_prefs.css"));

		$tpl = & new CopixTpl ();
		
		$main='';
		$prefs=array();
		
		$modules = Prefs::getModules();
		
		$data = Prefs::getPrefs();
		
		foreach( $modules AS $mod_key=>$mod_val ) {
			
			$class_file = COPIX_MODULE_PATH.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
			if( !file_exists( $class_file ) ) continue;
			
			$module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');
			
			$pref = $module_class->getPrefs( $data[$mod_val->rep] );
			$pref['code'] = $mod_val->rep;
			$prefs[] = $pref;
		}
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('prefs.moduleDescription'));
		$tpl->assign ('MAIN', CopixZone::process ('prefs|prefs', array('prefs'=>$prefs, 'get'=>$_GET )));
		
		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>' );
		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="Expires" CONTENT="-1"/>' );
		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>' );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function setPrefs () {
		if( Kernel::isDemo() ) return Kernel::noDemo();
		
		if( !isset($_SESSION["user"]->bu) || !isset($_SESSION["user"]->bu["type"]) || !isset($_SESSION["user"]->bu["id"]) ) {
	      return CopixActionGroup::process ('genericTools|Messages::getError',
	      array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'),
	      'back'=>CopixUrl::get ('auth|default|login')));
		}
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_prefs.css"));

		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>' );
		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="Expires" CONTENT="-1"/>' );
		CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>' );
		
		$tpl = & new CopixTpl ();
		
		// Liste des modules qui peuvent avoir des préférences...
		$modules = Prefs::getModules();

		$datas = array();
		$errors = array();

		reset( $modules );
		foreach( $modules AS $mod_key=>$mod_val ) {
			
			$class_file = COPIX_MODULE_PATH.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
			if( !file_exists( $class_file ) ) continue;
			
			$module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');
			
			reset($_POST);
			// Parcours de tous les parametres passé en POST, pour chaque module.
			foreach( $_POST AS $post_key => $post_val ) {
				if( ereg( '^'.$mod_val->rep.'_(.+)$', $post_key, $regs ) ) {
					$datas[$mod_val->rep][$regs[1]] = $post_val;
				}
			}

			// Appel de la fonction de vérification du module.
			if( method_exists( $module_class, 'checkPrefs' ) ) {
				$error = $module_class->checkPrefs( $mod_val->rep, $datas[$mod_val->rep] );
				if( sizeof( $error ) ) $errors[$mod_val->rep] = $error;
			}
		}
		
		if( sizeof( $errors ) ) {
//			$tplPrefs = & new CopixTpl ();

			// Liste des modules disponibles...
			reset( $modules );
			foreach( $modules AS $mod_key=>$mod_val ) {
				
				// Vérification de la présence de la classe de préférence pour le module...
				$class_file = COPIX_MODULE_PATH.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
				if( !file_exists( $class_file ) ) continue;
				
				// Chargement de la classe...
				$module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');
				
				// Récupération de la structure des prefs...
				$pref = $module_class->getPrefs( $data[$mod_val->rep] );
				$pref['code'] = $mod_val->rep;
				
				if($pref['form']) // Protection contre les modules aux prefs vides
				foreach( $pref['form'] AS $key=>$val ) {
					if( isset($val['code']) && isset($_POST[$pref['code'].'_'.$val['code']]) ) {
						$pref['form'][$key]['value'] = $_POST[$pref['code'].'_'.$val['code']];
					}
					
					if( isset($errors[$pref['code']][$val['code']]) ) {
						$pref['form'][$key]['error'] = $errors[$pref['code']][$val['code']];
					}
					
				}
				
				$prefs[] = $pref;
			}
			
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('prefs.moduleDescription'));
			$tpl->assign ('MAIN', CopixZone::process ('prefs|prefs', array('prefs'=>$prefs)));
			
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		} else {

			reset( $modules );
			foreach( $modules AS $mod_key=>$mod_val ) {
				$class_file = COPIX_MODULE_PATH.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
				if( !file_exists( $class_file ) ) continue;
				$module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');

			/*
			foreach( $datas AS $mod_key=>$mod_val ) {
				
				$class_file = COPIX_MODULE_PATH.$mod_key.'/'.COPIX_CLASSES_DIR.'mod'.$mod_key.'prefs.class.php';
				if( !file_exists( $class_file ) ) continue;
				
				$module_class = & CopixClassesFactory::Create ($mod_key.'|mod'.$mod_key.'prefs');
			*/
					
				$module_class->setPrefs( $mod_val->rep, $datas[$mod_val->rep] );
			}
		}
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('prefs|default|default', array('msg'=>'save') ) );
	}

}
?>
