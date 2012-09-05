<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: stats.actiongroup.php,v 1.3 2007-03-22 15:26:18 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2007 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|statsservices');
_classInclude('sysutils|admin');

class ActionGroupStats extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }

    /**
     * Accueil des stats
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/03/19
     */
    public function home ()
    {
        if (!Admin::canAdmin())
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));



        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sysutils|admin.menu.stats'));
        $tpl->assign ('MENU', Admin::getMenu('stats'));

        $tplStats = new CopixTpl();

        $modules = Kernel::getAllModules();

        $tab = array();

        foreach( $modules as $mod_val ) {

            $arModulesPath = CopixConfig::instance ()->arModulesPath;
            foreach ($arModulesPath as $modulePath) {
                $class_file = $modulePath.$mod_val.'/'.COPIX_CLASSES_DIR.'kernel'.$mod_val.'.class.php';
                if( !file_exists( $class_file ) ) continue;

                $module_class = & CopixClassesFactory::Create ($mod_val.'|Kernel'.$mod_val);
                //print_r($module_class);
                if (!is_callable(array($module_class, 'getStatsRoot')))
                    continue;

                //$classeModule = CopixClassesFactory::create("$label|Kernel$label");
                $tab[$mod_val]['module_nom'] = Kernel::Code2Name ('mod_'.$mod_val);
                $tab[$mod_val]['stats'] = $module_class->getStatsRoot();

            }
        }

        //print_r($tab);

        $tplStats->assign ('tab', $tab);

        $tpl->assign ('MAIN', $tplStats->fetch('sysutils|stats.modules.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

}
