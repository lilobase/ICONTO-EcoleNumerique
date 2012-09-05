<?php

/**
 * Actiongroup du module Aide
 *
 * @package Iconito
 * @subpackage	Aide
 */
class ActionGroupDefault extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }


    public function processDefault ()
    {
        //return _arRedirect (_url ('|viewHelp'));
        return CopixActionGroup::process ('aide|default::viewHelp');
    }

   /**
   * Affiche l'aide
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/04/12
   */
   public function processViewHelp ()
   {
        $rubrique = $this->getRequest ('rubrique', null);
        $page = $this->getRequest ('page', null);

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('aide|aide.description'));
        //$tpl->assign ('MENU', $menu);

        if ($rubrique) {

            $hasHelpRub = false;

            $arModulesPath = CopixConfig::instance ()->arModulesPath;
            foreach ($arModulesPath as $modulePath) {
                $file = $modulePath.$rubrique.'/'.COPIX_CLASSES_DIR.'help'.$rubrique.'.class.php';
                if (file_exists ($file)) {
                    $hasHelpRub = true;

                    $modhelp = & CopixClassesFactory::Create ($rubrique.'|help'.$rubrique);
                    if (method_exists($modhelp, 'getPages')) {
                        $pages = $modhelp->getPages();
                    }

                    if ($page && is_array($pages) && isset($pages[$page])) {	// Page précise
                        $tpl->assign ('TITLE_PAGE', Kernel::Code2Name('mod_'.$rubrique).' - '.$pages[$page]['title']);
                        $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('aide||', array('rubrique'=>$rubrique)).'">'.CopixI18N::get ('aide|aide.link.top').'</a> :: <a href="'.CopixUrl::get ('aide||').'">'.CopixI18N::get ('aide|aide.link.index').'</a>');

                        // On vérifie que le fichier existe bien
                        $lg = CopixI18N::getLang();
                        $file = $modulePath.$rubrique.'/'.COPIX_TEMPLATES_DIR.'help_'.$page.'_'.$lg.'.html';

                        if (file_exists ($file)) {

                            //$tpl->assignStatic ('text', $rubrique.'|help_'.$page.'_'.$lg.'.html');

                            $tpl2 = new CopixTpl ();
                            $text = $tpl2->fetch($rubrique.'|help_'.$page.'_'.$lg.'.html');

                            $tpl->assign ('text', $text);
                            $tpl->assign ('rubrique', $rubrique);
                            $tpl->assign ('pages', $pages);

                            $see = array();
                            if (isset($pages[$page]['links']) && is_array($pages[$page]['links'])) {
                                $links = $pages[$page]['links'];
                                //print_r($links);
                                foreach ($links as $link) {
                                    $l = explode ('|', $link);
                                    //print_r($l);
                                    if (count($l)==1) {	// Même module
                                        $see[] = array('rubrique'=>$rubrique, 'page'=>$l[0], 'title'=>$pages[$l[0]]['title']);
                                    } else {	// Autre module

                                        $arModulesPath2 = CopixConfig::instance ()->arModulesPath;
                                        foreach ($arModulesPath2 as $modulePath2) {
                                            $file = $modulePath2.$l[0].'/'.COPIX_CLASSES_DIR.'help'.$l[0].'.class.php';
                                            if (file_exists ($file)) {
                                                $modhelp2 = & CopixClassesFactory::Create ($l[0].'|help'.$l[0]);
                                                if (method_exists($modhelp2, 'getPages')) {
                                                    $pages2 = $modhelp2->getPages();
                                                    //print_r($pages2);
                                                    $see[] = array('rubrique'=>$l[0], 'page'=>$l[1], 'title'=>$pages2[$l[1]]['title']);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            $tpl->assign ('links', $see);
                        }
                        $MAIN = $tpl->fetch('viewhelprubpage.tpl');
                    } else { // Sommaire de l'aide du module
                        $tpl->assign ('TITLE_PAGE', Kernel::Code2Name('mod_'.$rubrique));
                        $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('aide||').'">'.CopixI18N::get ('aide|aide.link.index').'</a>');
                        $tpl->assign ('rubrique', $rubrique);
                        $tpl->assign ('pages', $pages);
                        $MAIN = $tpl->fetch('viewhelprub.tpl');
                    }
                }
            }
            if (!$hasHelpRub)
                $MAIN = $tpl->fetch('viewhelp.tpl');
        } else {
            $rubs = array('minimail', 'album');	// Compléter avec les modules dont l'aide est écrite

            $rubriques = array();
            foreach ($rubs as $rub) {
                $rubriques[] = array(
                    'name' => $rub,
                    'title' => Kernel::Code2Name('mod_'.$rub),
                );
            }
            $tpl->assign ('rubriques', $rubriques);
            $MAIN = $tpl->fetch('viewhelp.tpl');
        }

        $tpl->assign ('MAIN', $MAIN);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

}
