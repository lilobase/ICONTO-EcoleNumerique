<?php
/**
 * Assistance - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Assistance
 * @version     $Id: assistance.actiongroup.php,v 1.1 2009-09-30 10:06:20 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupAssistance extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }


    public function getAssistance ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get('assistance.moduleDescription'));

        $me_info = Kernel::getUserInfo( "ME", 0 );
        $animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
        $animateur = $animateurs_dao->get( $me_info['type'], $me_info['id'] );

        $tplAssistance = new CopixTpl ();
        $tplAssistance->assign('animateur', $animateur);
        $result = $tplAssistance->fetch("default.tpl");
        $tpl->assign ('MAIN', $result );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function getUsers ()
    {
        $tpl = new CopixTpl ();
        $tplUsers = new CopixTpl ();

        $me_info = Kernel::getUserInfo( "ME", 0 );

        $animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
        $animateur = $animateurs_dao->get( $me_info['type'], $me_info['id'] );
        $tplUsers->assign('animateur', $animateur);

        $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        $ien = $ien_dao->get( $me_info['type'], $me_info['id'] );
        $tplUsers->assign('ien', $ien);

        $assistance_service = & CopixClassesFactory::Create ('assistance|assistance');
        $users=$assistance_service->getAssistanceUsers();

        $tplUsers->assign('users', $users);
        $result = $tplUsers->fetch("users-list.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('assistance.moduleDescription')." &raquo; ".CopixI18N::get ('assistance.title.users'));
        $tpl->assign ('MAIN', $result );

        // echo "<pre>"; print_r($_SESSION); die("</pre>");

        /*
        $menu=array();
        $menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getnode'), 'url' => CopixUrl::get ('comptes||getNode') );
        $tpl->assign ('MENU', $menu );
        */

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    public function getSwitchUser ()
    {
        $login = _request('login');

        if( $login!='' ) {

            $me_info = Kernel::getUserInfo( "ME", 0 );
            $animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
            $animateur = $animateurs_dao->get( $me_info['type'], $me_info['id'] );

            $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
            $ien = $ien_dao->get( $me_info['type'], $me_info['id'] );

            // echo "<pre>"; print_r($ien); die("</pre>");

            if( ! $ien && (! $animateur || !isset($animateur->can_connect) || !$animateur->can_connect) )
                return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('assistance||users', array('error'=>'forbidden') ));

            $user_info = Kernel::getUserInfo( "LOGIN", $login );
            // $user_info->user_id

            if(!$user_info) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('assistance||users', array('error'=>'forbidden') ));

            $ok = false;


            $assistance_service = & CopixClassesFactory::Create ('assistance|assistance');
            $user_assistance = $assistance_service->getAssistanceUsers();

            if(!$user_assistance) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('assistance||users', array('error'=>'forbidden') ));

            foreach($user_assistance AS $ville_id => $ville) foreach($ville AS $ecole_id => $ecole) foreach($ecole->personnels AS $personnel_id => $personnel) {
                if( $personnel->id_copix == $user_info['user_id'] ) $ok = $personnel->assistance;
            }

            if(!$ok) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('assistance||users', array('error'=>'forbidden') ));

            $currentUserLogin = _currentUser()->getLogin();
            CopixSession::destroyNamespace('default');
            _sessionSet('user_animateur', $currentUserLogin);
            _sessionSet('prisedecontrole_ien', ($ien?true:false));
            _currentUser()->login(array('login'=>$login, 'assistance'=>true));
            $url_return = CopixUrl::get ('kernel||doSelectHome');
        } else {
            if ($session = _sessionGet('user_animateur')) {
                CopixSession::destroyNamespace('default');
                //_sessionSet('user_animateur', null);
                _currentUser()->login(array('login'=>$session, 'assistance'=>true));
            }
            $url_return = CopixUrl::get ('assistance||users');
        }
        return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
    }


}
