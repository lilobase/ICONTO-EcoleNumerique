<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: comptes.actiongroup.php,v 1.35 2009-08-31 10:00:17 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupComptes extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
		CopixTpl::setTheme(Kernel::getTheme());
	}

	/**
	 * go
	 *
	 * Appel automatique, avec détection du noeud à afficher.
	 * @author	Frédéric Mossmann
	 * @since	09.02.2006
	 * 
	 */
	function go () {
		if( _request("id") && ereg('(.+)-(.+)', _request("id"), $regs) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getNode', array('type'=>$regs[1], 'id'=>$regs[2]) ));
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getNode', array('type'=>'ROOT') ));
	}

	/**
	 * getNode
	 *
	 * Affiche les informations d'un noeud et les utilisateurs qui y sont attachés.
	 * @author	Frédéric Mossmann
	 * @since	09.02.2006
	 * 
	 */
	function getNode () {
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplGetNode = & new CopixTpl ();
		
		$pType = _request("type");
		$pId = _request("id");
		
		if( !$pType ) {
			$pType = _currentUser()->getExtraHome("type");
			$pId = _currentUser()->getExtraHome("id");
		}
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getnode'));
		$petitpoucet=array();
		$droit = 0+Kernel::getLevel( $pType, $pId );

		switch( $pType ) {
			case 'ROOT':
				$pId = 0;
				
				// ROOT
				$petitpoucet[] = array( 'txt' => 'Root' );
				
				$childs = Kernel::getNodeChilds( $pType, $pId );
				$childs = Kernel::filterNodeList( $childs, 'USER_*' );
				
				break;
			
			case 'BU_GRVILLE':
				$infos = Kernel::getNodeInfo( $pType, $pId, false );
				
				// ROOT
				$petitpoucet[] = array( 'txt' => 'Root', 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>'ROOT')) );
				// BU_GRVILLE
				$petitpoucet[] = array( 'txt' => $infos['nom'] );
				
				// Liste des "BU_VILLE"
				$childs = Kernel::getNodeChilds( $pType, $pId );
				$childs = Kernel::filterNodeList( $childs, 'USER_*' );
				
				$droit = max($droit, Kernel::getLevel( 'ROOT', 0 ) );
				
				break;
			
			case 'BU_VILLE':
				$infos = Kernel::getNodeInfo( $pType, $pId, false );
				
				// Recherche des parents
				$parents = Kernel::getNodeParents( $pType, $pId );
				$parent_grville = Kernel::filterNodeList( $parents, 'BU_GRVILLE' );
				
				// ROOT
				$petitpoucet[] = array( 'txt' => 'Root', 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>'ROOT')) );
				// BU_GRVILLE
				$petitpoucet[] = array( 'txt' => $parent_grville[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_grville[0]['type'], 'id'=>$parent_grville[0]['id'])) );
				// BU_VILLE
				$petitpoucet[] = array( 'txt' => $infos['nom'] );
				
				// Liste des "BU_VILLE"
				$childs = Kernel::getNodeChilds( $pType, $pId );
				$childs = Kernel::filterNodeList( $childs, 'USER_*' );
				
				$droit = max($droit, Kernel::getLevel( 'BU_GRVILLE', $parent_grville[0]["id"] ) );
				$droit = max($droit, Kernel::getLevel( 'ROOT', 0 ) );
				
				break;
			
			case 'BU_ECOLE':
				$infos = Kernel::getNodeInfo( $pType, $pId, false );
				
				// Recherche des parents
				$parents = Kernel::getNodeParents( $pType, $pId );
				$parent_ville = Kernel::filterNodeList( $parents, 'BU_VILLE' );
				$parents = Kernel::getNodeParents( $parent_ville[0]["type"], $parent_ville[0]["id"] );
				$parent_grville = Kernel::filterNodeList( $parents, 'BU_GRVILLE' );
				
				// ROOT
				$petitpoucet[] = array( 'txt' => 'Root', 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>'ROOT')) );
				// BU_GRVILLE
				$petitpoucet[] = array( 'txt' => $parent_grville[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_grville[0]['type'], 'id'=>$parent_grville[0]['id'])) );
				// BU_VILLE
				$petitpoucet[] = array( 'txt' => $parent_ville[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_ville[0]['type'], 'id'=>$parent_ville[0]['id'])) );
				// BU_ECOLE
				$petitpoucet[] = array( 'txt' => $infos['nom'] );
				
				// Liste des "BU_VILLE"
				$childs = Kernel::getNodeChilds( $pType, $pId );
				$childs = Kernel::filterNodeList( $childs, 'USER_*' );
				
				$droit = max($droit, Kernel::getLevel( 'BU_VILLE', $parent_ville[0]["id"] ) );
				$droit = max($droit, Kernel::getLevel( 'BU_GRVILLE', $parent_grville[0]["id"] ) );
				$droit = max($droit, Kernel::getLevel( 'ROOT', 0 ) );
				
				break;
			
			case 'BU_CLASSE':
				$infos = Kernel::getNodeInfo( $pType, $pId, false );
				
				// Recherche des parents
				$parents = Kernel::getNodeParents( $pType, $pId );
				$parent_ecole = Kernel::filterNodeList( $parents, 'BU_ECOLE' );
				$parents = Kernel::getNodeParents( $parent_ecole[0]["type"], $parent_ecole[0]["id"] );
				$parent_ville = Kernel::filterNodeList( $parents, 'BU_VILLE' );
				$parents = Kernel::getNodeParents( $parent_ville[0]["type"], $parent_ville[0]["id"] );
				$parent_grville = Kernel::filterNodeList( $parents, 'BU_GRVILLE' );
				
				// ROOT
				$petitpoucet[] = array( 'txt' => 'Root', 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>'ROOT')) );
				// BU_GRVILLE
				$petitpoucet[] = array( 'txt' => $parent_grville[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_grville[0]['type'], 'id'=>$parent_grville[0]['id'])) );
				// BU_VILLE
				$petitpoucet[] = array( 'txt' => $parent_ville[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_ville[0]['type'], 'id'=>$parent_ville[0]['id'])) );
				// BU_ECOLE
				$petitpoucet[] = array( 'txt' => $parent_ecole[0]['nom'], 'url'=>CopixUrl::get ('comptes||getNode', array('type'=>$parent_ecole[0]['type'], 'id'=>$parent_ecole[0]['id'])) );
				// BU_CLASSE
				$petitpoucet[] = array( 'txt' => $infos['nom'] );
				
				// Liste des "BU_VILLE"
				$childs = Kernel::getNodeChilds( $pType, $pId );
				
				$eleves = Kernel::filterNodeList( $childs, 'USER_ELE' );
				foreach( $eleves AS $eleve ) {
					$parents = Kernel::getNodeChilds( $eleve['type'], $eleve['id'] );
					$parents = Kernel::filterNodeList( $parents, 'USER_RES' );
					foreach( $parents AS $parent ) {
						$childs[] = $parent;
					}
				}
				
				$childs = Kernel::filterNodeList( $childs, 'USER_*' );
				
				$droit = max($droit, Kernel::getLevel( 'BU_ECOLE', $parent_ecole[0]["id"] ) );
				$droit = max($droit, Kernel::getLevel( 'ROOT', 0 ) );
				
				break;
			
			default:
				return CopixActionGroup::process ('genericTools|Messages::getError',
					array ('message'=>CopixI18N::get ('comptes.error.badnodetype'),
					'back'=>CopixUrl::get ('||')));
				break;
		}
		
		// Tri par type de noeud
		// $childs = Kernel::sortNodeList( $childs, 'type', array('USER_VIL','USER_ENS','USER_ADM','USER_ELE','USER_RES','USER_EXT') );
		$childs = Kernel::sortNodeList( $childs, 'comptes' );
		$childs = Kernel::uniqNodeList( $childs );
		
		// Ajoute le type d'utilisateur en toute lettres.
		foreach( $childs AS $child_key=>$child_val ) {
			$childs[$child_key]['type_nom'] = Kernel::Code2Name($child_val['type']);
		}
		
		
		if( isset($petitpoucet) ) $tplGetNode->assign ('PETITPOUCET', Kernel::PetitPoucet($petitpoucet," &raquo; ") );
		$tplGetNode->assign ('NAVIGATION', CopixZone::process ('comptes|navigation'));
		
		if( $droit>=70 ) $tplGetNode->assign ('MAIN', CopixZone::process ('comptes|userlist', array('childs'=>$childs,'type'=>$pType,'id'=>$pId)) );
		else             $tplGetNode->assign ('MAIN', CopixI18N::get ('comptes.error.badrights') );
		
		$result = $tplGetNode->fetch("getNode.tpl");
		
		$menu = array();
		
		$session = _sessionGet ('modules|comptes|doLoginCreate|success');
		
		if( $session && is_array($session) && sizeof($session) ) {
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.showloginresult', sizeof($session) ), 'url' => CopixUrl::get ('comptes||getLoginResult') );
		}
		if( Kernel::getLevel( 'ROOT', 0 ) >= PROFILE_CCV_ADMIN ) {
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.getext'), 'url' => CopixUrl::get ('comptes||getUserExt') );
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.getanim'), 'url' => CopixUrl::get ('comptes|animateurs|list') );
		}
		if( count($menu) ) $tpl->assign ('MENU', $menu );
		$tpl->assign ('MAIN', $result );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}



	/**
	 * getLoginForm
	 *
	 * Affiche la liste des comptes à créer, avec proposition de login/passwd.
	 * @author	Frédéric Mossmann
	 * @since	14.02.2006
	 * 
	 */
	function getLoginForm () {
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");
		
		$tpl = & new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getloginform'));
		
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_listes'), 'url' => CopixUrl::get ('comptes||getNode', array('type'=>_request('type'),'id'=>_request('id'))) );
		$tpl->assign ('MENU', $menu );
		
		if( !_request('users') ) {
			$urlReturn = CopixUrl::get ('comptes||getNode', array('type'=>_request('type'), 'id'=>_request('id') ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		$users = array();
		
		foreach( _request('users') AS $user ) {
			if( ereg( '(.+)-(.+)', $user, $user_infos ) ) {
				$user_type = $user_infos[1];
				$user_id   = $user_infos[2];
				$user_infos = Kernel::getUserInfo( $user_type, $user_id );
				
				// Vérification de l'existance d'un login.
				// -> Si c'est le cas, il ne faut pas proposer un nouveau login.
				$bu_user = $bu_dao->getByBUID( $user_type, $user_id );
				if( !$bu_user ) {
					
					$user_infos['login']  = $comptes_service->createLogin( $user_infos );
					$user_infos['passwd'] = $comptes_service->createPasswd();
					
					$users[] = $user_infos;
				}
			}
		}
		
		// Ajoute le type d'utilisateur en toute lettres.
		foreach( $users AS $user_key=>$user_val ) {
			$users[$user_key]['type_nom'] = Kernel::Code2Name($user_val['type']);
		}
		
		$tpl->assign ('MAIN', CopixZone::process ('comptes|loginform', array('users'=>$users,'type'=>_request('type'),'id'=>_request('id'))) );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * doLoginCreate
	 *
	 * Execute la créattion des comptes et sauvegarde les infos en session.
	 * @author	Frédéric Mossmann
	 * @since	16.02.2006
	 * 
	 */
	function doLoginCreate () {
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$user_service = & CopixClassesFactory::Create ('auth|ProjectUser');
		$user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");
		
		$pConfirm = _request('confirm', array());
		$pLogin = _request('login', array());
		$pPasswd = _request('passwd', array());
		
		// Parcours de tous les utilisateurs de la liste précédente...
		foreach( _request('typeid') AS $typeid ) {
			// Si l'utilisateur est sélectionné, on crée le compte. Sinon, on ne fait rien.
			if( $pConfirm[$typeid] == 1 ) {
				// Vérification du format de type "USER_ENS-23", et extraction des valeurs.
				if( ereg( '(.+)-(.+)', $typeid, $bu_infos ) ) {
					$user_type = $bu_infos[1];
					$user_id   = $bu_infos[2];
					
					$olduser=$user_service->get($pLogin[$typeid]);
					
					// Test de préexistance du login dans la base. Si existe déjà : erreur.
					if( ! $olduser ) {
						
						// Récupération des information de l'utilisateur dans la base unique.
						$user_infos = Kernel::getUserInfo( $user_type, $user_id );
						
						// Création d'un login dans CopixUser
						$user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
						$user_new->login_cusr = $pLogin[$typeid];
						$user_new->password_cusr = md5($pPasswd[$typeid]);
						$user_new->email_cusr = '';
						
						// Enregistrement et vérification de l'insertion.
						if( $user_dao->insert( $user_new ) ) {
							
							// Création du lien entre l'utilisateur de la base unique et le login.
							$bu_new = CopixDAOFactory::createRecord("kernel|kernel_bu2user");
							$bu_new->user_id = $user_new->id_cusr;
							$bu_new->bu_type = $user_type;
							$bu_new->bu_id = $user_id;
							
							// Enregistrement et vérification de l'insertion.
							if( $bu_dao->insert( $bu_new ) ) {
								
								$node_infos = Kernel::getNodeInfo( _request('type'), _request('id'), false );
								
								// Garder en mémoire les comptes créés pour impression des passwords
								if (!$session = _sessionGet ('modules|comptes|doLoginCreate|success'))
									$session = array();
								
								$session[$typeid] = array(
									'id'      => $user_new->id_cusr,
									'login'   => $pLogin[$typeid],
									'passwd'  => $pPasswd[$typeid],
									'nom'     => $user_infos['nom'],
									'prenom'  => $user_infos['prenom'],
									'bu_type' => $user_type,
									'bu_id'   => $user_id,
									'node_type' => _request('type'),
									'node_id'   => _request('id'),
									'type_nom'  => Kernel::Code2Name($user_type),
									'node_nom' => Kernel::Code2Name(_request('type'))." ".$node_infos['nom'],
								);
								_sessionSet ('modules|comptes|doLoginCreate|success', $session);
								
							} else { // Si le lien entre la BU et le login ne fonctionne pas...
								
								// Garder en mémoire les echecs pour proposer une nouvelle insertion
								if (!$session = _sessionGet ('modules|comptes|doLoginCreate|error'))
									$session = array();
									
								$session[$typeid] = array(
									'login'   => $pLogin[$typeid],
									'passwd'  => $pPasswd[$typeid],
									'nom'     => $user_infos['nom'],
									'prenom'  => $user_infos['prenom'],
									'bu_type' => $user_type,
									'bu_id'   => $user_id,
									'error'   => 'BU2USER',
								);
								_sessionSet ('modules|comptes|doLoginCreate|error', $session);
								
								// Prévoir un Rollback pour effacer le login ?
							}
							
						} else { // Si le login est impossible à créer...
							
							if (!$session = _sessionGet ('modules|comptes|doLoginCreate|error'))
								$session = array();
									
							// Garder en mémoire les echecs pour proposer une nouvelle insertion
							$session[$typeid] = array(
								'login'   => $pLogin[$typeid],
								'passwd'  => $pPasswd[$typeid],
								'nom'     => $user_infos['nom'],
								'prenom'  => $user_infos['prenom'],
								'bu_type' => $user_type,
								'bu_id'   => $user_id,
								'error'   => 'COPIXUSER',
							);
							
							_sessionSet ('modules|comptes|doLoginCreate|error', $session);
							
						}
						
					} else { // Si le login existe déjà, vérification qu'il ne s'agit pas de la même personne.
						// Si c'est le cas, ce n'est pas une erreur, mais un doublon.
						
						$bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");
						$bu_user = $bu_dao->getByLogin($pLogin[$typeid]);
						if( $bu_user[0]->bu_type!=$user_type || $bu_user[0]->bu_id!=$user_id ) {
							if (!$session = _sessionGet ('modules|comptes|doLoginCreate|error'))
								$session = array();
								
							// Garder en mémoire les echecs pour proposer une nouvelle insertion
							$session[$typeid] = array(
								'login'   => $pLogin[$typeid],
								'passwd'  => $pPasswd[$typeid],
								'nom'     => $user_infos['nom'],
								'prenom'  => $user_infos['prenom'],
								'bu_type' => $user_type,
								'bu_id'   => $user_id,
								'error'   => 'LOGINEXISTS',
							);
							_sessionSet ('modules|comptes|doLoginCreate|error', $session);
							
						}
					}
				}
			}
		}
		
		$urlReturn = CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id')) );
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}

	/**
	 * getLoginResult
	 *
	 * Affiche le résultat de la création de comptes (login, passwd) dans différents formats (html, txt, csv, etc.).
	 * @author	Frédéric Mossmann
	 */
	function getLoginResult() {
		
		$inSession = _sessionGet ('modules|comptes|doLoginCreate|success');
		
		if( !$inSession || !is_array($inSession) || !sizeof($inSession) ) {
			$urlReturn = CopixUrl::get ('comptes||getNode' );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		$tpl = & new CopixTpl ();
		$tplLoginResult = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getloginresult'));
		$menu=array();
		
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_html'), 'url' => CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id'),'format'=>'html')) );
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_txt'), 'url' => CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id'),'format'=>'text')) );
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_cvs'), 'url' => CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id'),'format'=>'csv')) );
		 $menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_xml'), 'url' => CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id'),'format'=>'xml')) );
		// $menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_pdf'), 'url' => CopixUrl::get ('comptes||getLoginResult', array('type'=>_request('type'),'id'=>_request('id'),'format'=>'pdf')) );
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_purge'), 'url' => CopixUrl::get ('comptes||getPurgeResult', array('type'=>_request('type'),'id'=>_request('id'))), 'color'=>'red' );
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.export_return'), 'url' => CopixUrl::get ('comptes||getNode', array('type'=>_request('type'),'id'=>_request('id'))) );
		$tpl->assign ('MENU', $menu );
		
		
		$logins = $inSession;
		
		if( !_request('format') || trim(_request('format'))=='' ) {
			$format = "default";
		} else {
			$format = _request('format');
		}
		
		$tplLoginResult->assign ('logins', $logins );
		
		switch( $format ) {
			case 'default':
				$main = $tplLoginResult->fetch ('LoginResult.tpl');
				break;
			case 'html':
				$main = $tplLoginResult->fetch ('LoginResult-html.tpl');
				return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $main, 'Logins-'.date('YmdHi').'.html');
				break;
			case 'text':
				$main = $tplLoginResult->fetch ('LoginResult-text.tpl');
				return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $main, 'Logins-'.date('YmdHi').'.txt');
				break;
			case 'csv':
				$main = $tplLoginResult->fetch ('LoginResult-csv.tpl');
				return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $main, 'Logins-'.date('YmdHi').'.csv');
				break;
			case 'xml':
				$main = $tplLoginResult->fetch ('LoginResult-xml.tpl');
				return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $main, 'Logins-'.date('YmdHi').'.xml');
				break;
			/*
			case 'pdf':
				break;
			*/
			case 'session': // DEBUG
				$main = '<pre>'.print_r( $inSession, true ).'</pre>';
				break;
			default:
				break;
		}
		
		$tpl->assign ( 'MAIN', $main );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * getPurgeResult
	 *
	 * Propose l'effacement les information de création de comptes, mémorisées en session.
	 * @author	Frédéric Mossmann
	 */
	function getPurgeResult() {
		
		$inSession = _sessionGet ('modules|comptes|doLoginCreate|success');
		
		if( !$inSession || !is_array($inSession) || !sizeof($inSession) ) {
			$urlReturn = CopixUrl::get ('comptes||getNode' );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		$tpl = & new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getpurgeresult'));
		
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_listes'), 'url' => CopixUrl::get ('comptes||getNode', array('type'=>_request('type'),'id'=>_request('id'))) );
		$tpl->assign ('MENU', $menu );
		
		$tplPurgeResult = & new CopixTpl ();
		$tplPurgeResult->assign ('logins', $inSession );
		$main = $tplPurgeResult->fetch ('getPurgeResult.tpl');
		
		$tpl->assign ( 'MAIN', $main );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * doPurgeResult
	 *
	 * Efface les information de création de comptes, mémorisées en session.
	 * @author	Frédéric Mossmann
	 */
	function doPurgeResult() {
		
		$inSession = _sessionGet ('modules|comptes|doLoginCreate|success');
		
		foreach( _request('users') AS $typeid ) {
			if( $inSession && is_array($inSession) && array_key_exists( $typeid, $inSession ) )
				
				unset( $inSession[$typeid] );
		}
		_sessionSet ('modules|comptes|doLoginCreate|success', $inSession);
		
		
		$urlReturn = CopixUrl::get ('comptes||getLoginResult' );
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}


	/**
	 * doListForm TODO (description de la fonction)
	 * @package Comptes
	 * @author Frédéric REISS
	 * @since 14.12.2005
	 * 
	 */
	function doListForm () {
		/* Déclaration des DAO */
//		$dao = CopixDAOFactory::create("carnet_topics");
//		$daoMessages = CopixDAOFactory::create("carnet_messages");

		$user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
		$user_new->login_cusr = _request("login_cusr");
		$user_new->password_cusr = md5(_request("password_cusr"));

		$user_dao->insert ($user_new);

		
	}

   /**
   * L'annuaire (TODO temporaire)
   */
   function view () {
	 	
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$classe = _request("classe");
		
		$id = 1;
		$type = "BU_ECOLE";
		
		$ecole = Kernel::getNodeInfo ($type, $id, false);
		//print_r($ecole);
		
		$classes = $enseignants = $directeur = array();
		$result=Kernel::getNodeChilds( $type, $id );
		foreach ($result AS $key=>$value) {
			//print_r($value);
			if ($value["type"]=="BU_CLASSE") {
				$nodeInfo = Kernel::getNodeInfo ($value["type"], $value["id"], false);
				$result[$key]["info"] = $nodeInfo;
				// Enseignants
				$childs=Kernel::getNodeChilds( $value["type"], $value["id"] );				
				//print_r($childs);
				$enseignants = array();
				foreach ($childs AS $child) {
					if ($child["type"]=="USER_ENS") {
						$userInfo = Kernel::getUserInfo ($child["type"], $child["id"]);
						// print_r($userInfo);
						$enseignants[] = $userInfo;
					}
				}
				$result[$key]["enseignants"] = $enseignants;
				$classes[] = $result[$key];
			} elseif ($value["type"]=="USER_ENS") {
				$droit = Kernel::getLevel ($type, $id, $value["type"], $value["id"]);
				if ($droit >= PROFILE_CCV_ADMIN) {
					$nodeInfo = Kernel::getUserInfo ($value["type"], $value["id"]);
					//print_r($nodeInfo);
					$result[$key]["info"] = $nodeInfo;
					//$enseignants[] = $result[$key];
					$directeur = $result[$key];
				}

			} 
		}
		
		//print_r($directeur);
		$ecole["directeur"] = $directeur;
		//print_r($ecole);
		$id = $classe;
		$type = "BU_CLASSE";
		$classe = Kernel::getNodeInfo ($type, $id, false);

		$eleves = $enseignants = array();
		$result=Kernel::getNodeChilds( $type, $id );
		foreach ($result AS $key=>$value) {
			if ($value["type"]=="USER_ELE")	{
				$nodeInfo = Kernel::getUserInfo ($value["type"], $value["id"]);
				$result[$key]["info"] = $nodeInfo;
				//print_r($result[$key]);
				$eleves[] = $result[$key];
			} elseif ($value["type"]=="USER_ENS") {
				$userInfo = Kernel::getUserInfo ($value["type"], $value["id"]);
				//print_r($userInfo);
				$enseignants[] = $userInfo;
			}
		}
		//print_r($eleves);
		$classe["eleves"]=$annuaireService->order_tab_eleves($eleves);
		$classe["enseignants"]=$annuaireService->order_tab_enseignants($enseignants);
		//$classe["enseignants"]=($enseignants);
		//print_r($classe);
		

		$tplListe = & new CopixTpl ();
		$tplListe->assign ('ecole', $ecole);
		$tplListe->assign ('classes', $classes);
		$tplListe->assign ('classe', $classe);
		$tplListe->assign ('annu', "<pre>".$return_str."</pre>" );
		$result = $tplListe->fetch("view.tpl");

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $ecole["nom"]." (".$ecole["desc"].")");
		$tpl->assign ("MAIN", $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

	function getUser() {
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$userinfo = $comptes_service->checkLoginAccess( _request('login') );
		
		$tpl = & new CopixTpl ();
		$tplGetUser = & new CopixTpl ();

		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.strings.modpasswd_user', array(_request('login'))) );


		$nodeinfo['type'] = _request('node_type');
		$nodeinfo['id'  ] = _request('node_id'  );
		$tplGetUser->assign ('node', $nodeinfo );
		$tplGetUser->assign ('user', $userinfo );
		$tplGetUser->assign ('error', _request('error') );

		
		$result = $tplGetUser->fetch("getUser.tpl");
		
		$tpl->assign ("MAIN", $result);

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function setUserPasswd() {
		if( Kernel::isDemo() ) return Kernel::noDemo();
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$userinfo = $comptes_service->checkLoginAccess( _request('login') );
		
		$passwd1 = trim(_request('passwd1'));
		$passwd2 = trim(_request('passwd2'));
		

		if( $passwd1=='' || strlen($passwd1)<6 ) {
			$urlReturn = CopixUrl::get ('comptes||getUser',
				array('node_type'=>_request('node_type'), 'node_id'=>_request('node_id'), 'login'=>_request('login'), 'error'=>'tooshortpassword' ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		if( $passwd2=='' || $passwd1!=$passwd2 ) {
			$urlReturn = CopixUrl::get ('comptes||getUser',
				array('node_type'=>_request('node_type'), 'node_id'=>_request('node_id'), 'login'=>_request('login'), 'error'=>'notsamepassword' ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}

		$user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$user_new = $user_dao->getByLogin( _request('login') );
		$user_new[0]->password_cusr = md5(_request('passwd1'));
		$user_dao->update( $user_new[0] );
		

		
		$urlReturn = CopixUrl::get ('comptes||getNode',
			array('type'=>_request('node_type'), 'id'=>_request('node_id')) );
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
	}
	
	/**
	 * getUserExt
	 * 
	 * Affiche la liste des utilisateurs extérieurs
	 * 
	 * @package	Comptes
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getUserExt() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplGetUserExt = & new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserext'));

		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");
		$bu2user_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");

		$userext_list = $userext_dao->findAll();
		foreach( $userext_list AS $userext_key=>$userext_val ) {
			if( $bu2user_item = $bu2user_dao->getByBUID( "USER_EXT", $userext_val->ext_id ) )
				$userext_list[$userext_key]->bu2user = $bu2user_item[0];
		}
		$tplGetUserExt->assign ('userlist', $userext_list );
		
		$result = $tplGetUserExt->fetch("getUserExt.tpl");
		// $result = '<pre>'.print_r( $userext_list, true ).'</pre>'.$result;
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getnode'), 'url' => CopixUrl::get ('comptes||getNode') );
		$tpl->assign ('MENU', $menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

	/**
	 * getUserExtMod
	 * 
	 * Affiche le formulaire de modification d'un utilisateur extérieur
	 * 
	 * @package	Comptes
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getUserExtMod() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$errors=array();
		$tpl = & new CopixTpl ();
		
		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");
		
		$pNom = trim(_request('nom'));
		$pPrenom = trim(_request('prenom'));
		
		
		if( _request('mode') ) {
			
			$mode = _request('mode');
			
			switch( _request('mode') ) {
				
				case 'MOD':
					$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextmod'));
					
					$userext_item = $userext_dao->get( _request('id') );
					// $userext_item->ext_id     = _request('id');
					if( $pNom=='' && $pPrenom=='' ) {
						$errors['ext_nom'] = CopixI18N::get ('comptes.alert.nameempty');
					} else {
						$userext_item->ext_nom    = $pNom;
						$userext_item->ext_prenom = $pPrenom;
						$userext_dao->update( $userext_item );
						return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					}
					
					break;

				case 'ADD':
					$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextadd'));
					
					$userext_item = CopixDAOFactory::createRecord("kernel|kernel_ext_user");
					if( $pNom=='' && $pPrenom=='' ) {
						$errors['ext_nom'] = CopixI18N::get ('comptes.alert.nameempty');
						$userext_item->ext_id = 0;
					} else {
						$userext_item->ext_nom         = $pNom;
						$userext_item->ext_prenom      = $pPrenom;
						$userext_item->ext_description = '';
						$userext_dao->insert( $userext_item );
						return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					}
					
					break;

				case 'DEL':
					// die("Do Del");
					$userext_item = $userext_dao->get( abs(_request('id')) );
					if( $userext_item ) {
						$userext_dao->delete( abs(_request('id')) );
					}
					return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					
					break;
			}

		} else {
			if( _request('id') > 0 ) {
				$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextmod'));
				
				$userext_item = $userext_dao->get( _request('id') );
				if( !$userext_item ) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
				$mode = 'MOD';
			} elseif( _request('id') < 0 ) {
				$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextdel'));
				
				$userext_item = $userext_dao->get( abs(_request('id')) );
				if( !$userext_item ) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
				$mode = 'DEL';
			} else {
				$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextadd'));
				
				$userext_item = CopixDAOFactory::createRecord("kernel|kernel_ext_user");
				$userext_item->ext_id = 0;
				$mode = 'ADD';
			}
		}
		
		if( $mode=='ADD' || $mode=='MOD' )
			$result = CopixZone::process ('comptes|getUserExtForm', array('user'=>$userext_item,'mode'=>$mode,'errors'=>$errors));
		else
			return CopixActionGroup::process ('genericTools|Messages::getConfirm',
				array (
					'title'=>CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextdel'),
					'message'=>CopixI18N::get ('comptes.alert.getuserextdel', trim($userext_item->ext_prenom.' '.$userext_item->ext_nom) ),
					'confirm'=>CopixUrl::get ('comptes|default|getUserExtMod', array ('id'=>_request('id'), 'mode'=>'DEL')),
					'cancel'=>CopixUrl::get ('comptes|default|getUserExt')
				)
			);
		
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getuserext'), 'url' => CopixUrl::get ('comptes||getUserExt') );
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}

?>
