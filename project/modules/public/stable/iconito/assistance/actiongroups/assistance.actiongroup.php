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
		$animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");
		$grvilles_gr2ville_dao = & CopixDAOFactory::create("regroupements|grvilles_gr2ville");
		$grecoles_gr2ecole_dao = & CopixDAOFactory::create("regroupements|grecoles_gr2ecole");
		
		$ecoles_dao = & CopixDAOFactory::create("kernel|kernel_tree_eco");
		$personnels_dao = & CopixDAOFactory::create("kernel|kernel_bu_personnel");
		
		$animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
		// echo "<pre>"; print_r($animateur); die("</pre>");
		

		$regroupements_list = $animateurs2regroupements_dao->findByUser(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
		// echo "<pre>"; print_r($regroupements_list); die("</pre>");
		
		$users=array();
		
		// Pour chaque regroupement
		foreach($regroupements_list AS $regroupement_item) {
			
			// Si c'est un groupe de villes...
			if($regroupement_item->regroupement_type=='villes') {
				// Pour toutes les villes du grvilles
				$villes = $grvilles_gr2ville_dao->findByGroupe($regroupement_item->regroupement_id);
				foreach( $villes AS $ville ) {
					
					// Si on n'a jamais traité la ville (qui peut être dans plusieurs regroupements)
					if(!isset($users[$ville->id_ville])) {
						$users[$ville->id_ville] = array();
						
						// On cherche les ecoles de la ville (format DAO)
						$ecoles = $ecoles_dao->getByVille($ville->id_ville);
						// On traite la sortie du DAO pour avoir un array propre
						foreach( $ecoles AS $ecole ) {
							$users[$ville->id_ville][$ecole->eco_numero] = $ecole;
							$users[$ville->id_ville][$ecole->eco_numero]->personnels = $personnels_dao->getPersonnelInEcole($ecole->eco_numero);
						}
						
						// echo "<pre>"; print_r($users[$ville->id_ville]); echo("</pre>");
					}
				}
			}
			
			// Si c'est un groupe d'ecoles...
			if($regroupement_item->regroupement_type=='ecoles') {
				$ecoles = $grecoles_gr2ecole_dao->findByGroupe($regroupement_item->regroupement_id);
				// echo "<pre>"; print_r($ecoles); echo("</pre>");
				
				foreach( $ecoles AS $ecole ) {
					$ecole_info = $ecoles_dao->get($ecole->id_ecole);
					$ecole_info->personnels = $personnels_dao->getPersonnelInEcole($ecole->id_ecole);
					
					// echo "<pre>"; print_r($ecole_info); echo("</pre>");
					
					if(!isset($users[$ecole_info->vil_id_vi])) $users[$ecole_info->vil_id_vi] = array();
					$users[$ecole_info->vil_id_vi][$ecole_info->eco_numero] = $ecole_info;
					// echo "<pre>"; print_r($users); die("</pre>");
					
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
			$currentUserLogin = _currentUser()->getLogin();
			CopixSession::destroyNamespace('default');
			_sessionSet('user_animateur', $currentUserLogin);
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
?>
