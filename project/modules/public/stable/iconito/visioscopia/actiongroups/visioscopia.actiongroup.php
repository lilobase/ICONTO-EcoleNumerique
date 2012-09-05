<?php
/**
 * Actiongroup du module VisioScopia
 *
 * @package	Iconito
 * @subpackage	VisioScopia
 */

class ActionGroupVisioScopia extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        CopixHTMLHeader::addCSSLink (_resource("styles/module_visioscopia.css"));
    }


    public function getVisioScopia ()
    {
        $dao = CopixDAOFactory::create("visioscopia|visioscopia_config");

        $id = $this->getRequest ('id', null);
        $user_infos = Kernel::getUserInfo();

        $conf_result = $dao->get($id);

        $title = "Visioconf&eacute;rence";
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);
        $tplVisio = new CopixTpl ();

        $save = $this->getRequest ('save', 0);
        if( $save == 1 ) {
            $conf_id     = $this->getRequest ('conf_id'    , 0 );
            $conf_msg    = $this->getRequest ('conf_msg'   , '');
            $conf_active = $this->getRequest ('conf_active', 0 );

            if( 1 ) { // test de validité ?

                if(!$conf_result)
                    $conf_result = _record("visioscopia|visioscopia_config");


                $conf_result->id = (int)$id;
                $conf_result->conf_id     = $conf_id;
                $conf_result->conf_msg    = $conf_msg;
                $conf_result->conf_active = $conf_active;
                //_dump($conf_result);

                $dao->delete($id);
                $dao->insert($conf_result);

                $tplVisio->assign ('saved', 1);
            }
        }

        if( $conf_result ) {

            if( CopixConfig::exists('visioscopia|conf_ModVisioScopia_url') ) {
                $tplVisio->assign ('config_ok', 1);
                $url = CopixConfig::get('visioscopia|conf_ModVisioScopia_url');

            } else {
                $tplVisio->assign ('config_ok', 0);
            }

            // $url = CopixConfig::get('visioscopia|url');

            $patterns[0] = '/%ROOM%/';
            $patterns[1] = '/%NAME%/';
            $replacements[0] = $conf_result->conf_id;
            $replacements[1] = urlencode(trim($user_infos['prenom']." ".$user_infos['nom']));
            $url = preg_replace($patterns, $replacements, $url);

            $tplVisio->assign ('url', $url);
        } else {
            $tplVisio->assign ('config_ok', 0);
        }

        $tplVisio->assign ('visio_id', $id);
        // _dump($conf_result);
        $tplVisio->assign ('config', $conf_result);

        $result = $tplVisio->fetch('visioscopia-user.tpl');

        // echo Kernel::getLevel( "MOD_VISIOSCOPIA", $id );

        if( Kernel::getLevel( "MOD_VISIOSCOPIA", $id ) >= PROFILE_CCV_ADMIN ) {
            $result .= $tplVisio->fetch('visioscopia-admin.tpl');
        } else {
        }

        $menu = array();
        $tpl->assign ('MENU', $menu );

        $tpl->assign ('MAIN', $result);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

}



