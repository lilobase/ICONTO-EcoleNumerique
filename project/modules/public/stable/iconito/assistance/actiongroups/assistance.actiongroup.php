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

class ActionGroupAssistance extends CopixActionGroup {
	
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}
	
	
	function getAssistance () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Assistance");
		
		$tplAssistance = & new CopixTpl ();
		// $tplAssistance->assign('users', $users);
		$result = $tplAssistance->fetch("default.tpl");
		$tpl->assign ('MAIN', $result );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function getUsers () {
		
		$tpl = & new CopixTpl ();
		
		$animateur_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
		$animateurs2grville_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2grville");
		$ecoles_dao = & CopixDAOFactory::create("kernel|kernel_tree_eco");
		$personnels_dao = & CopixDAOFactory::create("kernel|kernel_bu_personnel");
		
		$animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));

		
		// echo "<pre>"; print_r($animateur); die("</pre>");

		$grville_list = $animateurs2grville_dao->findByUser(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
		$users=array();
		foreach($grville_list AS $grville_item) {
			if(!isset($users[$grville_item->id_ville])) {
				$users[$grville_item->id_ville] = $ecoles_dao->getByVille($grville_item->id_ville);
				foreach($users[$grville_item->id_ville] AS $ecole_key => $ecole_val ) {
					$users[$grville_item->id_ville][$ecole_key]->personnels = $personnels_dao->getPersonnelInEcole($ecole_val->eco_numero);
				}
				
			}
		}
		
		// echo "<pre>"; print_r($users); die("</pre>");
		
		$tplUsers = & new CopixTpl ();
		$tplUsers->assign('users', $users);
		$result = $tplUsers->fetch("users-list.tpl");
		
		// echo "<pre>"; print_r($users); die("</pre>");
		
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
	
	
	function getSwitchUser () {
		$login = _request('login');
		
		if( $login!='' ) {
		
			_sessionSet('user_animateur', _currentUser()->getLogin());
			//var_dump(_currentUser());
			_currentUser()->login(array('login'=>$login, 'assistance'=>true));
			
			// echo "<pre>"; print_r($_SESSION); die("</pre>");
			$url_return = CopixUrl::get ('kernel||doSelectHome');
		} else {
			if ($session = _sessionGet('user_animateur')) {
				//var_dump($session);
				_sessionSet('user_animateur', null);
				_currentUser()->login(array('login'=>$session, 'assistance'=>true));
			}
			
			$url_return = CopixUrl::get ('assistance||users');
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
	}
	
	
	
}
?>
