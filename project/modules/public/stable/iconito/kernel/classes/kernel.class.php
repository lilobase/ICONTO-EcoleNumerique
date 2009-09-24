<?php
/**
 * Kernel - Classes
 *
 * Fonctions du coeur d'Iconito : Gestion des utilisateurs, des liens avec les entités, de l'accès à la base élève, des droits.
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: kernel.class.php,v 1.111 2009-08-31 09:59:53 fmossmann Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

class Kernel {
	
	private $cache_getNodeChilds_ville = array();
	private $cache_getNodeChilds_ecole = array();
	private $cache_getNodeChilds_classe = array();
	
	private $cache_getNodeInfo_ville = array();
	private $cache_getNodeInfo_ecole = array();
	private $cache_getNodeInfo_classe = array();
	
	/**
	 * Test un utilisateur est connecté
	 * @return	boolean	true si un utilisateur est connecté, false sinon
	 * @author	Frédéric REISS
	 * @since	15.12.2005
	 * 
	 */
	function is_connected () {
		$user = _currentUser ();
		return ($user && $user->isConnected());
	}

	/**
	 * ymd2dmy
	 *
	 * Transforme une date du format informatique (YYYY-MM-DD) en format français (JJ/MM/YYYY).
	 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $ymd Date au format informatique (YYYY-MM-DD).
	 * @return string Date au format français (JJ/MM/YYYY).
	 */
	function ymd2dmy( $ymd ) {
		if( ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $ymd, $regs) ) {
			$dmy = $regs[3]."/".$regs[2]."/".$regs[1];
			return( $dmy );
		} else return( $ymd );
	}
	

	function getLevel( $node_type, $node_id=0, $user_type="-1", $user_id="-1" ) {
		//print_r("getLevel ($node_type, $node_id, $user_type, $user_id)<br/>");
		/*
		define ('PROFILE_CCV_NONE',     0);
		define ('PROFILE_CCV_SHOW',     10);
		define ('PROFILE_CCV_READ',     20);
		define ('PROFILE_CCV_WRITE',    30);
		define ('PROFILE_CCV_VALID',    40);
		define ('PROFILE_CCV_PUBLISH',  50);
		define ('PROFILE_CCV_MODERATE', 60);
		define ('PROFILE_CCV_ADMIN',    70);
		*/
		$level = 0;
		if( $user_type=="-1" && $user_id=="-1" )
		{
			if( (_currentUser()->getExtra('type')) && (_currentUser()->getExtra('id')) )
			{
				$user_type=_currentUser()->getExtra('type');
				$user_id=_currentUser()->getExtra('id');
			}
		}
		
		
		
		if( ereg( "^MOD_(.+)$", $node_type, $regs ) ) {
			$level = Kernel::getModRight( $node_type, $node_id, $user_type, $user_id );
		} else {
			$tmp = Kernel::getNodeParents( $user_type, $user_id );
			foreach( $tmp as $key => $val ) {
				if( $val["type"]==$node_type && $val["id"]==$node_id )
					$level=$val["droit"];
			}
		}
		return( $level );
	}
	
	function setLevel( $node_type, $node_id, $user_type, $user_id, $droit, $debut=NULL, $fin=NULL ) {
		$dao = _dao("kernel|kernel_link_user2node");
		
		if( $droit==0 ) {
			$dao->delete( $user_type, $user_id, $node_type, $node_id );
//			die("$node_type, $node_id, $user_type, $user_id");
		} else {
			$nouveau_droit = _record("kernel|kernel_link_user2node");
			$nouveau_droit->user_type = $user_type;
			$nouveau_droit->user_id = $user_id;
			$nouveau_droit->node_type = $node_type;
			$nouveau_droit->node_id = $node_id;
			$nouveau_droit->droit = $droit;
			$nouveau_droit->debut = ($debut) ? $debut : NULL;
			$nouveau_droit->fin = ($fin) ? $fin : NULL;
			$dao->insert( $nouveau_droit );
		}
	}
	
	
	/**
	 * Recherche récursive du droit le plus fort d'un utilisateur sur un noeud.
	 *
	 * @return  integer Droit (entre 0 et 70).
	 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getLevel_r( $node_type, $node_id, $user_type="-1", $user_id="-1", $fusible=9 ) {
		if( $fusible-- <= 0 ) return 0;
		$level=(int)Kernel::getLevel( $node_type, $node_id, $user_type, $user_id );

		$node = Kernel::getNodeParents( $node_type, $node_id );
		foreach( $node AS $parent_node ) {
			$level = max(
				$level,
				Kernel::getLevel_r( $parent_node['type'], $parent_node['id'], $user_type, $user_id, $fusible )
			);
		}
		return $level;
	}

	/**
	 * Fonction récursive de parcours de l'arbre des noeuds, en remontant les parents, et concaténation de toutes les données.
	 * Ne pas utiliser directement, mais passer par getNodes.
	 *
	 * @return  array Liste des noeuds rencontrés, avec doublons.
	 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since   15.06.2006
	 */
	function getNodes_r( $node_type, $node_id, $user_type, $user_id, $fusible=9 ) {
		if( $fusible-- <= 0 ) return false;

		$nodes = Kernel::getNodeParents( $node_type, $node_id );
		
		foreach( $nodes AS $parent_node ) {
			$parent_nodes = Kernel::getNodes_r( $parent_node['type'], $parent_node['id'], $user_type, $user_id, $fusible );
			if( $parent_nodes != false )
				$nodes = array_merge( $nodes, $parent_nodes );
		}
		
		return( $nodes );

	}

	/**
	 * Recherche de tous les parents d'une personnes, directs ou indirects, avec informations completes.
	 *
	 * @return  array Liste des noeuds, par type, puis par ID.
	 * @author  Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since   15.06.2006
	 */
	function getNodes( $user_type="-1", $user_id="-1" ) {
		if( $user_type=="-1" && $user_id=="-1" )
		{
			if( (_currentUser()->getExtra('type')) && (_currentUser()->getExtra('id')) )
			{
				$user_type=_currentUser()->getExtra('type');
				$user_id=_currentUser()->getExtra('id');
			}
		}

		$nodes = Kernel::getNodes_r( $user_type, $user_id, $user_type, $user_id, 9 );
		
		$mynodes = array();
		foreach( $nodes AS $node ) {
			$mynodes[$node['type']][$node['id']] = $node;
		}
		
		return $mynodes;
	}

	function registerModule( $module_type, $module_id, $node_type, $node_id ) {
		$dao = _dao("kernel|kernel_mod_enabled");
		$nouveau_module = _record("kernel|kernel_mod_enabled");
		$nouveau_module->node_type = $node_type;
		$nouveau_module->node_id = $node_id;
		$nouveau_module->module_type = $module_type;
		$nouveau_module->module_id = $module_id;
		$dao->insert( $nouveau_module );
    if ($module_type == "MOD_AGENDA") {
			_sessionSet('modules|agenda', null);
    }
		return true;
	}
	
	function unregisterModule( $module_type, $module_id, $node_type="ALL", $node_id=0 ) {
		$dao = _dao("kernel|kernel_mod_enabled");
		if( $node_type=="ALL" && $node_id==0 ) {
			$dao->delByModule( $module_type, $module_id );
		} else {
			$dao->delete( $node_type, $node_id, $module_type, $module_id );
		}
    if ($module_type == "MOD_AGENDA") {
			_sessionSet('modules|agenda', null);
    }
		return true;
	}
	
	function getTree( $racine_type="ROOT", $racine_node=0 ) {
		switch( $racine_type ) {
			case "ROOT" : // Recherche des groupes dans l'ENT...
				$grv_dao = _dao("kernel|kernel_tree_grv");
				$grv_list = $grv_dao->findAll();
				$tree->groupes = array();
				foreach ($grv_list AS $key=>$value) {
					$tree->groupes[$value->grv_id_grv]->info["nom" ] = $value->grv_nom_groupe;
					$tree->groupes[$value->grv_id_grv]->info["crea"] = $value->grv_date_creation;
					$tree->groupes[$value->grv_id_grv]->info["level"] = 0+Kernel::getLevel( 'BU_GRVILLE', $value->grv_id_grv );
					// Recherche des villes dans chaque groupe trouvé
					$villes = Kernel::getTree( "BU_GRVILLE", $value->grv_id_grv );
					$tree->groupes[$value->grv_id_grv]->villes = $villes->villes;
				}
				break;
			case "BU_GRVILLE" : // Recherche des villes dans un groupe...
				$vil_dao = _dao("kernel|kernel_tree_vil");
				$vil_list = $vil_dao->getByGroupeVille($racine_node);
				$tree->villes = array();
				foreach ($vil_list AS $key=>$value) {
					$tree->villes[$value->vil_id_vi]->info["nom" ] = $value->vil_nom;
					$tree->villes[$value->vil_id_vi]->info["crea"] = $value->vil_date_creation;
					$tree->villes[$value->vil_id_vi]->info["level"] = 0+Kernel::getLevel( 'BU_VILLE', $value->vil_id_vi );
					// Recherche des ecoles dans chaque ville trouvée
					$ecoles = Kernel::getTree( "BU_VILLE", $value->vil_id_vi );
					$tree->villes[$value->vil_id_vi]->ecoles = $ecoles->ecoles;
				}
				break;
			case "BU_VILLE" : // Recherche des écoles dans une ville...
				$eco_dao = _dao("kernel|kernel_tree_eco");
				$eco_list = $eco_dao->getByVille($racine_node);
				$tree->ecoles = array();
				foreach ($eco_list AS $key=>$value) {
					$tree->ecoles[$value->eco_numero]->info["nom" ] = $value->eco_nom;
					$tree->ecoles[$value->eco_numero]->info["type"] = $value->eco_type;
					$tree->ecoles[$value->eco_numero]->info["level"] = 0+Kernel::getLevel( 'BU_ECOLE', $value->eco_numero );
					// Recherche des classes dans chaque école trouvée
					$classes = Kernel::getTree( "BU_ECOLE", $value->eco_numero );
					$tree->ecoles[$value->eco_numero]->classes = $classes->classes;
				}
				break;
			case "BU_ECOLE" : // Recherche des écoles dans une ville...
				$cla_dao = _dao("kernel|kernel_tree_claniv");
				$cla_list = $cla_dao->getByEcole($racine_node);
				$tree->classes = array();
				foreach ($cla_list AS $key=>$value) {
					$tree->classes[$value->cla_id]->info["nom"   ] = $value->cla_nom;
					$tree->classes[$value->cla_id]->info["niveau"] = $value->niv_niveau;
					$tree->classes[$value->cla_id]->info["type"  ] = $value->niv_type;
					$tree->classes[$value->cla_id]->info["level"] = 0+Kernel::getLevel( 'BU_CLASSE', $value->cla_id );
				}
				break;
		}
		
		reset($tree);
		return( $tree );
	}
	
	
	/**
	 * Nodes : Retourne les noeuds parents (ecole->ville)
	 * INPUT  : noeud (type+id)
	 * OUTPUT : noeuds (array type+id)
	 */
	function getNodeParents ( $type, $id ) {
    //print_r ("getNodeParents( $type, $id )<br>");
		//die();

		if (1) { //La donnee n’est pas en cache, on traite la demande.
		
		$return = array();
		switch( $type ) {
			case "BU_GRVILLE":
				$return[]=array("type"=>"ROOT", "id"=>0);
				break;
			
			case "BU_VILLE":
				// Ville --(1)--> Groupe de ville
				$vil_dao = _dao("kernel|kernel_tree_vil");
				if( $ville = $vil_dao->get($id) )
					$return[]=array("type"=>"BU_GRVILLE", "id"=>$ville->vil_id_grville);
				break;
			
			case "BU_ECOLE":
				// Ecole --(1)--> Ville
				$eco_dao = _dao("kernel|kernel_tree_eco");
				if( $ecole = $eco_dao->get($id) )
					$return[]=array("type"=>"BU_VILLE", "id"=>$ecole->eco_id_ville);
				break;
			
			case "BU_CLASSE":
				// Classe --(1)--> Ecole
				$cla_dao = _dao("kernel|kernel_tree_cla");
				if( $classe = $cla_dao->get($id) )
					$return[]=array("type"=>"BU_ECOLE", "id"=>$classe->cla_ecole);
				break;
			
			case "CLUB": // Voir la table des nodes
				// Groupe de travail --(1)--> Noeud (classe, ecole, etc.)
				$dao = _dao("kernel|kernel_link_groupe2node");
				if( $res = $dao->get($id) ) {
					$return[]=array("type"=>$res->node_type, "id"=>$res->node_id);
				}
				break;
			
			case "USER_ENS": // Enseignant --(n)--> Classes/Ecoles
			case "USER_VIL": // Agent de ville --(1?)--> Ville
			case "USER_ADM": // Administratif école --(n)--> Ecoles
				$dao = _dao("kernel|kernel_bu_personnel_entite");
				$res = $dao->getById($id);
				
				foreach( $res AS $key=>$val ) {
					switch( $val->pers_entite_type_ref ) {
						case "ECOLE":
							// Enseignant --(n)--> Ecoles (directeur)
							$role2droit = array(1=>PROFILE_CCV_WRITE,2=>PROFILE_CCV_ADMIN,3=>PROFILE_CCV_WRITE);
							$return[]=array("type"=>"BU_ECOLE", "id"=>$val->pers_entite_reference,"droit"=>$role2droit[$val->pers_entite_role]);
							
							// Patch pour accès directeur dans les classes
							if( CopixConfig::exists('|conf_DirClasse') && CopixConfig::get('|conf_DirClasse') )
							{
								// Enseignant --(n)--> Classes (directeur)
								$node_list = Kernel::getNodeChilds( "BU_ECOLE", $val->pers_entite_reference );
								$classe_list = Kernel::filterNodeList( $node_list, "BU_CLASSE" );
								if( sizeof($classe_list) ) foreach( $classe_list AS $classe )
								{
									$return[]=array("type"=>"BU_CLASSE", "id"=>$classe['id'],"droit"=>PROFILE_CCV_ADMIN);
								}
							}
							
							break;
						case "CLASSE":
							// Enseignant --(n)--> Classes
							$role2droit = array(1=>PROFILE_CCV_ADMIN);
							$return[]=array("type"=>"BU_CLASSE", "id"=>$val->pers_entite_reference,"droit"=>$role2droit[$val->pers_entite_role]);
							break;
						case "VILLE":
							$role2droit = array(4=>PROFILE_CCV_ADMIN);
							$return[]=array("type"=>"BU_VILLE", "id"=>$val->pers_entite_reference,"droit"=>$role2droit[$val->pers_entite_role]);
							break;
						case "GVILLE":
							$role2droit = array(5=>PROFILE_CCV_ADMIN);
							$return[]=array("type"=>"BU_GRVILLE", "id"=>$val->pers_entite_reference,"droit"=>$role2droit[$val->pers_entite_role]);
							break;
					}
				}
				break;
							
			case "USER_ELE":
				// Eleve --(n)--> Classes
				$dao = _dao("kernel|kernel_bu_ele_affect");
				$res = $dao->getByEleve($id);
				foreach( $res AS $key=>$val ) {
					$return[]=array("type"=>"BU_CLASSE", "id"=>$val->affect_classe,"droit"=>PROFILE_CCV_WRITE);
				}
				// Ecole (lecture) ?
				// ? Pour toutes les classes, ajouter les parents de "USER_ELE_CLASSE,X"
				break;
			
			// Utilisateurs locaux (hors BU) --(n)--> Noeuds (ecoles, classes, clubs, etc.)
			case "USER_EXT":
				$dao = _dao("kernel|kernel_link_user2node");
				$res = $dao->getByUser($type,$id);
				foreach( $res AS $key=>$val ) {
					if( ereg( "^BU_(.+)$", $val->node_type, $regs ) )
						$return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$val->droit);
					if( ereg( "^ROOT$", $val->node_type ) )
						$return[]=array("type"=>$val->node_type, "id"=>0,"droit"=>$val->droit);
				}
				// PNL - en dur, chef de Grandville (id1)
				// en réalité il faut balayer tout ce qui est attaché à ce user EXT
				// $return[]=array("type"=>"BU_VILLE", "id"=>1,"droit"=>99);
				
				// Les USER_EXT sont attaches par defaut a ROOT/0 sans droits
				$return[]=array("type"=>"ROOT", "id"=>0,"droit"=>0);
				break;

			default:
				break;
		}

		// Responsable --(n)--> Bénéficiaire (limité à parent --> enfants)
		if( $type=="USER_RES" /* || $type="ORGANISME" */ ) {
			$dao = _dao("kernel|kernel_bu_res2ele");
			$res = $dao->getByResponsable($type, $id);
			foreach( $res AS $key=>$val ) {
				if( $val->res2ele_type_beneficiaire != "eleve" ) continue;
				$return[]=array("type"=>"USER_ELE", "id"=>$val->res2ele_id_beneficiaire, "droit"=>($val->res2ele_auth_parentale?PROFILE_CCV_ADMIN:PROFILE_CCV_READ), "res2ele_type"=>$val->res2ele_type_beneficiaire, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale);
			}
		}
				
		if( ereg( "^USER_(.+)$", $type, $regs ) ) {
			$dao = _dao("kernel|kernel_link_user2node");
			$res = $dao->getByUser($type,$id);
			foreach( $res AS $key=>$val ) {

				// Utilisateurs --(n)--> Groupes de travail (clubs)
				if( 1 || $val->node_type=="CLUB" ) {
					$ok = true;
					if ($val->debut && $val->debut>date("Ymd")) $ok = false;
					if ($val->fin   && $val->fin  <date("Ymd")) $ok = false;
					$droit = ($ok) ? $val->droit : 19; // CB Remplacer 30 par constante
					$return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$droit);
				}

				// Utilisateurs --(n)--> Modules
				/*
				if( ereg( "^MOD_(.+)$", $val->node_type ) ) {
					$ok = true;
					if ($val->debut && $val->debut>date("Ymd")) $ok = false;
					if ($val->fin   && $val->fin  <date("Ymd")) $ok = false;
					$droit = ($ok) ? $val->droit : 0; // CB Remplacer 30 par constante
					$return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$droit);
				}
				*/
			}
		}
		
		// Ajoute les infos aux données sur les enfants
		foreach( $return AS $key=>$val ) {
			$infos = Kernel::getNodeInfo( $val['type'], $val['id'], false );
			if( $infos ) // Vérifie qu'il y a des infos...
				foreach( $infos AS $info_key=>$info_val )
					if( !isset($return[$key][$info_key]) ) // Evite les effacements...
						$return[$key][$info_key] = $info_val;
		}
		
		}

		reset($return);
		return $return;
	}

	/**
	 * getNodeChilds
	 *
	 * Retourne la liste des noeuds dépendant d'un noeud (enfants).
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $type Type du noeud parent.
	 * @param integer $id Identifiant du noeud parent.
	 * @return array Liste des noeuds, chacun sous forme (type,id). 
	 */
	function getNodeChilds( $type, $id, $addchildinfo=true ) {
		
		
		
		$return=array();
		switch( $type ) {
			case "ROOT":
				// Racine --(n)--> Groupes de villes
				$dao = _dao("kernel|kernel_tree_grv");
				$res = $dao->findAll();
				foreach ($res AS $key=>$value) {
					$return[]=array("type"=>"BU_GRVILLE", "id"=>$value->grv_id_grv);
				}
				break;
			case "BU_GRVILLE":
				if( isset($this->cache_getNodeChilds_grville[$id]) ) {
					$return = $this->cache_getNodeChilds_grville[$id];
				} else {
					// Groupe de ville --(n)--> Villes
					$dao = _dao("kernel|kernel_tree_vil");
					
					$res = $dao->getByGroupeVille($id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"BU_VILLE", "id"=>$val->vil_id_vi);
					}
					// Groupe de Ville --(n)--> Agents de ville
					$dao = _dao("kernel|kernel_bu_personnel_entite");
					$res = $dao->getByRef("GVILLE",$id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"USER_VIL", "id"=>$val->pers_entite_id_per);
					}
					
					$this->cache_getNodeChilds_grville[$id] = $return;
				}
				break;
			case "BU_VILLE":
				if( isset($this->cache_getNodeChilds_ville[$id]) ) {
					$return = $this->cache_getNodeChilds_ville[$id];
				} else {
					// Ville --(n)--> Ecoles
					$dao = _dao("kernel|kernel_tree_eco");
					$res = $dao->getByVille($id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"BU_ECOLE", "id"=>$val->eco_numero);
					}
					// Ville --(n)--> Agents de ville
					$dao = _dao("kernel|kernel_bu_personnel_entite");
					$res = $dao->getByRef("VILLE",$id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"USER_VIL", "id"=>$val->pers_entite_id_per);
					}
					
					$this->cache_getNodeChilds_ville[$id] = $return;
				}
				break;
			case "BU_ECOLE":
				if( isset($this->cache_getNodeChilds_ecole[$id]) ) {
					$return = $this->cache_getNodeChilds_ecole[$id];
				} else {
					// Ecole --(n)--> Classes
					$dao = _dao("kernel|kernel_tree_cla");
					$res = $dao->getByEcole($id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"BU_CLASSE", "id"=>$val->cla_id);
					}
					// Ecole --(n)--> Enseignants
					$dao = _dao("kernel|kernel_bu_personnel_entite");
					$res = $dao->getByRef("ECOLE",$id);
					foreach( $res AS $key=>$val ) {
						switch( $val->pers_entite_role ) {
							case "1": // Enseignant
							case "2": // Directeur
								$return[]=array("type"=>"USER_ENS", "id"=>$val->pers_entite_id_per);
								break;
							case "3": // Personnel administratif
								$return[]=array("type"=>"USER_ADM", "id"=>$val->pers_entite_id_per);
								break;
						}
					}
					
					$this->cache_getNodeChilds_ecole[$id] = $return;
				}
				break;
			case "BU_CLASSE":
				if( isset($this->cache_getNodeChilds_classe[$id]) ) {
					$return = $this->cache_getNodeChilds_classe[$id];
				} else {

					// Classe --(n)--> Elèves
					$dao = _dao("kernel|kernel_bu_ele_affect");
					$res = $dao->getByClasse($id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"USER_ELE", "id"=>$val->affect_eleve);
					}
					// Classe --(n)--> Enseignants
					$dao = _dao("kernel|kernel_bu_personnel_entite");
					$res = $dao->getByRef("CLASSE",$id);
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"USER_ENS", "id"=>$val->pers_entite_id_per);
					}
					//print_r($return);
					$this->cache_getNodeChilds_classe[$id] = $return;
				}
				break;
			
			/*
			case "USER_ELE": // A FINIR !!!
				// Bénéficiaire --(n)--> Responsable
				$dao = _dao("kernel|kernel_bu_res2ele");
				$res = $dao->getByBeneficiaire("USER_ELE", $id);
				foreach( $res AS $key=>$val ) {
					$return[]=array("type"=>"USER_RES", "id"=>$val->res2ele_id_responsable, "res2ele_type"=>$val->res2ele_type, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale);
				}
				break;
			*/
		}

		// Bénéficiaire --(n)--> Responsable (limité à enfant --> parents)
		if( $type=="USER_ELE" /* || $type=="USER_RES" */ ) {
			$dao = _dao("kernel|kernel_bu_res2ele");
			$res = $dao->getByBeneficiaire("USER_ELE", $id);
			//print_r($res);
			foreach( $res AS $key=>$val ) {
				if( $val->res2ele_type_responsable != "responsable" ) continue;
				$return[]=array("type"=>"USER_RES", "id"=>$val->res2ele_id_responsable, "res2ele_type"=>$val->res2ele_type_beneficiaire, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale);
			}
		}
		
		// Noeud (classe, ecole, etc.) + clubs --(n)--> Utilisateurs ext.
		if( !ereg( "^USER_(.+)$", $type, $regs ) ) {
			$dao = _dao("kernel|kernel_link_user2node");
			$res = $dao->getByNode($type,$id);
			foreach( $res AS $key=>$val ) {
				$return[]=array("type"=>$val->user_type, "id"=>$val->user_id,"droit"=>$val->droit, "debut"=>$val->debut, "fin"=>$val->fin);
			}
		}
		if( ereg( "^BU_(.+)$", $type, $regs ) ) {
			// Noeud (classe, ecole, etc.) --(n)--> Groupes de travail
			$dao = _dao("kernel|kernel_link_groupe2node");
			$res = $dao->getByNode($type,$id);
			foreach( $res AS $key=>$val ) {
				$return[]=array("type"=>"CLUB", "id"=>$val->groupe_id);
			}

			// Groupes
		}
		
		// Ajoute les personnes extérieures (même non liées à un noeud) à la racine
		if( $type=='ROOT' ) {
			
			$return_add = array();
			$userext_old = array();
			foreach( $return AS $user ) {
				if( $user['type']=='USER_EXT' ) $userext_old[$user['id']] = true;
			}
			
			$userext_dao = & _dao("kernel|kernel_ext_user");
			$userext_list = $userext_dao->findAll();
			
			foreach( $userext_list as $userext_key=>$userext_val ) {
				if( isset($userext_old[$userext_val->ext_id]) ) continue;
				$userext_new = array(
					'type' => 'USER_EXT',
					'id' => $userext_val->ext_id,
					'droit' => 0,
					'debut' => '',
					'fin' => '',
/*

					user_id' => '',
					'login' => '',
					'nom' => $userext_val->ext_nom,
					'prenom' => $userext_val->ext_prenom,
					'ALL' => $userext_val,
*/
				);
				
				$return_add[] = $userext_new;
			}
			$return = array_merge($return, $return_add);
			// Kernel::MyDebug($return);
		}
		
		if( $addchildinfo ) {
			// Ajoute les infos aux données sur les enfants
			foreach( $return AS $key=>$val ) {
		 		$infos = Kernel::getNodeInfo( $val['type'], $val['id'], false );
				if( $infos ) // Vérifie qu'il y a des infos...
					foreach( $infos AS $info_key=>$info_val )
						if( !isset($return[$key][$info_key]) ) // Evite les effacements...
							$return[$key][$info_key] = $info_val;
			}
		}
		//print_r($return);
		reset($return);
		return $return;
	}
	
	/**
	 * filterNodeList
	 *
	 * Filtre une liste des noeuds en fonction d'un type.
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $node_list Liste de noeuds.
	 * @param string $node_type Type de noeud.
	 * @return array Liste des noeuds, filtré sur le type, renuméroté.
	 */
	function filterNodeList( $node_list, $node_type ) {
		$liste_filtree = array();
		
		if( ereg('([^\*]+)\*',$node_type,$regs) && isset($regs[1]) && trim($regs[1])!='' ) {
			foreach( $node_list AS $key=>$val ) {
				if( 0==strncmp($val['type'],$regs[1],strlen($regs[1])) )
					$liste_filtree[] = $val;
			}
		} else {
			foreach( $node_list AS $key=>$val ) {
				if( $val['type'] == $node_type )
					$liste_filtree[] = $val;
			}
		}
		
		return $liste_filtree;
	}
	
	
	/**
	 * filterModuleList
	 *
	 * Filtre une liste des noeuds en fonction d'un type.
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @param array $module_list Liste de modules
	 * @param string $module_type Type de module
	 * @return array Liste des noeuds, filtré sur le type, renuméroté.
	 */
	function filterModuleList( $module_list, $module_type ) {
		//print_r($module_list);
		$liste_filtree = array();
		
		if( ereg('([^\*]+)\*',$module_type,$regs) && isset($regs[1]) && trim($regs[1])!='' ) {
			foreach( $module_list AS $key=>$val ) {
				if( 0==strncmp($val->module_type,$regs[1],strlen($regs[1])) )
					$liste_filtree[] = $val;
			}
		} else {
			foreach( $module_list AS $key=>$val ) {
				if( $val->module_type == $module_type )
					$liste_filtree[] = $val;
			}
		}
		
		return $liste_filtree;
	}
	
	/**
	 * sortNodeList
	 *
	 * Tri une liste des noeuds par type.
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $node_list Liste de noeuds.
	 * @return array Liste des noeuds, trié par type, renuméroté.
	 */
	function sortNodeList( $node_list, $col='type', $ordre=SORT_ASC ) {
		
		if( $col == 'comptes' ) {

			function sortNodeList_compare($a, $b) 
			{
				$types = array('USER_VIL','USER_ENS','USER_ADM','USER_ELE','USER_RES','USER_EXT');
				$types = array_flip( $types );
				
				if ($a['type'] == $b['type']) {
					if (!isset($a['nom']) || !isset($b['nom']) || $a['nom'] == $b['nom']) {
						if (!isset($a['prenom']) || !isset($b['prenom']) || $a['prenom'] == $b['prenom']) {
							return 0;
						}
						return (Kernel::simpleName($a['prenom']) > Kernel::simpleName($b['prenom'])) ? 1 : -1;
					}
					return (Kernel::simpleName($a['nom']) > Kernel::simpleName($b['nom'])) ? 1 : -1;
				}
				return ($types[$a['type']] > $types[$b['type']]) ? 1 : -1;
				// comparaison des types.
			}
			
	
			usort( $node_list, "sortNodeList_compare" );
			return( $node_list );
		}
		
		if( is_array($ordre) ) {
			$node_list_tri = array();
			
			foreach ($ordre as $element) {
				foreach ($node_list as $key=>$row) {
					// Kernel::MyDebug($row);
					if( $row[$col] == $element ) {
						$node_list_tri[] = $row;
						unset($node_list[$key]);
					}
				}
			}
			foreach ($node_list as $key=>$row) {
				$node_list_tri[] = $row;
			}
			$node_list = $node_list_tri;
		} else {
			$nodes_type = array();
			foreach ($node_list as $key => $row) {
				$nodes_type[$key]  = $row[$col];
			}
			array_multisort($nodes_type, $ordre, $node_list);
		}
		
		return $node_list;
	}
	
	/**
	 * uniqNodeList
	 * 
	 * Elimine les doublons d'une liste des noeuds.
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $node_list Liste de noeuds.
	 * @return array Liste des noeuds.
	 */
	function uniqNodeList( $node_list )
	{
		$old = array();
		$node_list_new = array();
		foreach( $node_list AS $key=>$val ) {
			if( ! isset($old[$val['type']."-".$val['id']]) ) {
				$node_list_new[] = $val;
				$old[$val['type']."-".$val['id']] = true;
			}
		}
		return( $node_list_new );
	}
	
	
	/**
	 * setClubParent
	 *
	 * Attache un club ($club_id) à un noeud ($node_type, $node_id).
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param integer $club_id Identifiant du club.
	 * @param string $node_type Type de noeud.
	 * @param integer $node_id Identifiant du noeud.
	 */
	function setClubParent( $club_id, $node_type, $node_id ) {
		$dao = _dao("kernel|kernel_link_groupe2node");
		$nouveau = _record("kernel|kernel_link_groupe2node");
		$nouveau->groupe_id = $club_id;
		$nouveau->node_type = $node_type;
		$nouveau->node_id = $node_id;
		$dao->insert( $nouveau );
	}


	/**
	 * getNodeInfo
	 *
	 * Retourne les information d'un noeud ($type, $id).
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $type Type de noeud.
	 * @param integer $id Identifiant du noeud.
	 * @param boolean $addparents Ajouter les infos sur les parents (vrai par défaut).
	 */
	function getNodeInfo( $type, $id, $addparents=true ) {
		$return = array();
		switch( $type ) {
			case "BU_GRVILLE":
				$dao = _dao("kernel|kernel_tree_grv");
				if( $result = $dao->get($id) ) {
					$return["type"] = $type;
					$return["id"] = $id;
					$return["nom"] = $result->grv_nom_groupe;
					$return["crea"] = $result->grv_date_creation;
					$return["ALL"] = $result;
				}
				break;
			case "BU_VILLE":
				if( isset($this->cache_getNodeInfo_ville[$id]) ) {
					$return = $this->cache_getNodeInfo_ville[$id];
				} else {
					$dao = _dao("kernel|kernel_tree_vil");
					if( $result = $dao->get($id) ) {
						$return["type"] = $type;
						$return["id"] = $id;
						$return["nom"] = $result->vil_nom;
						$return["crea"] = $result->vil_date_creation;
						$return["ALL"] = $result;
						
						if( $addparents ) {
							// Recherche des parents
							$parents = Kernel::getNodeParents( $type, $id );
							$parent = Kernel::filterNodeList( $parents, "BU_GRVILLE" );
							if( sizeof($parent) > 0 ) {
								$return["parent"] = $parent[0];
							}
						}
					}
					
					$this->cache_getNodeInfo_ville[$id] = $return;
				}
				break;
			case "BU_ECOLE":
				if( isset($this->cache_getNodeInfo_ecole[$id]) ) {
					$return = $this->cache_getNodeInfo_ecole[$id];
				} else {
					$dao = _dao("kernel|kernel_tree_eco");
					if( $result = $dao->get($id) ) {
						$return["type"] = $type;
						$return["id"] = $id;
						$return["nom"] = $result->eco_nom;
						$return["ALL"] = $result;
						//if( $result->eco_type ) $return["desc"] = CopixI18N::get ('kernel|kernel.message.ecole')." ".$result->eco_type;
						$return["desc"] = ($result->eco_type) ? $result->eco_type : null;
						
						if( $addparents ) {
							// Recherche des parents
							$parents = Kernel::getNodeParents( $type, $id );
							$parent = Kernel::filterNodeList( $parents, "BU_VILLE" );
							if( sizeof($parent) > 0 ) {
								$return["parent"] = $parent[0];
							}
						}
					}
						
					$this->cache_getNodeInfo_ecole[$id] = $return;
				}
				break;
			case "BU_CLASSE":
				if( isset($this->cache_getNodeInfo_classe[$id]) ) {
					$return = $this->cache_getNodeInfo_classe[$id];
				} else {
					$dao = _dao("kernel|kernel_tree_cla");
					if( $result = $dao->get($id) ) {
						$return["type"] = $type;
						$return["id"] = $id;
						$return["nom"] = $result->cla_nom;
						$return["ALL"] = $result;
						
						if( $addparents ) {
							// Recherche des parents
							$parents = Kernel::getNodeParents( $type, $id );
							$parent = Kernel::filterNodeList( $parents, "BU_ECOLE" );
							if( sizeof($parent) > 0 ) {
								$return["parent"] = $parent[0];
							}
						}
					}
							
					$this->cache_getNodeInfo_classe[$id] = $return;
				}
				break;
			case "CLUB":
				$dao = _dao("groupe|groupe");
				if( $result = $dao->get($id) ) {
					$return["type"] = $type;
					$return["id"] = $id;
					$return["nom"] = $result->titre;
					if( $result->description ) $return["desc"] = $result->description;
					$return["crea"] = $result->date_creation;
					$return["ALL"] = $result;
				}
				break;
			case "USER_ENS":
			case "USER_RES":
			case "USER_ELE":
			case "USER_EXT":
			case "USER_VIL":
			case "USER_ADM":
				$return = Kernel::getUserInfo( $type, $id );
				break;
			case "MOD_TELEPROCEDURES": // CB 26/01/2009 pour teleprocedures
				$return = array();
				$return["type"] = $type;
				$return["id"] = $id;
				break;
			default:
				break;
		}
		
		if( $return ) {
			$return["type"] = $type;
			$return["id"] = $id;
			return $return;
		} else return null;
	}




	function getUserInfo( $type="ME", $id=0 ) {
		//Kernel::deb("getUserInfo / type=$type / id=$id");
		$user = array();
		switch( $type ) {
			case "ID":
				$user_dao = _dao("kernel|kernel_bu2user");
				$users = $user_dao->getByUserID($id);
				break;
			case "LOGIN":
				$user_dao = _dao("kernel|kernel_bu2user");
				$users = $user_dao->getByLogin($id);
				break;
			case "ME":
				if( Kernel::is_connected() )
					return( Kernel::getUserInfo( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') ) );
			default:
				$user_dao = _dao("kernel|kernel_bu2user");
				$users = $user_dao->getByBUID($type,$id);
				if (count($users)) {
					$users[0]->bu_type = $type;
					$users[0]->bu_id   = $id;
				} else {
					$record = _record("kernel|kernel_bu2user");
					$record->bu_type = $type;
					$record->bu_id   = $id;
					$users = array();
					$users[0] = $record;
				}
				break;
		}
		//print_r($users);
		
		if( sizeof( $users ) ) {
			// foreach( $users as $key => $userval ) {
			$userval = $users[0];
			$user["type"]     = $users[0]->bu_type;
			$user["id"]       = $users[0]->bu_id;
			if(isset($users[0]->user_id)) $user["user_id"]       = $users[0]->user_id;
			if(isset($users[0]->user_login)) $user["login"]       = $users[0]->user_login;
			
				switch( $userval->bu_type ) {
					case "USER_VIL" :
					case "USER_ENS" :
					case "USER_ADM" :
						$pers_dao = _dao("kernel|kernel_bu_personnel");
						$personne = $pers_dao->get($userval->bu_id);
						$user["nom"]      = $personne->pers_nom;
						$user["prenom"]   = $personne->pers_prenom1;
						$user["civilite"] = $personne->pers_civilite;
						$user["sexe"]     = $personne->pers_id_sexe;
						$user["cle_privee"]     = $personne->pers_cle_privee;
						$user["ALL"]      = $personne;
						
						$pers_entite_dao = _dao("kernel|kernel_bu_personnel_entite");
						$pers_entites = $pers_entite_dao->getById($userval->bu_id);
						foreach ($pers_entites AS $key=>$value) {
							switch( $value->pers_entite_type_ref ) {
								case "VILLE":
									$user["link"]->ville[$value->pers_entite_reference] = $value->pers_entite_role;
									break;
								case "ECOLE":
									$user["link"]->ecole[$value->pers_entite_reference] = $value->pers_entite_role;
									break;
								case "CLASSE":
									$user["link"]->classe[$value->pers_entite_reference] = $value->pers_entite_role;
									break;
							}
						}
						break;

					case "USER_ELE" :
						$ele_dao = _dao("kernel|kernel_bu_ele");
						$eleve = $ele_dao->get($userval->bu_id);
						// $user["type"]     = "USER_ELE";
						// $user["id"]       = $eleve->ele_idEleve;
						$user["nom"]      = $eleve->ele_nom;
						$user["prenom"]   = $eleve->ele_prenom1;
						$user["civilite"] = $eleve->ele_civilite;
						$user["sexe"]     = $eleve->ele_id_sexe;
						$user["ALL"]      = $eleve;
						
						$parents = Kernel::getNodeParents("USER_ELE", $userval->bu_id);
						foreach ($parents AS $key=>$value) {
							switch( $value['type'] ) {
								case "BU_CLASSE":
									$user["link"]->classe[$value['id']] = 1;
									$user["link"]->ecole[$value['ALL']->cla_ecole] = 1;
									$user["link"]->ville[$value['ALL']->eco_id_ville] = 1;
									break;
							}
						}

						break;

					case "USER_RES" :
						$res_dao = _dao("kernel|kernel_bu_res");
						$reponsable = $res_dao->get($userval->bu_id);
						// $user["type"]     = "USER_RES";
						// $user["id"]       = $reponsable->res_numero;
						$user["nom"]      = $reponsable->res_nom;
						$user["prenom"]   = $reponsable->res_prenom1;
						$user["civilite"] = $reponsable->res_civilite;
						$user["sexe"]     = $reponsable->res_id_sexe;
						$user["ALL"]      = $reponsable;
						break;

					case "USER_EXT" :
						$ext_dao = _dao("kernel|kernel_ext_user");
						$extuser = $ext_dao->get($userval->bu_id);
						// attention id user = celui de la basu
						// $user["type"]     = "USER_EXT";
						// $user["id"]       = $extuser->ext_id;
						$user["nom"]      = $extuser->ext_nom;
						$user["prenom"]   = $extuser->ext_prenom;
						$user["ALL"]      = $extuser;
						break;

					default :
						break;
				} // switch( $userval->bu_type )
			// } // foreach( $users as $key => $userval )
		} // if( sizeof( $users ) )
		return( $user );
	}



	function getMyParents( $racine_type="USER", $racine_node=0, $options=array() ) {
		$tree = array(
			"direct"=>array(),
			"linked"=>array(),
		);
		
		if( $racine_type=="USER" && $racine_node==0 )
		{
			if (isset($options['bu_type']) && isset($options['bu_id'])) {
				$racine_type=$options['bu_type'];
				$racine_node=$options['bu_id'];
			} elseif( (_currentUser()->getExtra('type')) && (_currentUser()->getExtra('id')) )
			{
				
				$racine_type=_currentUser()->getExtra('type');
				$racine_node=_currentUser()->getExtra('id');
			} else {

				return( $tree );
			}
		}
		
		$tmp = Kernel::getNodeParents( $racine_type, $racine_node );
		foreach( $tmp as $key => $val ) {
			if(isset($val["droit"])) $tree["direct"][$val["type"]][$val["id"]] = $val["droit"];
			else                     $tree["linked"][$val["type"]][$val["id"]] = 0;
			$follow = Kernel::getMyParents( $val["type"], $val["id"] );
			$tree["direct"] = array_merge_recursive( $tree["direct"], $follow["direct"] );
			$tree["linked"] = array_merge_recursive( $tree["linked"], $follow["linked"] );
		}
		
		return( $tree );
	}

	/**
	 * Modules : Retourne la liste des modules disponibles pour un noeud
	 * INPUT  : ... ou user(type)
	 * OUTPUT : 
	 */
	function getModAvailable( $type ) {
		$result = array();
		$dao = _dao("kernel|kernel_mod_available");
		$list = $dao->getByNode($type);
		foreach ($list as $r)
			$result[] = $r;
		
		if( ereg( "(.*)_(.*)", $type, $regs ) ) {
			$list = $dao->getByNode( $regs[1]."_%" );
			foreach ($list as $r)
				$result[] = $r;
		}
		return $result;
	}

	/**
	 * getModEnabled
	 *
	 * Retourne la liste des modules attachés à un noeud, et en option, ceux dépendant du type d'utilisateur.
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param string  $node_type Type de noeud.
	 * @param integer $node_id   Identifiant du noeud.
	 * @param string  $user_type Type d'utilisateur (facultatif).
	 * @param integer $user_id   Identifiant du noeud (facultatif).
	 */
	function getModEnabled( $node_type, $node_id, $user_type='', $user_id=0 ) {
		//echo "getModEnabled( $node_type, $node_id, $user_type, $user_id)";
		
		$dao = _dao("kernel|kernel_mod_enabled");
		$modules = array();
		
		$list = $dao->getByNode($node_type,$node_id);
		//print_r($modules);
		
		foreach ($list as $v) {
			$v->module_nom	 = Kernel::Code2Name ($v->module_type);
			$modules[] = $v;
		}
		
		//print_r($modules);
		
		if( $user_type == "USER_ENS" &&
		    $node_type == "BU_CLASSE" &&
			Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$carnetcorresp->node_type   = $node_type;
			$carnetcorresp->node_id     = $node_id;
			$carnetcorresp->module_type = 'MOD_CARNET';
			$carnetcorresp->module_id   = 'CLASSE_'.$node_id;
			$carnetcorresp->module_nom	 = Kernel::Code2Name ('MOD_CARNET');
			$modules[] = $carnetcorresp;
		}
		
				

		if( CopixConfig::exists('|conf_ModTeleprocedures') && CopixConfig::get('|conf_ModTeleprocedures')==0 )
		{
			// Pas de module de téléprocédures...
		} else {
			if( $user_type == "USER_ENS" &&
			    $node_type == "BU_ECOLE" &&
				Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
				$teleprocedures->node_type   = $node_type;
				$teleprocedures->node_id     = $node_id;
				$teleprocedures->module_type = 'MOD_TELEPROCEDURES';
				$teleprocedures->module_id   = 'ECOLE_'.$node_id;
				$teleprocedures->module_nom   = Kernel::Code2Name ('MOD_TELEPROCEDURES');
				$modules[] = $teleprocedures;
			} /* elseif( $user_type == "USER_VIL" &&
			    $node_type == "BU_VILLE" &&
				Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
				$teleprocedures->node_type   = $node_type;
				$teleprocedures->node_id     = $node_id;
				$teleprocedures->module_type = 'MOD_TELEPROCEDURES';
				$teleprocedures->module_id   = 'VILLE_'.$node_id;
				$teleprocedures->module_nom   = Kernel::Code2Name ('MOD_TELEPROCEDURES');
				$modules[] = $teleprocedures;
			}
			*/
		}
		
		if( (
			($user_type == "USER_EXT" && $node_type == "ROOT") ||
			($user_type == "USER_ENS" && $node_type == "BU_ECOLE") ||
			($user_type == "USER_VIL" && $node_type == "BU_VILLE")
			) &&
			Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$comptes->node_type   = $node_type;
			$comptes->node_id     = $node_id;
			$comptes->module_type = 'MOD_COMPTES';
			$comptes->module_id = $node_type.'-'.$node_id;
			$comptes->module_nom   = Kernel::Code2Name ('MOD_COMPTES');
			$modules[] = $comptes;
		}
		
		// Cas particuliers : modules personnels sans numéros
		if( 0 == strncmp($node_type,"USER_",5) ) {
			$perso_list = array( 'MOD_ANNUAIRE', 'MOD_MINIMAIL', 'MOD_GROUPE', 'MOD_RESSOURCE' );
			foreach( $perso_list AS $perso_module ) {
			  $perso->node_type   = $node_type;
			  $perso->node_id     = $node_id;
				$perso->module_type = $perso_module;
				$perso->module_nom   = Kernel::Code2Name ($perso_module);
				$modules[] = $perso;
				unset ($perso);
			}
		}
		
		// Cas particulier : module d'administration
		if( $user_type=='USER_EXT' && $user_id==1 ) {
			$admin->node_type   = $node_type;
			$admin->node_id     = $node_id;
			$admin->module_type = 'MOD_ADMIN2';
			$admin->module_nom   = Kernel::Code2Name ('MOD_ADMIN');
			$modules[] = $admin;
		}
		
		// Cas ENS+VIL : SSO vers Gael si tout est configuré.
		$SsoGaelService = & CopixClassesFactory::Create ('ssogael|ssogael');
		if( (
			($user_type == "USER_ENS" && $node_type == "BU_ECOLE") ||
			($user_type == "USER_VIL" && $node_type == "BU_VILLE")
			) &&
			method_exists( $SsoGaelService, "canSsoGael" ) &&
			$SsoGaelService->canSsoGael() &&
			Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$comptes->node_type   = $node_type;
			$comptes->node_id     = $node_id;
			$comptes->module_type = 'MOD_SSOGAEL';
			$comptes->module_id = $node_type.'-'.$node_id;
			$comptes->module_nom   = Kernel::Code2Name ('MOD_SSO_GAEL');
			$comptes->module_popup = true; // Mode Popup !!!
			$modules[] = $comptes;
		}
		
		// Cas particulier : gestion des groupes de ville (AC/TICE)
		if(    $user_type == "USER_EXT"
		    && $node_type == "ROOT"
		    && Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$mod_grvilles->node_type   = $node_type;
			$mod_grvilles->node_id     = $node_id;
			$mod_grvilles->module_type = 'MOD_GRVILLES';
			$mod_grvilles->module_id = $node_type.'-'.$node_id;
			$mod_grvilles->module_nom   = Kernel::Code2Name ('MOD_GRVILLES');
			$modules[] = $mod_grvilles;
		}

		reset($modules);
		return $modules;
	}

	function getModParent( $type, $id ) {
		//echo "getModParent ($type,$id)";
		$dao = _dao("kernel|kernel_mod_enabled");
		$list = $dao->getByModule($type,$id);
		$result = array();
		if ($list) {
			foreach ($list as $r) {
				if (!count($result)) {
					$result = $r;
				}
			}
		}
		if ($result && substr($result->node_type,0,4) == 'MOD_')
			$result = Kernel::getModParent($result->node_type,$result->node_id);
		return $list;
	}
	
	function getModParentInfo( $type, $id ) {
		//echo "getModParentInfo ($type,$id)";
		$dao = _dao("kernel|kernel_mod_enabled");
		$result = $dao->getByModule($type,$id);
		//die();
		if( count( $result ) ) {
			$node = $result[0];
			$info = Kernel::getNodeInfo( $node->node_type, $node->node_id, false );
			if ($info) {
				if ($info["type"]=="CLUB")
					$info["module"] = "groupe";
				else // Todo corriger
					$info["module"] = "kernel";
			}
			return( $info );
		}
		return false;
	}
	
	
	function getModRight( $mod_type, $mod_id, $user_type="-1", $user_id="-1" ) {
		//echo "getModRight( $mod_type, $mod_id, $user_type, $user_id )";
		$droit=0;
		if ($mod_type == "MOD_MINIMAIL") {
			return (_currentUSer()->isConnected()) ? PROFILE_CCV_ADMIN : 0;
		}

		if( $user_type=="-1" && $user_id=="-1" ) {
			if( (_currentUser()->getExtra('type')) && (_currentUser()->getExtra('id')) ) {
				$user_type=_currentUser()->getExtra('type'); $user_id=_currentUser()->getExtra('id');
			} else {
				return 0;
			}
		}
		
		$mod_parents  = Kernel::getModParent( $mod_type, $mod_id );
		$user_parents = Kernel::getNodeParents( $user_type, $user_id );
		
		// Rustine CB 23/01/2009 pour les droits des directeurs dans les teleprocedures
		if ($mod_type == 'MOD_TELEPROCEDURES' && $user_type == 'USER_ENS') {
			//print_r($mod_parents);
			//print_r($user_parents);
			$villeMod = ($mod_parents[0]->node_type == 'BU_VILLE') ? $mod_parents[0]->node_id : null;
			// S'il est bien directeur d'une ecole dans cette ville
			foreach ($user_parents as $parent) {
				if ($parent['type'] == 'BU_ECOLE' && $parent['droit']>=PROFILE_CCV_ADMIN && $parent['ALL']->eco_id_ville == $villeMod)
					return PROFILE_CCV_READ;
			}
		}
		// print_r("getModRight( $mod_type, $mod_id, $user_type, $user_id)<br/>");
		
		//print_r($mod_parents);
		
		foreach( $mod_parents AS $mod_key=>$mod_val ) {
			// Check user -> admin
			if( $mod_val->node_type==$user_type && $mod_val->node_id==$user_id ) $droit=PROFILE_CCV_ADMIN;
			
			// Check parents nodes -> right (DB)
			reset( $user_parents );
			//foreach( $user_parents AS $user_key=>$user_val ) {
			while ($user_val = array_pop($user_parents)) {
        
				// Rustine 1 : les gens rattachés à une classe ont le même droit dans l'école de la classe
				if ($user_val["type"] == "BU_CLASSE") {
					$ecoles = Kernel::getNodeParents( $user_val["type"], $user_val["id"] );
					foreach ($ecoles as $ecole_key=>$ecole_val) {
						$ecoles[$ecole_key]["droit"] = PROFILE_CCV_READ;
						$user_parents[] = $ecoles[$ecole_key];
					}
				}

				// Rustine 2 : les parents ont des droits de lecture sur les classes de leur enfants
				if ($user_val["type"] == "USER_ELE" && $user_type == "USER_RES") {
					$nodes_all = Kernel::getNodeParents( $user_val["type"], $user_val["id"] );
					//print_r($nodes_all);
					foreach ($nodes_all as $node) {
						if ($node['type'] != 'BU_CLASSE') continue;
						$node['droit'] = PROFILE_CCV_READ;
						$user_parents[] = $node;
						//print_r($node);
						/*
						$child['classe'] = $node['nom'];
						$modules = Kernel::getModEnabled(
						$node['type'], $node['id'],
						$child["type"],   $child["id"]   );
						*/
						//print_r($modules);
					}
				}
        
        

				if( $mod_val->node_type==$user_val["type"] && $mod_val->node_id==$user_val["id"] ) $droit=max($droit,$user_val["droit"]);
        
				//$droit = min();
        
			}
		}
		
		$dao = _dao("kernel|kernel_link_user2node");
		$res = $dao->getByUser($user_type, $user_id);
		foreach( $res AS $key=>$val ) {
			// Utilisateurs --(n)--> Modules
			// if( ereg( "^MOD_(.+)$", $val->node_type ) ) {
			if( $val->node_type == $mod_type && $val->node_id == $mod_id ) {
				$ok = true;
				if ($val->debut && $val->debut>date("Ymd")) $ok = false;
				if ($val->fin   && $val->fin  <date("Ymd")) $ok = false;
				$droit_module = ($ok) ? $val->droit : 0;
				
				$droit=max($droit,$droit_module);
			}
		}
		
		// die( "USER=".$user_type."/".$user_id."<br />"."MOD=".$mod_type."/".$mod_id."<br />".$droit."<pre>".print_r( $mod_parents, true )."</pre><hr /><pre>".print_r( $user_parents, true )."</pre>" );

		return( $droit );
	}
	
	
	
	
	function getMyNodes($bu_type=null, $bu_id=null) {

		$bu_type = (!$bu_type) ? _currentUser()->getExtra('type') : $bu_type;
		$bu_id = (!$bu_id) ? _currentUser()->getExtra('id') : $bu_id;
		
		$cache_type = 'getmynodes';
		$cache_id = $bu_type.'-'.$bu_id;
		
		if (!CopixCache::exists($cache_id, $cache_type)) { //La donnee n’est pas en cache, on traite la demande.
		
			//var_dump("getMyNodes / type=$type / id=$id");
			$data = array();
			
			$data[0]->title = "Modules perso...";
			$data[0]->type = $bu_type;
			$data[0]->id = $bu_id;
			$data[0]->droit = 70;
			$data[0]->enabled = Kernel::getModEnabled( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
			$data[0]->available_type = Kernel::getModAvailable( _currentUser()->getExtra('type') );
			
			/* $data[0]->enabled = array_merge( $data[0]->enabled, $data[0]->available_type ); */
	
	// die( "<pre>".print_r( $data[0]->enabled, true )."</pre>" );
	
			$i=1;
			$myTree = Kernel::getMyParents("USER", 0, array('bu_type'=>$bu_type, 'bu_id'=>$bu_id));
			//print_r($myTree);
			//die();
			foreach( $myTree["direct"] AS $node_type=>$node_val ) {
				foreach( $node_val AS $node_id=>$droit ) {
					$data[$i]->title = "Node ".$node_type."/".$node_id;
					$data[$i]->type = $node_type;
					$data[$i]->id = $node_id;
					$data[$i]->droit = $droit;
					$data[$i]->enabled = Kernel::getModEnabled( $node_type, $node_id, $bu_type, $bu_id );
					$data[$i]->available_type = Kernel::getModAvailable( $node_type );
					
					/* $data[$i]->enabled = array_merge( $data[$i]->enabled, $data[$i]->available_type ); */
					
					$i++;
				}
			}
			CopixCache::write ($cache_id, serialize($data), $cache_type);
		} else { // En cache
			$data = unserialize(CopixCache::read($cache_id, $cache_type));
		}
		
		return $data;
	}
	
	// Si on passe le tebleau $extra, on ne touche pas a la session (utilise a la connexion, quand on n'a pas encore la session PHP) - CB
	function setMyNode( $type, $id, &$extra=array() ) {
		if (isset($extra) && count($extra)) {
			$extra['home'] = array();
			$extra['home']["type"] = $type;
			$extra['home']["id"] = $id;
			$nodeinfo = Kernel::getNodeInfo( $type, $id, false );
			$extra['home']["titre1"] = $nodeinfo["nom"];
			$parent = Kernel::getNodeParents( $type, $id );
			if( count($parent) ) {
				//print_r($parent);
				$parent_item = current($parent);
				$parentinfo = Kernel::getNodeInfo( $parent_item["type"], $parent_item["id"], false );
				$extra['home']["titre2"] = $parentinfo["nom"];
			}
		} else {
			_currentUser()->setExtraHome('type', $type);
			_currentUser()->setExtraHome('id', $id);
			$nodeinfo = Kernel::getNodeInfo( $type, $id, false);
			_currentUser()->setExtraHome('titre1', $nodeinfo["nom"]);
			$parent = Kernel::getNodeParents( $type, $id );
			if( count($parent) ) {
				$parent_item = current($parent);
				$parentinfo = Kernel::getNodeInfo( $parent_item["type"], $parent_item["id"], false );
				_currentUser()->setExtraHome('titre2', $parentinfo["nom"]);
			}
		}
	}
	
	
	function MyDebug( $var ) {
		die( "<pre>".print_r($var,true)."</pre>" );
	}
	
	
	// Retourne les parents d'un utilisateur, mais UNIQUEMENT les noeuds (pas les clubs)
	function getMyHomes() {
		$myParents = Kernel::getMyParents();
		$myHomes = array();
		
		foreach( $myParents['direct'] AS $bu_type=>$bu_val ) {
			if( ereg( "^BU_(.+)$", $bu_type )
			 || ereg( "^ROOT$", $bu_type ) ) {
				foreach( $bu_val AS $bu_id=>$level ) {
					$myHomes[] = array('type'=>$bu_type, 'id'=>$bu_id, 'level'=>$level );
				}
			}
		}
		
		return( $myHomes );
	}
	
	function simpleName( $titre ) {
		$res = trim($titre);
		$tofind = " ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ()[]'~$&%*@!?;,:/\^¨€{}|+-";
		$replac = "-AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn--------------------------";
		$res =(strtr($res,$tofind,$replac));
		$res = strtolower($res);
		$res = ereg_replace("\"","-", $res);
		$res = ereg_replace ("[^a-z0-9\.-]", "-", $res);
		$res = ereg_replace("[-]{2,}", "-", $res);
		
		if (substr($res,0,1)=="-")	$res = substr($res,1);
		if (substr($res,-1,1)=="-")	$res = substr($res,0,strlen($res)-1);
		$res = trim($res);
		return( $res );
	}

	function createMissingModules( $node_type, $node_id ) {
		$modavailable = Kernel::getModAvailable( $node_type );

		$modenabled = Kernel::getModEnabled($node_type, $node_id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
		
		
		
		$modinstalled = array();
		foreach( $modenabled AS $module ) {
			$modinstalled[] = strtolower( $module->module_type );
		}
		
		//var_dump($modinstalled);

		$nodeInfo = Kernel::getNodeInfo ($node_type, $node_id);
		foreach( $modavailable AS $module ) {
			//var_dump($module);
				
			if( ereg( "^MOD_(.+)$", $module->module_type, $modinfo ) ) {
				$modname = strtolower( $modinfo[1] );
				
				if( array_search("mod_".$modname, $modinstalled)===false ) {
					$file     = & CopixSelectorFactory::create($modname."|".$modname);
					$filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
					//var_dump($filePath);
					if (is_readable($filePath)){
						$modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
						if( method_exists( $modservice, "create" ) ) {
							$subtitle = ($node_type=='BU_ECOLE' && isset($nodeInfo['ALL']->eco_type)) ? $nodeInfo['ALL']->eco_type : '';
							$modid = $modservice->create(array('title'=>trim($nodeInfo['prenom'].' '.$nodeInfo['nom']), 'subtitle'=>$subtitle, 'node_type'=>$node_type, 'node_id'=>$node_id));
							if( $modid != null ) {
								Kernel::registerModule( $module->module_type, $modid, $node_type, $node_id );
							}
						}
					}
				}
			}
		}
	}

	/**
	 * getSessionBU
	 *
	 * Retourne les information de l'utilisateur courant (depuis la Session)
	 * @return	array	tableau contenant les informations sur l'entité courante
	 * @author	Frédéric Mossmann
	 */
	function getSessionBU() {
		return _currentUser()->getExtras();
	}

	/**
	 * getSessionHome
	 *
	 * Retourne un tableau contenant les informations sur l'entité de rattachement courant
	 * @return	array	tableau contenant les informations sur l'entité courante
	 * @author	Frédéric REISS
	 * @since	15.12.2005
	 */
	function getSessionHome () {
		$getExtraHome = _currentUser()->getExtraHome('');
		return ($getExtraHome) ? $getExtraHome : array();
	}

	
	function Code2Name( $code ) {
		return( CopixI18N::get ('kernel|kernel.codes.'.strtolower($code) ) );
	}
	function Code2Desc( $code ) {
		return( CopixI18N::get ('kernel|kernel.codes.'.strtolower($code).'.desc' ) );
	}

	function PetitPoucet( $data, $separateur = " :: " ) {
		if( is_array($data) ) {
			$out = '';
			$sep = '';
			foreach( $data AS $key=>$val ) {
				$out .= $sep; $sep=$separateur;
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '<a href="'.$val['url'].'">';
				$out .= $val['txt'];
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '</a>';
			}
		} else $out=$data;
		return( $out );
	}

	function whereAmI( $node_type=false, $node_id=false ) {
		if( $node_type==false || $node_id==false ) {
			$node_type=_currentUser()->getExtraHome("type");
			$node_id=_currentUser()->getExtraHome("id");
		}
		
		$where = array();
		
		if( $node_type == 'BU_CLASSE' ) {
			$where['BU_CLASSE']['type'] = $node_type;
			$where['BU_CLASSE']['id'  ] = $node_id;
			
			$infos = Kernel::getNodeInfo( $node_type, $node_id );
			$where['BU_CLASSE']['nom' ] = $infos['nom'];
			
			$parents = Kernel::getNodeParents( $node_type, $node_id );
			$parent  = Kernel::filterNodeList( $parents, 'BU_ECOLE' );
			
			$node_type = $parent[0]["type"];
			$node_id   = $parent[0]["id"];
		}
		
		if( $node_type == 'BU_ECOLE' ) {
			$where['BU_ECOLE']['type'] = $node_type;
			$where['BU_ECOLE']['id'  ] = $node_id;
			
			$infos = Kernel::getNodeInfo( $node_type, $node_id );
			$where['BU_ECOLE']['nom' ] = $infos['nom'];
			
			$parents = Kernel::getNodeParents( $node_type, $node_id );
			$parent  = Kernel::filterNodeList( $parents, 'BU_VILLE' );
			
			$node_type = $parent[0]["type"];
			$node_id   = $parent[0]["id"];
		}
		
		if( $node_type == 'BU_VILLE' ) {
			$where['BU_VILLE']['type'] = $node_type;
			$where['BU_VILLE']['id'  ] = $node_id;
			
			$infos = Kernel::getNodeInfo( $node_type, $node_id );
			$where['BU_VILLE']['nom' ] = $infos['nom'];
			
			$parents = Kernel::getNodeParents( $node_type, $node_id );
			$parent  = Kernel::filterNodeList( $parents, 'BU_GRVILLE' );
			
			$node_type = $parent[0]["type"];
			$node_id   = $parent[0]["id"];
		}
		
		if( $node_type == 'BU_GRVILLE' ) {
			$where['BU_GRVILLE']['type'] = $node_type;
			$where['BU_GRVILLE']['id'  ] = $node_id;
			
			$infos = Kernel::getNodeInfo( $node_type, $node_id );
			$where['BU_GRVILLE']['nom' ] = $infos['nom'];
		}
		
		return $where;
	}



	/**
	 * Détermine si l'usager courant est un élève
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/06/15
	 * @return bool True si c'est un élève, false sinon
	 */
	function isEleve () {
		return (_currentUser()->getExtra('type') == 'USER_ELE');
	}

	/**
	 * Détermine si l'usager courant est un parent
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/06/15
	 * @return bool True si c'est un parent, false sinon
	 */
	function isParent () {
		return (_currentUser()->getExtra('type') == 'USER_RES');
	}


	/**
	 * Détermine si l'usager courant est un enseignant ou directeur
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/06/15
	 * @return bool True si c'est un enseignant ou directeur, false sinon
	 */
	function isEnseignant () {
		return (_currentUser()->getExtra('type') == 'USER_ENS');
	}

	/**
	 * Détermine si l'usager courant est un agent de ville
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/06/15
	 * @return bool True si c'est un agent de ville, false sinon
	 */
	function isAgentVille () {
		return (_currentUser()->getExtra('type') == 'USER_VIL');
	}

	/**
	 * Détermine si l'usager courant est un administrateur
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2007/02/19
	 * @return bool True si c'est un administrateur, false sinon
	 */
	function isAdmin () {
		return ( Kernel::getLevel("ROOT",0) >= 70 );
	}

   /**
   * Détermine si l'usager courant est du personnel administratif
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/01/24
	 * @return bool True si c'est un personnel administratif, false sinon
   */
	function isPersonnelAdministratif () {
		return (_currentUser()->getExtra('type') == 'USER_ADM');
	}

  // $options : 
	function menuReturntoParent ( $module_type, $module_id, $options=array()) {
		//$parents = Kernel::getModParent( $module_type, $module_id );
    $parent = Kernel::getModParentInfo ( $module_type, $module_id );
		//var_dump($parent);
		//if( sizeof($parents) ) {
		if ( $parent ) {
			//$parent = $parents[0];
      //print_r($parent);
			/*
			$parent->node_type] => CLUB
			$parent->node_id
			*/
			switch( $parent['type'] ) {
				case 'CLUB':
					$return['txt'] = CopixI18N::get('kernel|kernel.returnto.club' );
					$return['url'] = CopixUrl::get ('groupe||getHome', array('id'=>$parent['id']) );
					$return['node_type'] = $parent['type'];
					$return['node_id'] = $parent['id'];
					$return['node_name'] = $parent['nom'];
					break;

				case 'BU_GRVILLE':
				case 'BU_VILLE':
				case 'BU_ECOLE':
				case 'BU_CLASSE':
					$return['txt'] = CopixI18N::get('kernel|kernel.returnto.'.strtolower($parent['type']) );
					$return['url'] = CopixUrl::get ('kernel||doSelectHome', array('type'=>$parent['type'], 'id'=>$parent['id']) );
					$return['node_type'] = $parent['type'];
					$return['node_id'] = $parent['id'];
					$return['node_name'] = $parent['nom'];
					break;

				case 'USER_ENS':
				case 'USER_ADM':
				case 'USER_ELE':
				case 'USER_VIL':
				case 'USER_RES':
				case 'USER_EXT':
					$return['node_type'] = $parent['type'];
					$return['node_id'] = $parent['id'];
					$return['node_name'] = trim($parent['prenom'].' '.$parent['nom']);
					break;
        
				case 'MOD_TELEPROCEDURES':
					$return['txt'] = CopixI18N::get('kernel|kernel.returnto.'.strtolower($parent['type']) );
					$return['url'] = CopixUrl::get ('teleprocedures|admin|admin', array('id'=>$parent['id']) );
					$return['node_type'] = $parent['type'];
					$return['node_id'] = $parent['id'];
					break;

				default:
					$return = false;

			}
		}
		return $return;
	}
	
	/**
	 * Détermine si Iconito est en mode "démo" (accès limité et auto-login).
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/11/23
	 * @return bool True si Iconito est en mode "démo" (cf. config |isdemo)
	 */
	function isDemo() {
		return( CopixConfig::exists('default|isDemo') && CopixConfig::get('default|isDemo') );
	}
	
	function noDemo() {
		if( Kernel::isDemo() ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('kernel|kernel.error.nodemo'),
				       'TITLE_PAGE'=>CopixI18N::get ('kernel|kernel.error.demomode'),
				       'back'=>CopixUrl::get ('||')));
				//     'back'=>'javascript:history.go(-1);' ));
		}
		
	}
  
	/**
	 * Affichage d'une trace de debuggage
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/06
	 * @return 
	 */
	function deb ($txt) {
		echo '<div style="color:red;">'.$txt.'</div>';
	}

	/**
	 * Retourne la visibilité d'un type d'utilisateur
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/07
	 * @param string $dst Type personne à voir.
	 * @param string $src Type personne qui veut voir (option)
	 * @return string 'FULL', 'NONE' ou un niveau 'BU_VILLE', 'BU_ECOLE', etc.
	 */
	function getUserTypeVisibility( $dst, $src="ME" ) {
		if( $src=='ME' ) {
			if( (_currentUser()->getExtra('type')) ) {
				$src=_currentUser()->getExtra('type');
			}
			else return( 'NONE' );
		}

		$visibility_dao = _dao("kernel|kernel_conf_uservisibility");
		$visibility = $visibility_dao->getBySrcAndDst($src,$dst);

		if( count($visibility)>0 ) {
			return $visibility[0]->visibility;
		} else {
  			return 'NONE';
		}
  		// kernel_conf_uservisibility
	}

	/**
	 * Retourne la visibilité d'un utilisateur
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/07
	 * @param string $dst_type Type personne à voir.
	 * @param integer $dst_id Identifiant personne à voir.
	 * @param string $src_type Type personne qui veut voir (option). L'utilisateur connecté par défaut.
	 * @param integer $src_id Identifiant personne qui veut voir (option). L'utilisateur connecté par défaut.
	 * @return bool True si la visibilité est autorisée
	 * @todo Affiner la recherche si la visibilité est limité à un noeud (ville, ecole, etc.)
	 */
	function getUserVisibility( $dst_type, $dst_id, $src_type="ME", $src_id=0 ) {
		if( $src_type=='ME' ) {
			if( (_currentUser()->getExtra('type')) ) {
				$src_type=_currentUser()->getExtra('type');
				$src_id=_currentUser()->getExtra('id');
			}
			else return( false );
		}
		
		// Recherche la visibilité générique en fonction du type d'utilisateur.
		$type_visibility = Kernel::getUserTypeVisibility( $dst_type, $src_type );

		// Si c'est FULL ou NONE, on répond de suite. Sinon, on doit affiner la recherche (TODO)
		switch( $type_visibility ) {
			case 'FULL':
				return true;
			case 'NONE':
				return false;
			case 'BU_GRVILLE':
			case 'BU_VILLE':
			case 'BU_ECOLE':
			case 'BU_CLASSE':
			default:
				return true;
				break;
		}
	}
	
	/**
	 * Renvoie tous les modules développés. Parcourt le dossier modules
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/22
	 * @return array Tableau contenant les modules et leurs descriptif
	 */
	function getAllModules() {
		$all_modules = array();

		$arModulesPath = CopixConfig::instance ()->arModulesPath;
		foreach ($arModulesPath as $modulePath) {
			$dir = $modulePath;
			if (is_dir($dir)) {
	   		if ($dh = opendir($dir)) {
	      	while (($file = readdir($dh)) !== false) {
						if (is_dir($dir.$file) && $file!='.' && $file!='..' && $file!='CVS' && $file!='.svn') {
							$all_modules[$file] = $file;
						}
	       	}
	      	closedir($dh);
	   		}
			}
		}
		return $all_modules;
	}

	function breakLongWords($str, $maxLength, $char){
		$wordEndChars = array(" ", "\n", "\r", "\f", "\v", "\0");
		$count = 0;
		$newStr = "";
		$openTag = false;
		for($i=0; $i<strlen($str); $i++){
			$newStr .= $str{$i};   

			if($str{$i} == "<"){
				$openTag = true;
				continue;
			}
			if(($openTag) && ($str{$i} == ">")){
				$openTag = false;
				continue;
			}
       
			if(!$openTag){
				if(!in_array($str{$i}, $wordEndChars)){//If not word ending char
					$count++;
					if($count==$maxLength){//if current word max length is reached
						$newStr .= $char;//insert word break char
						$count = 0;
					}
				}else{//Else char is word ending, reset word char count
					$count = 0;
				}
			}
		}//End for   
    	return $newStr;
	}

	
	// Sur une date soumise, verifie le format et le corrige eventuellement
	function _validDateProperties ($value) {
		if (preg_match('/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{1,2})$/', $value, $regs)) {
			//Kernel::deb("value=$value");
			//print_r($regs);
			
			if (strlen($regs[1])==1)
				$regs[1] = '0'.$regs[1];
			if (strlen($regs[2])==1)
				$regs[2] = '0'.$regs[2];
			if (strlen($regs[3])==1)
				$regs[3] = '0'.$regs[3];
				
			if ($regs[3]<date('y')+10)
				$year = '20'.$regs[3];
			else
				$year = '19'.$regs[3];

			$value = $regs[1].'/'.$regs[2].'/'.$year;
			//Kernel::deb("value=$value");
		}
		return $value;
	}
	
	
	/**
	 * Detail d'un dossier : contrats
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/09/21
	 * @param integer $id Id de la demande
	 */
	function getTheme () {
		if (!$theme = CopixSession::get ('theme')) {
			$theme = 'default';
			self::setTheme($theme);
		}
		//var_dump($theme);
		return $theme;
	
	
	}
	function setTheme ($pTheme) {
		CopixSession::set ('theme', $pTheme);
	}
	
	
}

?>
