<?php
/**
 * @package standard
 * @subpackage auth
 * @author		Gérald Croës
 * @copyright	CopixTeam
 * @link		http://copix.org
 * @license		http://www.gnu.org/licenses/lgpl.html GNU General Lesser  Public Licence, see LICENCE file
 */

/**
 * OpÃ©rations de connexions / déconnexion
 * @package standard
 * @subpackage auth
 */
class ActionGroupLog extends enicActionGroup
{
    /**
     * Action par défaut.... logique
     */
    public function processDefault ()
    {
        return $this->processForm ();
    }

    /**
     * Login
     */
    public function processIn ()
    {
            //delete chartValid;
            /*
             * PATCH FOR CHARTE
             */
            $_SESSION['chartValid'] = false;

        CopixRequest::assert ('login', 'password');
        $noCredential = _request ('noCredential', false);
        $ssoIn = _request ('sso_in', false);

        $config = CopixConfig::instance();
        if ($noCredential && count ($config->copixauth_getRegisteredUserHandlers()) > 1 && CopixConfig::get('auth|multipleConnectionHandler')) {
            $connected = CopixAuth::getCurrentUser ()->login (array ('login'=>CopixRequest::get ('login'),
                                                                     'password'=>CopixRequest::get ('password'),
                                                                     'append'=>true));
        } else {
            $connected = CopixAuth::getCurrentUser ()->login (array ('login'=>CopixRequest::get ('login'),
                                                                     'password'=>CopixRequest::get ('password'),
                                                                                                                             'ssoIn'=>$ssoIn));
        }
        if ($connected){
            //insert token for remember_me plugin
            $response = CopixAuth::getCurrentUser()->getResponses();
            foreach ($response as $key=>$r){
                if(($r instanceof CopixUserLogResponse) && $r->getResult ()){
                    $handlername = $key;
                }
            }

            CopixEventNotifier::notify ('login', array ('login'=>CopixRequest::get ('login')));
            if (CopixConfig::get('auth|authorizeRedirectIfOK')) {
                $urlReturn = CopixRequest::get ('auth_url_return', _url ('log|'));
            } else {
                $urlReturn = _url ('log|');
            }
            Logs::set( array('type'=>'LOG', 'message'=>'Login ok: '.CopixRequest::get ('login')) );
            //die ($urlReturn);

                        /*
                         * PATCH FOR CHARTE
                         */
                        $this->user->forceReload();
                        if(!$this->service('charte|CharteService')->checkUserValidation()){
                            $this->flash->redirect = $urlReturn;
                            return $this->go('charte|charte|valid');
                        }
            return _arRedirect ($urlReturn);
        }
        if (CopixConfig::get('auth|authorizeRedirectIfNoK')) {
            $urlReturn = CopixRequest::get ('auth_failed_url_return', _url ('log|', array ('failed'=>1, 'auth_url_return'=>CopixRequest::get ('auth_url_return'))));
        } else {
            $urlReturn = _url ('log|', array ('failed'=>1, 'auth_url_return'=>CopixRequest::get ('auth_url_return')));
        }

        Logs::set( array('type'=>'LOG', 'message'=>'Login failed: '.CopixRequest::get ('login').'/'.CopixRequest::get ('password')) );

        return _arRedirect ($urlReturn);
    }

    /**
     * Logout
     */
    public function processOut ()
    {
        Logs::set( array('type'=>'LOG', 'message'=>'Logout: '._currentUser()->getLogin()) );
        CopixAuth::getCurrentUser ()->logout (array ());
        CopixEventNotifier::notify ('logout', array ('login'=>CopixAuth::getCurrentUser()->getLogin ()));
        CopixAuth::destroyCurrentUser ();
        CopixSession::destroyNamespace('default');
        return _arRedirect (CopixRequest::get ('auth_url_return', _url ('||')));
    }

    /**
     * Ecran de connexion
     */
    public function processForm ()
    {
        $ppo = new CopixPPO ();
        $ppo->TITLE_PAGE = _i18n ('auth.connect');
        if (CopixAuth::getCurrentUser ()->isConnected ()){
            $ppo->user = CopixAuth::getCurrentUser ();
            return _arRedirect (_url ('kernel||getHome'));
        }

        $config = CopixConfig::instance();
        if (count ($config->copixauth_getRegisteredUserHandlers()) > 1 && CopixConfig::get('auth|multipleConnectionHandler')) {
            $ppo->noCredential = true;
        }

        $ppo->auth_url_return = CopixRequest::get ('auth_url_return', _url ('#'));
        $ppo->failed = array ();
        if (CopixRequest::getInt ('noCredential', 0)){
            $ppo->failed[] = _i18n ('auth.error.noCredentials');
        }
        if (CopixRequest::getInt ('failed', 0)){
            $ppo->failed[] = _i18n ('auth.error.failedLogin');
        }

        $ppo->createUser = Copixconfig::get('auth|createUser');
        return _arPPO ($ppo, 'login.form.php');
    }
}
