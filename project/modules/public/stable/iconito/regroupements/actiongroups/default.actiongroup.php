<?php
/**
 * Regroupements - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Regroupements
 * @version     $Id$
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupDefault extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        CopixHTMLHeader::addCSSLink (_resource("styles/module_regroupements.css"));
    }



   public function getHomePage ()
   {
           if(!Kernel::isAdmin())
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        $tpl = new CopixTpl ();
        $tplRegroupements = new CopixTpl ();

        // CopixHTMLHeader::addCSSLink (_resource("styles/module_grvilles.css"));

        // $tpl->assign ('TITLE_PAGE', CopixI18N::get ('grvilles|grvilles.module.titre'));

        $dao_grvilles = CopixDAOFactory::create("regroupements|grvilles");
        $grvilles = $dao_grvilles->findAll();
        $tplRegroupements->assign ( 'GRVILLES', count($grvilles) );

        $dao_grecoles = CopixDAOFactory::create("regroupements|grecoles");
        $grecoles = $dao_grecoles->findAll();
        $tplRegroupements->assign ( 'GRECOLES', count($grecoles) );

        $main = $tplRegroupements->fetch ('default.tpl');

        $tpl->assign ( 'MAIN', $main );


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

}
