<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: compte.actiongroup.php 64 2009-08-18 15:56:50Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

_classInclude ('kernel|sso');


class ActionGroupCompte extends CopixActionGroup {

	/**
	 * Verifie les droits
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/30
	 */
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[Admin]');
	}



	/**
	 * Liste des comptes
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/30
	 */
	public function processDefault () {
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Comptes d'acc&egrave;s";
		
		// Menu
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'kernel', 'level_1' => 'comptes'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('kernel||'), 'Administration');
 		$cBc->addItem (null, 'Comptes d\'acc&egrave;s');
		
		/*
		$params = array();
		foreach (CopixConfig::instance ()->copixauth_getRegisteredUserHandlers() as $handlerInformations){
			$arUsers[$handlerInformations['name']] = CopixUserHandlerFactory::create ($handlerInformations['name'])->find ($params);
		}
		*/
		//print_r($arUsers);
		//$ppo->list = $arUsers['auth|dbuserhandler'];
		$criteres = _daoSp ()->orderBy ('login_dbuser');
		$ppo->list = _ioDAO ('dbuser')->findBy ($criteres);
		
		$ppo->login = _currentUser()->getLogin();
		
		return _arPPO ($ppo, 'compte-default.tpl');
	}
	
	/**
	 * Formulaire de saisie/modif d'un compte
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/30
	 */
	public function processForm () {
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Compte";
		
		$id_dbuser = CopixRequest::getInt('id_dbuser');
		$submit = CopixRequest::getInt('submit');
		
		if ($id_dbuser) {
		  $userDAO = _ioDAO ('dbuser');
		  if (!$rUser = $userDAO->get ($id_dbuser)) {
				$id_dbuser = null;
			} else {
				$ppo->rForm = $rUser;
			}
		}
		
		//print_r($ppo->rForm);
		
		if ($submit) {
			$record = _record ('dbuser');
			$this->_parametersToObject ('dbuser', $record);
			//print_r($record);
			$check = array();
			
			//on verifie si le login n'est pas deja pris
			$sp = _daoSp ()->addCondition ('login_dbuser', '=', $record->login_dbuser);
			if ($record->id_dbuser){
				//l'utilisateur existe deja, on demande a verifier l'unicite du login pour l'utilisateur courant
				$sp->addCondition ('id_dbuser', '<>', $record->id_dbuser);
			}
			if (!preg_match('/^[a-z0-9]+$/',$record->login_dbuser))
				$check[] = 'Login incorrect';
			elseif (count (_ioDAO ('dbuser')->findBy ($sp))) {
				$check[] = 'Login d&eacute;j&agrave; pris';
			}	
			
			//on verifie si un mot de passe est donne qu'ils soient bien identiques
			if ($record->password1) {
				if ($record->password1 != $record->password2) {
					$check[] = 'Mots de passe diff&eacute;rents';
				}else{
					$record->password_dbuser = md5 ($record->password1);
				}			
			} else {
				//si c'est un nouvel utilisateur, il est obligatoire de saisir un nouveau mot de passe.
				if (!$record->id_dbuser){
					$check[] = 'Mot de passe obligatoire';
				} else
					$record->password_dbuser = $rUser->password_dbuser;
			}
			
			if (!$record->email_dbuser){
				$check[] = 'Email obligatoire';
			}
			
			if (!$record->type_dbuser){
				$check[] = 'Type obligatoire';
			}
			
			//print_r($check);
			if (!count($check)) {
				if ($record->id_dbuser) { // Mise a jour
					$record->cle_privee = $rUser->cle_privee;
					$record->challenge = $rUser->challenge;
					$record->challenge_date = $rUser->challenge_date;
					_ioDAO ('dbuser')->update($record);
					
				} else { // Ajout 
					$record->cle_privee = md5(SSO::randomKeys(32));
					_ioDAO ('dbuser')->insert($record);
					if (!$record->id_dbuser)
						$check[] = 'Probl&egrave;me de cr&eacute;ation du compte';
				}
				
				// Enregistrement des droits
				if (!count($check) && $record->id_dbuser) {
				
					$sp = _daoSearchParams ();
					$sp->addCondition ('user_dbgroup', '=', $record->id_dbuser);
					_ioDAO ('dbgroup_users')->deleteBy ($sp);
					
					if ($record->type_dbuser == 'root') {
						$dbgroup_users = _record ('dbgroup_users');
						$dbgroup_users->id_dbgroup = 1;
						$dbgroup_users->userhandler_dbgroup = 'auth|dbuserhandler';
						$dbgroup_users->user_dbgroup = $record->id_dbuser;
						_ioDAO ('dbgroup_users')->insert($dbgroup_users);
					}
		
					return _arRedirect (_url ('kernel|compte|'));
				}
				
			}
			$ppo->errors = $check;
			$ppo->rForm = $record;
		} elseif (!$id_dbuser) {
			$ppo->rForm->type_dbuser = 'agent';
			
		}
		
		// Menu
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'kernel', 'level_1' => 'comptes'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('kernel||'), 'Administration');
		$cBc->addItem (CopixUrl::get ('kernel|compte|'), 'Comptes d\'acc&egrave;s');
		if ($id_dbuser)
	 		$cBc->addItem (null, 'Modification');
		else
	 		$cBc->addItem (null, 'Insertion');
		
		return _arPPO ($ppo, 'compte-form.tpl');
	}




	/**
	 * Suppression d'un compte
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @param integer $id_dbuser Id du compte
	 * @param integer $confirm Si confirmation
	 * @since 2009/03/31
	 */
	public function processDelete () {
		
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Compte";
		
		$id_dbuser = CopixRequest::getInt('id_dbuser');
		$confirm = CopixRequest::getInt('confirm');
		
		$criticError = '';
		
		$userDAO = _ioDAO ('dbuser');
	  if (!$rUser = $userDAO->get ($id_dbuser))
			$criticError = "Compte inexistant";
		elseif ($rUser->login_dbuser==_currentUser()->getLogin())
			$criticError = "Impossible de se supprimer soi-m&ecirc;me !";
		
		//var_dump($rSeance);
		if ($criticError)
			return CopixActionGroup::process ('generictools|Messages::getError',
				array (
					'message'=>$criticError,
					'back'=>_url ('kernel|compte|'),
				)
			);
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('kernel||'), 'Administration');
 		$cBc->addItem (CopixUrl::get ('kernel|compte|'), 'Compte d\'acc&egrave;s');
 		$cBc->addItem (null, 'Suppression');
			
		if (!$confirm)
			return CopixActionGroup::process ('generictools|Messages::getConfirm',
				array (
					'title'=>'Compte '.$rUser->login_dbuser,
					'message'=>'Supprimer ce compte ?',
					'confirm'=>_url ('kernel|compte|delete', array ('id_dbuser'=>$id_dbuser, 'confirm'=>1)),
          'cancel'=>_url ('kernel|compte|'),
					'MENU'=>CopixZone::process ('kernel|menu', array ('level_0' => 'kernel', 'level_1' => 'comptes')),
				)
			);
		
		// On supprime
		$sp = _daoSearchParams ();
		$sp->addCondition ('user_dbgroup', '=', $rUser->id_dbuser);
		_ioDAO ('dbgroup_users')->deleteBy ($sp);
	
		_ioDAO ('dbuser')->delete($rUser->id_dbuser);

		return _arRedirect (_url ('kernel|compte|'));

	}



	private function _parametersToObject ($table, &$record) {
		switch ($table) {
			case 'dbuser' :
				$record->id_dbuser = CopixRequest::getInt('id_dbuser');
				$record->login_dbuser = CopixRequest::get('login_dbuser');
				$record->password1 = CopixRequest::get('password1');
				$record->password2 = CopixRequest::get('password2');
				$record->email_dbuser = CopixRequest::get('email_dbuser');
				$record->personnel_dbuser = CopixRequest::getInt('personnel_dbuser');
				$record->type_dbuser = CopixRequest::get('type_dbuser');
				$record->enabled_dbuser = 1;
				break;
		}
	}
}

?>
