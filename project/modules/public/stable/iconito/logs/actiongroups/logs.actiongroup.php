<?php
/**
 * Logs - ActionGroup
 *
 * Fonctions d'enregistrement et de recherche d'evenements.
 * @package	Iconito
 * @subpackage	Logs
 * @version   $Id: logs.actiongroup.php,v 1.3 2006-05-11 10:09:41 fmossmann Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

_classInclude('logs|logsolesservice');
require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ActionGroupLogs extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }


   public function display ()
   {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Logs");
        // $tpl->assign ('MENU', '');

        $dao = CopixDAOFactory::create("logs|logs");

        $data = $dao->getAll();
        $tplData = new CopixTpl ();
        $tplData->assign ('data', $data);
        $result = $tplData->fetch('action_display.tpl');
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function display_details ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Logs :: Détails");
        $tpl->assign ('MENU', array(
            array( 'url'=>'url', 'titre'=>'titre' ),
        ) );

        $dao = CopixDAOFactory::create("logs|logs");

        $data = $dao->get( _request('id') );
        $tplData = new CopixTpl ();
        $tplData->assign ('data', $data);
        $result = $tplData->fetch('action_display_details.tpl');
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

   public function test ()
   {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', "Logs");

        $dao = CopixDAOFactory::create("logs|logs");
        $data = $dao->lastLogin('admin');
        $tpl->assign ('MAIN', '<pre>'.print_r($data,true).'</pre>' );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

}
