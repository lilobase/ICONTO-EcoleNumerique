<?php
/**
 * Actiongroup du module Visio
 * 
 * @package	Iconito
 * @subpackage	Visio
 */
 
class ActionGroupDefault extends enicActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
		CopixHTMLHeader::addCSSLink (_resource("styles/module_visio.css"));
	}

	
	function processDefault () {
		$ppo = new CopixPPO();
		$ppo->error = "";
		
		$user_from = Kernel::getUserInfo("ME", 0);
		$ppo->login_to = $this->request('login', 'str');
		$ppo->login_from = $user_from['login'];
		$ppo->red5 = 'rtmp://sandbox.gribin.net/oflaDemo';
		
		if($ppo->login_to) {
			$user_to = Kernel::getUserInfo("LOGIN", $ppo->login_to);
			if( $user_to ) {
				return _arPPO($ppo, 'visio.tpl');
			} else {
				$ppo->error = "Login inconnu";
			}
		}
		return _arPPO($ppo, 'default.tpl');
	}

}



?>
