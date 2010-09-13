<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: animateurs.actiongroup.php,v 1.1 2009-08-31 10:00:17 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2009 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupAnimateurs extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}

	/**
	 * list
	 * 
	 * Affiche le formulaire de modification d'un utilisateur extérieur
	 * 
	 * @package	Comptes
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getList() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplAnimateurs = & new CopixTpl ();
		
		$animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
		$animateurs = $animateurs_dao->findAll();
		$ppo->animateurs = array();
		
		foreach( $animateurs AS $animateur ) {
			$animateur->user_infos = Kernel::getUserInfo( $animateur->user_type, $animateur->user_id );
			$animateur->regroupements = new CopixPPO();
			$animateur->regroupements->grvilles = array();
			$animateur->regroupements->grecoles = array();
			$ppo->animateurs[$animateur->user_type."-".$animateur->user_id] = $animateur;
		}

		$animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");
		$animateurs2regroupements = $animateurs2regroupements_dao->findAll();

		// echo "<pre>"; print_r($animateurs2regroupements); die("</pre>");
		
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$grvilles = $comptes_service->getGrvillesList();
		$grecoles = $comptes_service->getGrecolesList();
		
		$ppo->grvilles = array();
		foreach( $grvilles AS $grville ) {
			$ppo->grvilles[$grville->id] = $grville;
		}

		$ppo->grecoles = array();
		foreach( $grecoles AS $grecole ) {
			$ppo->grecoles[$grecole->id] = $grecole;
		}
		
		foreach( $animateurs2regroupements AS $anim2gr) {
			if($anim2gr->regroupement_type=='ecoles')
				$ppo->animateurs[$anim2gr->user_type."-".$anim2gr->user_id]->regroupements->grecoles[$anim2gr->regroupement_id] = $ppo->grecoles[$anim2gr->regroupement_id];
			if($anim2gr->regroupement_type=='villes')
				$ppo->animateurs[$anim2gr->user_type."-".$anim2gr->user_id]->regroupements->grvilles[$anim2gr->regroupement_id] = $ppo->grvilles[$anim2gr->regroupement_id];
		}
		
		// echo "<pre>"; print_r($ppo->animateurs); die("</pre>");
		
		// $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		// $ppo->grvilles = $comptes_service->getGrvillesList();
		
		
		/*
		echo "<pre>";
		print_r($ppo->animateurs);
		print_r($ppo->grvilles);
		die();
		*/
		
		$tplAnimateurs->assign('ppo', $ppo);
		$result = $tplAnimateurs->fetch("animateurs-list.tpl");
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.animateur_list'));
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getnode'), 'url' => CopixUrl::get ('comptes||getNode'),       'size'=>180 );
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.new_animateur'),  'url' => CopixUrl::get ('comptes|animateurs|new'), 'size'=>160, 'type'=>'create' );
		
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}





	function getEdit() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplAnimateurs = & new CopixTpl ();

		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
		$animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");
	
		$pUserType = _request('user_type');
		$pUserId = _request('user_id');
		$pSave = _request('save');
		
		$user = Kernel::getUserInfo();

		$ppo->pouvoirs = array(
			array('id'=>'can_connect',        'nom'=>'Se connecter en tant qu\'un enseignant/directeur' ),
			array('id'=>'can_tableaubord',    'nom'=>'Tableau de bord des usages'),
			array('id'=>'can_comptes',        'nom'=>'Administration des comptes d\'acc&egrave;s'),
		);

		$grvilles = $comptes_service->getGrvillesList();
		$ppo->grvilles = array();
		foreach( $grvilles AS $grville ) {
			$ppo->grvilles[$grville->id] = $grville;
		}
		
		$grecoles = $comptes_service->getGrecolesList();
		$ppo->grecoles = array();
		foreach( $grecoles AS $grecole ) {
			$ppo->grecoles[$grecole->id] = $grecole;
		}
		
		if( !$pUserType || !$pUserId ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|animateurs|list'));
		}
		
		$ppo->animateur = $animateurs_dao->get($pUserType, $pUserId);
		
		$new = false;
		if(!$ppo->animateur) {
			$ppo->animateur = _record("kernel|kernel_animateurs");
			
			$ppo->animateur->user_type = $pUserType;
			$ppo->animateur->user_id = $pUserId;
			$ppo->animateur->can_connect = 0;
			$ppo->animateur->can_tableaubord = 0; 
			$ppo->animateur->can_comptes = 0; 
			$ppo->animateur->is_visibleannuaire = 0; 
			$ppo->animateur->updated_at = date("Y-m-d H:i:s");
			$ppo->animateur->updated_by = $user['login'];
    		$new=true;
			// return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|animateurs|list'));
		}
		
		if($pSave==1) {
			
			foreach( $ppo->pouvoirs AS $pouvoir ) {
				$ppo->animateur->$pouvoir['id'] = _request('pouvoir_'.$pouvoir['id'])*1;
			}
			
			$ppo->animateur->is_visibleannuaire = _request('annuaire')*1;
			
			$ppo->animateur->updated_at = date("Y-m-d H:i:s");
			$ppo->animateur->updated_by = $user['login'];

			if($new) $animateurs_dao->insert($ppo->animateur);
			         $animateurs_dao->update($ppo->animateur);
			
			$animateurs2regroupements_dao->deleteByUser($pUserType, $pUserId);
			
			$animateurs2regroupements  = _record("kernel|kernel_animateurs2regroupements");
			
			
			$animateurs2regroupements->user_type = $pUserType;
			$animateurs2regroupements->user_id   = $pUserId;
			
			$animateurs2regroupements->regroupement_type   = 'villes';
			foreach( $ppo->grvilles AS $grville ) {
				if( _request('groupe_villes_'.$grville->id)==1 ) {
					$animateurs2regroupements->regroupement_id = $grville->id;
					_dao("kernel|kernel_animateurs2regroupements")->insert($animateurs2regroupements);
				}
			}
			
			$animateurs2regroupements->regroupement_type   = 'ecoles';
			foreach( $ppo->grecoles AS $grecole ) {
				if( _request('groupe_ecoles_'.$grecole->id)==1 ) {
					$animateurs2regroupements->regroupement_id = $grecole->id;
					_dao("kernel|kernel_animateurs2regroupements")->insert($animateurs2regroupements);
				}
			}
			
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|animateurs|list' ) );
		}
		
		$ppo->animateur->user_infos = Kernel::getUserInfo( $pUserType, $pUserId );
		
		$animateur_regroupements = $animateurs2regroupements_dao->findByUser($pUserType, $pUserId);
		$ppo->animateur_grville = array();
		foreach( $animateur_regroupements AS $animateur_regroupement ) {
			if($animateur_regroupement->regroupement_type=='villes')
				$ppo->animateur_grville[$animateur_regroupement->regroupement_id] = 1;
			if($animateur_regroupement->regroupement_type=='ecoles')
				$ppo->animateur_grecole[$animateur_regroupement->regroupement_id] = 1;
				
		}
		
		$tplAnimateurs->assign('ppo', $ppo);
		$result = $tplAnimateurs->fetch("animateurs-edit.tpl");
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.animateur_edit'));
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getnode'), 'url' => CopixUrl::get ('comptes||getNode'), 'size'=>180 );
		$menu[] = array( 'txt' => 'Liste des animateurs', 'url' => CopixUrl::get ('comptes|animateurs|list'), 'size'=>115 );
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
		
	}
	
	
	function getNew() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplAnimateurs = & new CopixTpl ();
		
		$animateurs_dao = _dao("kernel|kernel_animateurs");
		$animateurs = $animateurs_dao->findAll();
		$ppo->animateurs = array();
		
		foreach( $animateurs AS $animateur ) {
			$ppo->animateurs[$animateur->user_type."-".$animateur->user_id] = $animateur;
		}
		
		$userext_dao = _dao("kernel|kernel_ext_user");
		$list = $userext_dao->listUsers();
		$user_key = 0;
		foreach( $list AS $user_val ) {
			$ppo->userext[$user_key] = $user_val;
			$user_key++;
		}
		foreach( $ppo->userext AS $user_key => $user_val ) {
			if( isset($ppo->animateurs["USER_EXT-".$user_val->ext_id]) ) {
				// Si la personne est déjà animateur
				unset($ppo->userext[$user_key]);
			} else {
				$ppo->userext[$user_key]->user_infos = Kernel::getUserInfo( 'USER_EXT', $user_val->ext_id );
				if( !isset($ppo->userext[$user_key]->user_infos['login']) ) {
					// Si la personne n'a pas de login de type enseignant
					unset($ppo->userext[$user_key]);
				}
			}
		}
		
		$userens_dao = _dao("kernel|kernel_bu_personnel");
		$list = $userens_dao->listUsers();
		$user_key = 0;
		$ppo->userens = array();
		foreach( $list AS $user_val ) {
			$ppo->userens[$user_key] = $user_val;
			$user_key++;
		}
		foreach( $ppo->userens AS $user_key => $user_val ) {
			if( isset($ppo->animateurs["USER_ENS-".$user_val->pers_numero]) ) {
				// Si la personne est déjà animateur
				unset($ppo->userens[$user_key]);
			} else {
				$ppo->userens[$user_key]->user_infos = Kernel::getUserInfo( 'USER_ENS', $user_val->pers_numero );
				if( !isset($ppo->userens[$user_key]->user_infos['login']) ) {
					// Si la personne n'a pas de login de type enseignant
					unset($ppo->userens[$user_key]);
				}
			}
		}
		
		
		
		/*
		echo "<pre>";
		// print_r($ppo->animateurs);
		print_r($ppo->userext);
		// print_r($ppo->userens);
		die();
		*/
		
		$tplAnimateurs->assign('ppo', $ppo);
		$result = $tplAnimateurs->fetch("animateurs-new.tpl");
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.animateur_list'));
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getnode'), 'url' => CopixUrl::get ('comptes||getNode'), 'size'=>180 );
		$menu[] = array( 'txt' => "Liste des animateurs", 'url' => CopixUrl::get ('comptes|animateurs|list'), 'size'=>115 );
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
	
	function getDelete() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		$pUserType = _request('user_type');
		$pUserId = _request('user_id');
			
		if( !$pUserType || !$pUserId ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|animateurs|list'));
		}

		$animateurs_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
		// $animateurs2grville_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2grville");
		$animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");
		
		$animateurs_dao->delete($pUserType, $pUserId);
		// $animateurs2grville_dao->deleteByUser($pUserType, $pUserId);
		$animateurs2regroupements_dao->deleteByUser($pUserType, $pUserId);
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|animateurs|list'));
	}	
}

?>
