<?php
/**
 * @package standard
 * @subpackage auth 
 * 
 * @author		Frederic Mossmann
 * @copyright	CAP-TIC
 * @link		http://www.cap-tic.fr
 * @license		http://www.gnu.org/licenses/lgpl.html GNU General Lesser  Public Licence, see LICENCE file
 */

/**
 * Actiongroup contenant la parti administration des userhandler, grouphandler et credentialhandler
 * @package standard
 * @subpackage auth
 */
class ActionGroupCas extends EnicActionGroup {
	
	public function processLogin (){
		include_once (COPIX_UTILS_PATH.'../../CAS-1.2.2/CAS.php');
		
                $_SESSION['chartValid'] = false;
                
		$ppo = new CopixPPO ();
		$ppo->user = _currentUser();
		if($ppo->user->isConnected()){
			$url_return = CopixUrl::get ('kernel||doSelectHome');
                        /*
                         * PATCH FOR CHARTE
                         */
                        $this->user->forceReload();
                        if(!$this->service('charte|CharteService')->checkUserValidation()){
                            $this->flash->redirect = $urlReturn;
                            return $this->go('charte|charte|valid');
                        }
			return _arRedirect ($urlReturn);
			//return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
		} else {
			
			$conf_Cas_host = CopixConfig::get ('default|conf_Cas_host');
			$conf_Cas_port = CopixConfig::get ('default|conf_Cas_port');
			$conf_Cas_path = CopixConfig::get ('default|conf_Cas_path');
			
			phpCAS::client(CAS_VERSION_2_0,$conf_Cas_host,(int)$conf_Cas_port,$conf_Cas_path, false);
			phpCAS::setNoCasServerValidation();
			phpCAS::forceAuthentication();
			$ppo->cas_user = phpCAS::getUser();
			if($ppo->cas_user) {

				$ppo->iconito_user = Kernel::getUserInfo( "LOGIN", $ppo->cas_user );
				
				if($ppo->iconito_user['login']) {
					_currentUser()->login(array('login'=>$ppo->iconito_user['login'], 'assistance'=>true));
					$url_return = CopixUrl::get ('kernel||doSelectHome');
					// $url_return = CopixUrl::get ('assistance||users');
                                        
                                        

					return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
				} else {
					$ppo->cas_error = 'no-iconito-user';
					return _arPpo ($ppo, 'cas.tpl');
				}
			}
		}
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = $pTitle;
		
		
		phpCAS::setDebug();
		
		$conf_Cas_host = CopixConfig::get ('default|conf_Cas_host');
		$conf_Cas_port = CopixConfig::get ('default|conf_Cas_port');
		$conf_Cas_path = CopixConfig::get ('default|conf_Cas_path');
		
		phpCAS::client(CAS_VERSION_2_0,$conf_Cas_host,(int)$conf_Cas_port,$conf_Cas_path, false);
	
		phpCAS::setNoCasServerValidation();
		phpCAS::forceAuthentication();
		if (isset($_REQUEST['logout'])) {
			phpCAS::logout();
		}
		die(phpCAS::getUser());

		die('ok');
		return _arPpo ($ppo, 'handlers.list.tpl');
	}
	
	public function processLogout (){
		include_once (COPIX_UTILS_PATH.'../../CAS-1.2.2/CAS.php');
		
		$ppo = new CopixPPO ();
		$ppo->user = _currentUser();
		if($ppo->user->isConnected()){
			CopixAuth::getCurrentUser ()->logout (array ());
			CopixEventNotifier::notify ('logout', array ('login'=>CopixAuth::getCurrentUser()->getLogin ()));
			CopixAuth::destroyCurrentUser ();
			CopixSession::destroyNamespace('default');
		}

		phpCAS::setDebug();
		
		$conf_Cas_host = CopixConfig::get ('default|conf_Cas_host');
		$conf_Cas_port = CopixConfig::get ('default|conf_Cas_port');
		$conf_Cas_path = CopixConfig::get ('default|conf_Cas_path');
		
		phpCAS::client(CAS_VERSION_2_0,$conf_Cas_host,(int)$conf_Cas_port,$conf_Cas_path, false);
		
		phpCAS::setNoCasServerValidation();
		phpCAS::forceAuthentication();
		phpCAS::logout();
		
		return _arRedirect (CopixRequest::get ('auth_url_return', _url ('||')));
		
	}
}
?>
