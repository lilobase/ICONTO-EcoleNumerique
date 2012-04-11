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
class ActionGroupComptes extends enicActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
		$this->menu = array();
		
		if($this->user->root || _currentUser()->hasAssistance('can_comptes') ) 
		{
			$this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getUsers'), 'url' => CopixUrl::get ('gestionautonome||showTree'), 'type'=>'users');
			$this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getExt'), 'url' => CopixUrl::get ('comptes||getUserExt'), 'type'=>'acl', 'current'=>'current');
		}
		if($this->user->root) 
		{
			$this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getAnim'), 'url' => CopixUrl::get ('comptes|animateurs|list'), 'type'=> 'acl');
			$this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.manageGrades'), 'url' => CopixUrl::get ('gestionautonome||manageGrades'), 'type'=>'agendalist');
		}
		//CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));
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
		
		if( CopixConfig::exists('kernel|gestionAutonomeEnabled') && CopixConfig::get('kernel|gestionAutonomeEnabled') ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('gestionautonome|default|showTree', array('type'=>'ROOT') ));
		}
		
		
		$tpl = new CopixTpl ();
		$tplGetNode = new CopixTpl ();
		
		$pType = _request("type", 'ROOT');
		$pId = _request("id");
		
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
				//print_r($childs);
				
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
		
		$result = $tplGetNode->fetch("getnode.tpl");
		
		$menu = array();
		
		$session = _sessionGet ('modules|comptes|doLoginCreate|success');
		
		if( $session && is_array($session) && sizeof($session) ) {
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.showloginresult', sizeof($session) ), 'url' => CopixUrl::get ('comptes||getLoginResult'), 'size'=>160 );
		}
		/*if( Kernel::getLevel( 'ROOT', 0 ) >= PROFILE_CCV_ADMIN ) {
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.getext'), 'url' => CopixUrl::get ('comptes||getUserExt'), 'size'=>160 );
			$menu[] = array( 'txt' => CopixI18N::get('comptes.strings.getanim'), 'url' => CopixUrl::get ('comptes|animateurs|list'), 'size'=>120 );
		}
		if( count($menu) ) $tpl->assign ('MENU', $menu );*/
		$tpl->assign('MENU', $this->menu);
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
		
		$tpl = new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getloginform'));
		
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_listes'), 'url' => CopixUrl::get ('comptes||getNode', array('type'=>_request('type'),'id'=>_request('id'))) );
		$tpl->assign ('MENU', $menu );
		
		if( $pUsers = _request('users') ) {
		
			$users = array();
			
			// $pUsers = _request('users');
			//var_dump($pUsers);
			
			
			foreach( $pUsers AS $user ) {
				if( ereg( '(.+)-(.+)', $user, $user_infos ) ) {
					$user_type = $user_infos[1];
					$user_id   = $user_infos[2];
					$user_infos = Kernel::getUserInfo( $user_type, $user_id );
					
					// Vérification de l'existance d'un login.
					// -> Si c'est le cas, il ne faut pas proposer un nouveau login.
					$bu_user = $bu_dao->getByBUID( $user_type, $user_id );
	
					if( !count($bu_user) ) {
						
						$user_infos['login']  = $comptes_service->createLogin( $user_infos );
						$user_infos['passwd'] = $comptes_service->createPasswd();
						
						$users[] = $user_infos;
					}
				}
			}
		} elseif( $pReset = _request('reset') ) {
			$users = array();
			$pNodeType = _request('type');
			$pNodeId   = _request('id');
			
			$childs = Kernel::getNodeChilds( $pNodeType, $pNodeId );
			$users_dump = Kernel::filterNodeList( $childs, $pReset );

            if( $pReset == 'USER_RES' ) {
                $users_dump = array();
                $eleves = Kernel::filterNodeList( $childs, 'USER_ELE' );
                foreach( $eleves AS $eleve ) {
                    $parent = Kernel::getNodeChilds( $eleve['type'], $eleve['id'] );
                    $parents = Kernel::filterNodeList( $parents, 'USER_RES' );
                    foreach( $parents AS $parent ) {
                        $users_dump[] = $parent;
                    }
                }
            }

			foreach( $users_dump AS $user ) {
				$user_infos = Kernel::getUserInfo( $user['type'], $user['id'] );
				$bu_user = $bu_dao->getByBUID( $user['type'], $user['id'] );

				// _dump($user_infos);
				// _dump($bu_user);
				
				if( count($bu_user) ) {
					$user_infos['login']  = $bu_user[0]->user_login;
					$user_infos['passwd'] = $comptes_service->createPasswd();
					$users[] = $user_infos;
				}
			}
			
			
		} else {
			$urlReturn = CopixUrl::get ('comptes||getNode', array('type'=>_request('type'), 'id'=>_request('id') ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		
		// Ajoute le type d'utilisateur en toute lettres.
		foreach( $users AS $user_key=>$user_val ) {
			$users[$user_key]['type_nom'] = Kernel::Code2Name($user_val['type']);
		}
		
		$tpl->assign ('MAIN', CopixZone::process ('comptes|loginform', array('users'=>$users,'type'=>_request('type'),'id'=>_request('id'), 'reset'=>_request('reset'))) );
		$tpl->assign ('MENU', $this->menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * doLoginCreate
	 *
	 * Execute la création des comptes et sauvegarde les infos en session.
	 * @author	Frédéric Mossmann
	 * @since	16.02.2006
	 * 
	 */
	function doLoginCreate () {
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");
		
		$pConfirm = _request('confirm', array());
		$pLogin = _request('login', array());
		$pPasswd = _request('passwd', array());
		$pReset = _request('reset', '');
		
		// Parcours de tous les utilisateurs de la liste précédente...
		foreach( _request('typeid') AS $typeid ) {
			// Si l'utilisateur est sélectionné, on crée le compte. Sinon, on ne fait rien.
			if( isset($pConfirm[$typeid]) && $pConfirm[$typeid] == 1 ) {
				// Vérification du format de type "USER_ENS-23", et extraction des valeurs.
				if( ereg( '(.+)-(.+)', $typeid, $bu_infos ) ) {
					$user_type = $bu_infos[1];
					$user_id   = $bu_infos[2];
					
					$olduser = _dao("kernel|kernel_copixuser")->getByLogin($pLogin[$typeid]);
					
					// Test de préexistance du login dans la base. Si existe déjà : erreur.
					if( ! count($olduser) ) {

						// Récupération des information de l'utilisateur dans la base unique.
						$user_infos = Kernel::getUserInfo( $user_type, $user_id );
						
						// Création d'un login dans CopixUser
						$user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
						$user_new->login_dbuser = $pLogin[$typeid];
						$user_new->password_dbuser = md5($pPasswd[$typeid]);
						$user_new->email_dbuser = '';
						$user_new->enabled_dbuser = 1;
						
						// Enregistrement et vérification de l'insertion.
						if( $user_dao->insert( $user_new ) ) {
							
							// Création du lien entre l'utilisateur de la base unique et le login.
							$bu_new = _record("kernel|kernel_bu2user2");
							$bu_new->user_id = $user_new->id_dbuser;
							$bu_new->bu_type = $user_type;
							$bu_new->bu_id = $user_id;
							
							// Enregistrement et vérification de l'insertion.
							if( _dao("kernel|kernel_bu2user2")->insert( $bu_new ) ) {
								
								$node_infos = Kernel::getNodeInfo( _request('type'), _request('id'), false );
								
								// Garder en mémoire les comptes créés pour impression des passwords
								if (!$session = _sessionGet ('modules|comptes|doLoginCreate|success'))
									$session = array();
								
								$session[$typeid] = array(
									'id'      => $user_new->id_dbuser,
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
						
					} elseif( $pReset != '' ) {
						$user_infos = Kernel::getUserInfo( $user_type, $user_id );
						$user_new = $user_dao->getByLogin( $pLogin[$typeid] );
						
						// _dump($user_new); die();
						
						$user_new[0]->password_dbuser = md5($pPasswd[$typeid]);
						$user_dao->update( $user_new[0] );
						
						$node_infos = Kernel::getNodeInfo( _request('type'), _request('id'), false );
						
						$session[$typeid] = array(
							'id'      => $user_new[0]->id_dbuser,
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
						
					} else { // Si le login existe déjà, vérification qu'il ne s'agit pas de la même personne.
						// Si c'est le cas, ce n'est pas une erreur, mais un doublon.
						$bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");
						$bu_user = $bu_dao->getByLogin($pLogin[$typeid]);
						
						$bu_user_deja = false;
						foreach ($bu_user as $user) {
							$bu_user_deja = $user;
							break;
						}
						
						//print_r($bu_user_deja);
						
						if( $bu_user_deja && $bu_user_deja->bu_type!=$user_type || $bu_user_deja->bu_id!=$user_id ) {
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
		
		//print_r($inSession);
		
		$tpl = new CopixTpl ();
		$tplLoginResult = new CopixTpl ();
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
				$main = $tplLoginResult->fetch ('loginresult.tpl');
				break;
			case 'html':
				$main = $tplLoginResult->fetch ('loginresult-html.tpl');
				return _arContent ($main, array ('filename'=>'Logins-'.date('YmdHi').'.html', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.html')));
				break;
			case 'text':
				$main = $tplLoginResult->fetch ('loginresult-text.tpl');
				return _arContent ($main, array ('filename'=>'Logins-'.date('YmdHi').'.txt', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.txt')));
				break;
			case 'csv':
				$main = $tplLoginResult->fetch ('loginresult-csv.tpl');
				return _arContent ($main, array ('filename'=>'Logins-'.date('YmdHi').'.csv', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.csv')));
				break;
			case 'xml':
				$main = $tplLoginResult->fetch ('loginresult-xml.tpl');
				return _arContent ($main, array ('filename'=>'Logins-'.date('YmdHi').'.xml', 'content-disposition'=>'attachement', 'content-type'=>CopixMIMETypes::getFromExtension ('.xml')));
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
		$tpl->assign ('MENU', $this->menu );
		
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
		
		$tpl = new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getpurgeresult'));
		
		$tplPurgeResult = new CopixTpl ();
		$tplPurgeResult->assign ('logins', $inSession );
		$main = $tplPurgeResult->fetch ('getpurgeresult.tpl');
		
		$tpl->assign ( 'MAIN', $main );
		$tpl->assign ('MENU', $this->menu );

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
		

		$tplListe = new CopixTpl ();
		$tplListe->assign ('ecole', $ecole);
		$tplListe->assign ('classes', $classes);
		$tplListe->assign ('classe', $classe);
		$tplListe->assign ('annu', "<pre>".$return_str."</pre>" );
		$result = $tplListe->fetch("view.tpl");

		$tpl = new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $ecole["nom"]." (".$ecole["desc"].")");
		$tpl->assign ("MAIN", $result);
		$tpl->assign ('MENU', $this->menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

	function getUser() {
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$userinfo = $comptes_service->checkLoginAccess( _request('login') );
		
		$tpl = new CopixTpl ();
		$tplGetUser = new CopixTpl ();

		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.strings.modpasswd_user', array(_request('login'))) );


		$nodeinfo['type'] = _request('node_type');
		$nodeinfo['id'  ] = _request('node_id'  );
		$tplGetUser->assign ('node', $nodeinfo );
		$tplGetUser->assign ('user', $userinfo );
		$tplGetUser->assign ('error', _request('error') );
		$tplGetUser->assign ('from', _request('from', '') );
		
		$result = $tplGetUser->fetch("getuser.tpl");
		
		$tpl->assign ("MAIN", $result);
		$tpl->assign ('MENU', $this->menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	function setUserPasswd() {
		$from = _request('from', '');
		
		if( 0 && Kernel::isDemo() ) return Kernel::noDemo();
		$comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
		$userinfo = $comptes_service->checkLoginAccess( _request('login') );
		
		$passwd1 = trim(_request('passwd1'));
		$passwd2 = trim(_request('passwd2'));
		

		if( $passwd1=='' || strlen($passwd1)<6 ) {
			$urlReturn = CopixUrl::get ('comptes||getUser',
				array('node_type'=>_request('node_type'), 'node_id'=>_request('node_id'), 'login'=>_request('login'), 'error'=>'tooshortpassword', 'from'=>$from ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		if( $passwd2=='' || $passwd1!=$passwd2 ) {
			$urlReturn = CopixUrl::get ('comptes||getUser',
				array('node_type'=>_request('node_type'), 'node_id'=>_request('node_id'), 'login'=>_request('login'), 'error'=>'notsamepassword', 'from'=>$from ) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		
		$user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$user_new = $user_dao->getByLogin( _request('login') );
		$user_new[0]->password_dbuser = md5(_request('passwd1'));
		$user_dao->update( $user_new[0] );
		
		//print_r($user_new);

		if( $from == 'assistance' )
			$urlReturn = CopixUrl::get ('assistance||users' );
		else
			$urlReturn = CopixUrl::get ('comptes||getNode', array('type'=>_request('node_type'), 'id'=>_request('node_id')) );
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
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN && !_currentUser()->hasAssistance('can_comptes') )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		
		$tpl = new CopixTpl ();
		$tplGetUserExt = new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserext'));

		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");
		$bu2user_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");

		$userext_list_raw = $userext_dao->findAll();
		$userext_list = array();
		foreach( $userext_list_raw AS $userext_key=>$userext_val ) {
			if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN && Kernel::getLevel( 'ROOT', 0, "USER_EXT", $userext_val->ext_id ) >= PROFILE_CCV_ADMIN ) continue;
			if( $bu2user_item = $bu2user_dao->getByBUID( "USER_EXT", $userext_val->ext_id ) )
				$userext_val->bu2user = $bu2user_item[0];
			$userext_list[$userext_key] = $userext_val;
		}
		$tplGetUserExt->assign ('userlist', $userext_list );
		
		$result = $tplGetUserExt->fetch("getuserext.tpl");
		// $result = '<pre>'.print_r( $userext_list, true ).'</pre>'.$result;
		$tpl->assign ('MAIN', $result );
		
		$tpl->assign ('MENU', $this->menu );

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
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN && !_currentUser()->hasAssistance('can_comptes') )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		
		$errors=array();
		$tpl = new CopixTpl ();
		
		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");
		$copixuser_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
		$bu2user_dao = & CopixDAOFactory::create("kernel|kernel_bu2user2");
		
		$pNom = trim(_request('nom'));
		$pPrenom = trim(_request('prenom'));
		$pLogin = trim(_request('login'));
		$pPasswd1 = trim(_request('passwd1'));
		$pPasswd2 = trim(_request('passwd2'));
		
		$mode = _request('mode');
		
		if( $mode ) {
			
			switch( $mode ) {
				
				case 'MOD':
					$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextmod'));
					
					$userext_item = $userext_dao->get( _request('id') );
					if(!$userext_item) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					
					
					if( $pNom=='' && $pPrenom=='' ) {
						$errors['ext_nom'] = CopixI18N::get ('comptes.alert.nameempty');
					}
					
					if( $pPasswd1!='' && $pPasswd1!=$pPasswd2 ) {
						$errors['passwd2'] = CopixI18N::get ('comptes.alert.passwddiff');
					}
					
					if(count($errors)==0) {
						$userext_item->ext_nom    = $pNom;
						$userext_item->ext_prenom = $pPrenom;
						$userext_dao->update( $userext_item );
						
						if( $pPasswd1!='' && $pPasswd1==$pPasswd2 ) {
							// Changer passwd
							
							// dbuser : id_dbuser 	login_dbuser 	password_dbuser 	email_dbuser 	enabled_dbuser
							// kernel_link_bu2user : user_id 	bu_type 	bu_id
							
							$sql = "
								UPDATE dbuser
								JOIN kernel_link_bu2user
									ON dbuser.id_dbuser=kernel_link_bu2user.user_id
								SET dbuser.password_dbuser=md5(:passwd)
								WHERE kernel_link_bu2user.bu_type = 'USER_EXT'
								AND   kernel_link_bu2user.bu_id   = :id";
							
							_doQuery($sql, array(':passwd'=>$pPasswd1, ':id'=>_request('id')) );
						}
						
						return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					}
					
					break;

				case 'ADD':
					$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextadd'));
					
					$userext_item = CopixDAOFactory::createRecord("kernel|kernel_ext_user");
					if( $pNom=='' && $pPrenom=='' ) {
						$errors['ext_nom'] = CopixI18N::get ('comptes.alert.nameempty');
						$userext_item->ext_id = 0;
					}
					
					if( trim($pLogin)=='' ) {
						$errors['login'] = CopixI18N::get ('comptes.alert.loginempty');
					} else {
						$logins = _doQuery('SELECT * FROM dbuser WHERE login_dbuser=:login', array(':login'=>$pLogin));
						if( count($logins) ) {
							$errors['login'] = CopixI18N::get ('comptes.alert.loginexists');
						}
					}
					
					if( $pPasswd1=='' ) {
						$errors['passwd1'] = CopixI18N::get ('comptes.alert.passwdempty');
					}
					
					if( $pPasswd1!='' && $pPasswd1!=$pPasswd2 ) {
						$errors['passwd2'] = CopixI18N::get ('comptes.alert.passwddiff');
					}
					
					
					if(count($errors)==0) {
						// $userext_item->ext_id
						$userext_item->ext_nom         = $pNom;
						$userext_item->ext_prenom      = $pPrenom;
						$userext_item->ext_description = '';
						$userext_dao->insert( $userext_item );
						
						$copixuser_item = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
						$copixuser_item->login_dbuser = trim($pLogin);
						$copixuser_item->password_dbuser = md5($pPasswd1);
						$copixuser_item->email_dbuser = '';
						$copixuser_item->enabled_dbuser = 1;
						$copixuser_dao->insert( $copixuser_item );
						
						$bu2user_item = CopixDAOFactory::createRecord("kernel|kernel_bu2user2");
						$bu2user_item->user_id = $copixuser_item->id_dbuser;
						$bu2user_item->bu_type = "USER_EXT";
						$bu2user_item->bu_id = $userext_item->ext_id;
						$bu2user_dao->insert( $bu2user_item );
						
						return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					}
					
					break;

				case 'DEL':
					// die("Do Del");
					$userext_item = $userext_dao->get( abs(_request('id')) );
					if( $userext_item ) {
						$userext_dao->delete( abs(_request('id')) );
					}
					
					_doQuery('DELETE FROM dbuser, kernel_link_bu2user USING dbuser JOIN kernel_link_bu2user ON dbuser.id_dbuser=kernel_link_bu2user.user_id WHERE kernel_link_bu2user.bu_type="USER_EXT" AND kernel_link_bu2user.bu_id=:id', array(':id'=>abs(_request('id'))));
					// _doQuery('DELETE FROM kernel_link_bu2user WHERE kernel_link_bu2user.bu_type="USER_EXT" AND kernel_link_bu2user.bu_id=:id', array(':id'=>abs(_request('id'))));
					
					return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
					
					break;
			}

		} else {
			if( _request('id') > 0 ) {
				$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextmod'));
				
				$userext_item = $userext_dao->get( _request('id') );
				if( !$userext_item ) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
				if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN && Kernel::getLevel( 'ROOT', 0, "USER_EXT", $userext_item->ext_id ) >= PROFILE_CCV_ADMIN )
					return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt') );
				$mode = 'MOD';
			} elseif( _request('id') < 0 ) {
				$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextdel'));
				
				$userext_item = $userext_dao->get( abs(_request('id')) );
				if( !$userext_item ) return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt' ) );
				if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN && Kernel::getLevel( 'ROOT', 0, "USER_EXT", $userext_item->ext_id ) >= PROFILE_CCV_ADMIN )
					return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes||getUserExt') );
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
		$tpl->assign ('MENU', $this->menu );

		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}

?>
