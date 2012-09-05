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

_classInclude('prefs|prefs');

class ActionGroupPrefs extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    public function getPrefs ()
    {
        CopixHTMLHeader::addCSSLink (_resource("styles/module_prefs.css"));

        $tpl = new CopixTpl ();

        $main='';
        $prefs=array();

        $modules = Prefs::getModules();
        $data = Prefs::getPrefs();
        $arModulesPath = CopixConfig::instance ()->arModulesPath;

        foreach( $modules AS $mod_key=>$mod_val ) {
            foreach ($arModulesPath as $modulePath) {
                $class_file = $modulePath.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
                if( !file_exists( $class_file ) ) continue;

                $module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');

                $d = (isset($data[$mod_val->rep])) ? $data[$mod_val->rep] : null;

                $pref = $module_class->getPrefs( $d );

                $pref['code'] = $mod_val->rep;
                $prefs[] = $pref;
            }
        }

        //print_r($prefs);

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('prefs.moduleDescription'));
        $tpl->assign ('MAIN', CopixZone::process ('prefs|prefs', array('prefs'=>$prefs, 'msg'=>_request('msg') )));

        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>' );
        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="Expires" CONTENT="-1"/>' );
        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>' );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function setPrefs ()
    {
        if( 0 && Kernel::isDemo() ) return Kernel::noDemo();


        CopixHTMLHeader::addCSSLink (_resource("styles/module_prefs.css"));

        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>' );
        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="Expires" CONTENT="-1"/>' );
        CopixHTMLHeader::addOthers( '<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>' );

        $tpl = new CopixTpl ();

        // Liste des modules qui peuvent avoir des préférences...
        $modules = Prefs::getModules();
        $arModulesPath = CopixConfig::instance ()->arModulesPath;

        $datas = array();
        $errors = array();

        reset( $modules );
        foreach( $modules AS $mod_key=>$mod_val ) {
            foreach ($arModulesPath as $modulePath) {

                $class_file = $modulePath.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
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
                    $d = (isset($datas[$mod_val->rep])) ? $datas[$mod_val->rep] : null;
                    $error = $module_class->checkPrefs( $mod_val->rep, $d );
                    if( sizeof( $error ) ) $errors[$mod_val->rep] = $error;
                }
            }
        }
        if( sizeof( $errors ) ) {

            //print_r($errors);
//			$tplPrefs = new CopixTpl ();

            // Liste des modules disponibles...
            reset( $modules );
            foreach( $modules AS $mod_key=>$mod_val ) {
                foreach ($arModulesPath as $modulePath) {

                    $class_file = $modulePath.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
                    if( !file_exists( $class_file ) ) continue;

                    // Chargement de la classe...
                    $module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');

                    // Récupération de la structure des prefs...
                    $d = (isset($data[$mod_val->rep])) ? $data[$mod_val->rep] : null;
                    $pref = $module_class->getPrefs( $d );

                    $pref['code'] = $mod_val->rep;

                    if (isset($pref['form'])) { // Protection contre les modules aux prefs vides

                        foreach( $pref['form'] AS $key=>$val ) {
                            if( isset($val['code']) && isset($_POST[$pref['code'].'_'.$val['code']]) ) {
                                $pref['form'][$key]['value'] = $_POST[$pref['code'].'_'.$val['code']];
                            }
                            //print_r($val);
                            if( isset($val['code']) && isset($errors[$pref['code']][$val['code']]) ) {
                                $pref['form'][$key]['error'] = $errors[$pref['code']][$val['code']];
                            }

                        }
                    }
                    $prefs[] = $pref;
                }
            }

            $tpl->assign ('TITLE_PAGE', CopixI18N::get ('prefs.moduleDescription'));
            $tpl->assign ('MAIN', CopixZone::process ('prefs|prefs', array('prefs'=>$prefs)));

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        } else {

            reset( $modules );
            foreach( $modules AS $mod_key=>$mod_val ) {
                foreach ($arModulesPath as $modulePath) {

                    $class_file = $modulePath.$mod_val->rep.'/'.COPIX_CLASSES_DIR.'mod'.$mod_val->rep.'prefs.class.php';
                    if( !file_exists( $class_file ) ) continue;

                    $module_class = & CopixClassesFactory::Create ($mod_val->rep.'|mod'.$mod_val->rep.'prefs');

                    $d = (isset($datas[$mod_val->rep])) ? $datas[$mod_val->rep] : null;
                    $module_class->setPrefs( $mod_val->rep, $d );
                }
            }
        }

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('prefs|default|default', array('msg'=>'save') ) );
    }

}
