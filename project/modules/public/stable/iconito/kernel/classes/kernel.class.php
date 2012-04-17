<?php
/**
 * Kernel - Classes
 *
 * Fonctions du coeur d'Iconito : Gestion des utilisateurs, des liens avec les entitÈs, de l'accËs ‡ la base ÈlËve, des droits.
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: kernel.class.php,v 1.111 2009-08-31 09:59:53 fmossmann Exp $
 * @author	FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
 */

class Kernel {

	private $cache_getNodeChilds_ville = array();
	private $cache_getNodeChilds_ecole = array();
	private $cache_getNodeChilds_classe = array();

	private $cache_getNodeInfo_ville = array();
	private $cache_getNodeInfo_ecole = array();
	private $cache_getNodeInfo_classe = array();

	/**
	 * Test un utilisateur est connectÈ
	 * @return	boolean	true si un utilisateur est connectÈ, false sinon
	 * @author	FrÈdÈric REISS
	 * @since	15.12.2005
	 *
	 */
	function is_connected () {
		$user = _currentUser ();
		return ($user && $user->isConnected());
	}

	/**
	 * Test si un utilisateur est reconnecté en animateur
	 * @return	boolean	true si un utilisateur est connectÈ, false sinon
	 * @author	Frédéric MOSSMANN
	 * @since	13.11.2009
	 *
	 */
	function is_animateur () {
		$isAnim = _sessionGet('user_animateur');
		if ($isAnim != '') return true;
		return false;
	}

	/**
	 * ymd2dmy
	 *
	 * Transforme une date du format informatique (YYYY-MM-DD) en format franÁais (JJ/MM/YYYY).
	 * @author  FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $ymd Date au format informatique (YYYY-MM-DD).
	 * @return string Date au format franÁais (JJ/MM/YYYY).
	 */
	function ymd2dmy( $ymd ) {
		if( ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})", $ymd, $regs) ) {
			$dmy = $regs[3]."/".$regs[2]."/".$regs[1];
			return( $dmy );
		} else return( $ymd );
	}


  /**
   * 
   * @author Christophe Beyer <cbeyer@cap-tic.fr>
   * @param string $ymdhis Date Mysql.
   * @return string Date au format francais (JJ/MM/YYYY HHhII).
   */
  function ymdhis2dmyhi ($ymdhis) {
    if( preg_match ("/^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$/", $ymdhis, $regs) ) {
      $dmyhi = $regs[3]."/".$regs[2]."/".$regs[1]." ".$regs[4]."h".$regs[5];
      return( $dmyhi );
    } else return( $ymdhis );
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



		if( preg_match( "/^MOD_(.+)$/", $node_type ) ) {
			$level = Kernel::getModRight( $node_type, $node_id, $user_type, $user_id );
		} else {
			$tmp = Kernel::getNodeParents( $user_type, $user_id );
			foreach( $tmp as $key => $val ) {
				if( $val["type"]==$node_type && $val["id"]==$node_id )
				$level=$val["droit"];
			}
		}
		//Kernel::deb("getLevel=$level");
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
	 * Recherche rÈcursive du droit le plus fort d'un utilisateur sur un noeud.
	 *
	 * @return  integer Droit (entre 0 et 70).
	 * @author  FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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
	 * Fonction rÈcursive de parcours de l'arbre des noeuds, en remontant les parents, et concatÈnation de toutes les donnÈes.
	 * Ne pas utiliser directement, mais passer par getNodes.
	 *
	 * @return  array Liste des noeuds rencontrÈs, avec doublons.
	 * @author  FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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
	 * @author  FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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
					// Recherche des villes dans chaque groupe trouvÈ
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
					// Recherche des ecoles dans chaque ville trouvÈe
					$ecoles = Kernel::getTree( "BU_VILLE", $value->vil_id_vi );
					$tree->villes[$value->vil_id_vi]->ecoles = $ecoles->ecoles;
				}
				break;
			case "BU_VILLE" : // Recherche des Ècoles dans une ville...
				$eco_dao = _dao("kernel|kernel_tree_eco");
				$eco_list = $eco_dao->getByVille($racine_node);
				$tree->ecoles = array();
				foreach ($eco_list AS $key=>$value) {
					$tree->ecoles[$value->eco_numero]->info["nom" ] = $value->eco_nom;
					$tree->ecoles[$value->eco_numero]->info["type"] = $value->eco_type;
					$tree->ecoles[$value->eco_numero]->info["level"] = 0+Kernel::getLevel( 'BU_ECOLE', $value->eco_numero );
					// Recherche des classes dans chaque Ècole trouvÈe
					$classes = Kernel::getTree( "BU_ECOLE", $value->eco_numero );
					$tree->ecoles[$value->eco_numero]->classes = $classes->classes;
				}
				break;
			case "BU_ECOLE" : // Recherche des Ècoles dans une ville...
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

		if (1) { //La donnee níest pas en cache, on traite la demande.

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
				case "USER_ADM": // Administratif ecole --(n)--> Ecoles
					$dao = _dao("kernel|kernel_bu_personnel_entite");
					$res = $dao->getById($id);

					foreach( $res AS $key=>$val ) {
						switch( $val->pers_entite_type_ref ) {
							case "ECOLE":
								// Enseignant --(n)--> Ecoles (directeur)
								$role2droit = array(1=>PROFILE_CCV_WRITE,2=>PROFILE_CCV_ADMIN,3=>PROFILE_CCV_WRITE);
								$return[]=array("type"=>"BU_ECOLE", "id"=>$val->pers_entite_reference,"droit"=>$role2droit[$val->pers_entite_role]);
									
								// Patch pour acces directeur dans les classes
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
								$role2droit = array(1=>PROFILE_CCV_ADMIN, 2=>PROFILE_CCV_ADMIN);
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
					$alreadyOnRoot = false;
					$dao = _dao("kernel|kernel_link_user2node");
					$res = $dao->getByUser($type,$id);
					foreach( $res AS $key=>$val ) {
						if( ereg( "^BU_(.+)$", $val->node_type, $regs ) )
						$return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$val->droit);
						/*
						 if( ereg( "^ROOT$", $val->node_type ) ){
						 $return[]=array("type"=>$val->node_type, "id"=>0,"droit"=>$val->droit);
						 $alreadyOnRoot = true;
						 }
						 */
					}
					// PNL - en dur, chef de Grandville (id1)
					// en rÈalitÈ il faut balayer tout ce qui est attachÈ ‡ ce user EXT
					// $return[]=array("type"=>"BU_VILLE", "id"=>1,"droit"=>99);

					// Les USER_EXT sont attaches par defaut a ROOT/0 sans droits
					// if(!$alreadyOnRoot) $return[]=array("type"=>"ROOT", "id"=>0,"droit"=>0);

					break;

				default:
					break;
			}

			// Responsable --(n)--> BÈnÈficiaire (limitÈ ‡ parent --> enfants)
			if( $type=="USER_RES" /* || $type="ORGANISME" */ ) {
				$dao = _dao("kernel|kernel_bu_res2ele");
				$res = $dao->getByResponsable('responsable', $id);
				foreach( $res AS $key=>$val ) {
					if( $val->res2ele_type_beneficiaire != "eleve" ) continue;

					$parents = Kernel::getNodeParents( 'USER_ELE', $val->res2ele_id_beneficiaire );
					$parent = Kernel::filterNodeList( $parents, "BU_CLASSE" );

					$nom_classe = '';
					if(count($parent)) {
						if(!isset($parent[0]['nom'])) continue;
						$nom_classe = $parent[0]['nom'];
					}
					
					$return[]=array("type"=>"USER_ELE", "id"=>$val->res2ele_id_beneficiaire, "droit"=>($val->res2ele_auth_parentale?PROFILE_CCV_ADMIN:PROFILE_CCV_READ), "res2ele_type"=>$val->res2ele_type_beneficiaire, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale, "nom_classe"=>$nom_classe);
				}
			}

			if( preg_match( "/^USER_(.+)$/", $type ) ) {
				$dao = _dao("kernel|kernel_link_user2node");
				$res = $dao->getByUser($type,$id);
				
				foreach( $res AS $key=>$val ) {

					// Utilisateurs --(n)--> Groupes de travail (clubs)
					if($val->node_type=="CLUB" )
					{
						$ok = true;
						if ($val->debut && $val->debut>date("Ymd")) $ok = false;
						if ($val->fin   && $val->fin  <date("Ymd")) $ok = false;
						$droit = ($ok) ? $val->droit : 19; // CB Remplacer 30 par constante
						$return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$droit);
					}
					elseif( $val->node_type == "ROOT" )
					{
						$return[]=array("type"=>$val->node_type, "id"=>0,"droit"=>$val->droit);
					}
					elseif( ereg( "^BU_(.+)$", $val->node_type ) )
					{
						$ok = true;
						if ($val->debut && $val->debut>date("Ymd")) $ok = false;
						if ($val->fin   && $val->fin  <date("Ymd")) $ok = false;
						if($ok) $return[]=array("type"=>$val->node_type, "id"=>$val->node_id,"droit"=>$val->droit);
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

				// Rustine CB 25/08/2010 On ajoute un droit de lecture sur le groupe d'assistance
				if( CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance'))) {
					$return[]=array("type"=>'CLUB', "id"=>$groupeAssistance,"droit"=>PROFILE_CCV_READ);
					//print_r($return);
				}

			}

			// Ajoute les infos aux donnÈes sur les enfants
			foreach( $return AS $key=>$val ) {
				$infos = Kernel::getNodeInfo( $val['type'], $val['id'], false );
				if( $infos ) // VÈrifie qu'il y a des infos...
				foreach( $infos AS $info_key=>$info_val )
				if( !isset($return[$key][$info_key]) ) // Evite les effacements...
				$return[$key][$info_key] = $info_val;
			}

		}
		 
		// Suppression des classes dans les annees scolaires passees...
		foreach( $return AS $key=>$val ) {
			if($val['type']=='BU_CLASSE' && isset($val['ALL']) ) {
				if(
				!$val['ALL']->annee_current ||
				!$val['ALL']->cla_is_validee ||
				$val['ALL']->cla_is_supprimee
				) unset($return[$key]);
			}
		}
		// _dump($return);

		reset($return);
		return $return;
	}

	/**
	 * getNodeChilds
	 *
	 * Retourne la liste des noeuds dÈpendant d'un noeud (enfants).
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $type Type du noeud parent.
	 * @param integer $id Identifiant du noeud parent.
	 * @return array Liste des noeuds, chacun sous forme (type,id).
	 *
	 * $options :
	 * 'skip_user' => true : supprime les infos concernants les utilisateurs
	 */
	function getNodeChilds( $type, $id, $addchildinfo=true, $options=array() ) {



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

					//skip get user infos
					if(!isset($options['skip_user']) || !$options['skip_user']){
						// Groupe de Ville --(n)--> Agents de ville
						$dao = _dao("kernel|kernel_bu_personnel_entite");
						$res = $dao->getByRef("GVILLE",$id);
						foreach( $res AS $key=>$val ) {
							$return[]=array("type"=>"USER_VIL", "id"=>$val->pers_entite_id_per);
						}
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
					//skip get user infos
					if(!isset($options['skip_user']) || !$options['skip_user']){
						// Ville --(n)--> Agents de ville
						$dao = _dao("kernel|kernel_bu_personnel_entite");
						$res = $dao->getByRef("VILLE",$id);
						foreach( $res AS $key=>$val ) {
							$return[]=array("type"=>"USER_VIL", "id"=>$val->pers_entite_id_per);
						}
					}
					$this->cache_getNodeChilds_ville[$id] = $return;
				}
				break;
			case "BU_ECOLE":

				$cacheId = (isset($options['annee']) && $options['annee']) ? $id.'|'.$options['annee'] : $id;
				//Kernel::deb($cacheId);
					
				if( isset($this->cache_getNodeChilds_ecole[$cacheId]) ) {
					$return = $this->cache_getNodeChilds_ecole[$cacheId];
				} else {
					// Ecole --(n)--> Classes
					$dao = _dao("kernel|kernel_tree_cla");
						
					if (isset($options['annee']) && $options['annee'])
					$res = $dao->getByEcoleAnnee($id, $options['annee']);
					else
					$res = $dao->getByEcole($id);
					//var_dump($res);
						
						
					foreach( $res AS $key=>$val ) {
						$return[]=array("type"=>"BU_CLASSE", "id"=>$val->cla_id);
					}


					//skip get user infos
					if(!isset($options['skip_user']) || !$options['skip_user']){
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
					}
					$this->cache_getNodeChilds_ecole[$cacheId] = $return;
				}
				break;
				//complet class child (only users)
			case "BU_CLASSE":

				//skip get user infos
				if(!isset($options['skip_user']) || !$options['skip_user']){
					if( isset($this->cache_getNodeChilds_classe[$id]) ) {
						$return = $this->cache_getNodeChilds_classe[$id];
					} else {

						// Classe --(n)--> ElËves
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
				}
				break;
					
				/*
				 case "USER_ELE": // A FINIR !!!
				 // BÈnÈficiaire --(n)--> Responsable
				 $dao = _dao("kernel|kernel_bu_res2ele");
				 $res = $dao->getByBeneficiaire("USER_ELE", $id);
				 foreach( $res AS $key=>$val ) {
					$return[]=array("type"=>"USER_RES", "id"=>$val->res2ele_id_responsable, "res2ele_type"=>$val->res2ele_type, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale);
					}
					break;
					*/
		}

		//skip get user infos
		if(!isset($options['skip_user']) || !$options['skip_user']){

			// BÈnÈficiaire --(n)--> Responsable (limitÈ ‡ enfant --> parents)
			if( $type=="USER_ELE" /* || $type=="USER_RES" */ ) {
				$dao = _dao("kernel|kernel_bu_res2ele");
				$res = $dao->getByBeneficiaire("eleve", $id);
				//print_r($res);
				foreach( $res AS $key=>$val ) {
					if( $val->res2ele_type_responsable != "responsable" ) continue;
					$return[]=array("type"=>"USER_RES", "id"=>$val->res2ele_id_responsable, "res2ele_type"=>$val->res2ele_type_beneficiaire, "res2ele_auth_parentale"=>$val->res2ele_auth_parentale);
				}
			}
		}

		//skip get user infos
		if(!isset($options['skip_user']) || !$options['skip_user']){
			// Noeud (classe, ecole, etc.) + clubs --(n)--> Utilisateurs ext.
			if( !preg_match( "#^USER_(.+)$#", $type, $regs ) ) {
				$dao = _dao("kernel|kernel_link_user2node");
				$res = $dao->getByNode($type,$id);
				foreach( $res AS $key=>$val ) {
					$return[]=array("type"=>$val->user_type, "id"=>$val->user_id,"droit"=>$val->droit, "debut"=>$val->debut, "fin"=>$val->fin);
				}
			}
		}

		if( preg_match( "#^BU_(.+)$#", $type, $regs ) ) {
			// Noeud (classe, ecole, etc.) --(n)--> Groupes de travail
			$dao = _dao("kernel|kernel_link_groupe2node");
			$res = $dao->getByNode($type,$id);
			foreach( $res AS $key=>$val ) {
				$return[]=array("type"=>"CLUB", "id"=>$val->groupe_id);
			}
		}

		// Ajoute les personnes extÈrieures (mÍme non liÈes ‡ un noeud) ‡ la racine
		if( $type=='ROOT' ) {

			//skip get user infos
			if(!isset($options['skip_user']) || !$options['skip_user']){
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
		}

		//add info for each node
		if( $addchildinfo ) {
			// Ajoute les infos aux donnÈes sur les enfants
			foreach( $return AS $key=>$val ) {
				$infos = Kernel::getNodeInfo( $val['type'], $val['id'], false);
				if( $infos ) // VÈrifie qu'il y a des infos...
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
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $node_list Liste de noeuds.
	 * @param string $node_type Type de noeud.
	 * @return array Liste des noeuds, filtrÈ sur le type, renumÈrotÈ.
	 */
	function filterNodeList( $node_list, $node_type ) {
		$liste_filtree = array();

		if( preg_match('/([^\*]+)\*/',$node_type,$regs) && isset($regs[1]) && trim($regs[1])!='' ) {
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
	 * @return array Liste des noeuds, filtrÈ sur le type, renumÈrotÈ.
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
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param array $node_list Liste de noeuds.
	 * @return array Liste des noeuds, triÈ par type, renumÈrotÈ.
	 */
	function sortNodeList( $node_list, $col='type', $ordre=SORT_ASC ) {

		if( $col == 'comptes' ) {

			function sortNodeList_compare($a, $b)
			{
				$types = array('USER_VIL','USER_ENS','USER_ADM','USER_ELE','USER_RES','USER_EXT');
				$types = array_flip( $types );

				// echo "<li>".$a['type']." / ".$b['type']."</li>";
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
			/*
			 $nodes_type = array();
			 foreach ($node_list as $key => $row) {
				$nodes_type[$key]  = $row[$col];
				}
				*/
			function sortNodeList_compare($a, $b)
			{
				$types = array('ROOT', 'BU_CLASSE', 'BU_ECOLE', 'BU_VILLE', 'BU_GRVILLE', 'CLUB');
				$types = array_flip( $types );

				// echo "<li>".$a['type']." / ".$b['type']."</li>";
				if ($a['type'] == $b['type']) {
					if (!isset($a['nom']) || !isset($b['nom']) || $a['nom'] == $b['nom']) {
						return 0;
					}
					return ($a['nom'] > $b['nom']) ? 1 : -1;
				}

				if(!isset($types[$a['type']])||!isset($types[$b['type']])) return 0;
				return ($types[$a['type']] > $types[$b['type']]) ? 1 : -1;
			}
				
			usort( $node_list, "sortNodeList_compare" );
		}

		return $node_list;
	}

	/**
	 * uniqNodeList
	 *
	 * Elimine les doublons d'une liste des noeuds.
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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
	 * Attache un club ($club_id) ‡ un noeud ($node_type, $node_id).
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param string $type Type de noeud.
	 * @param integer $id Identifiant du noeud.
	 * @param boolean $addparents Ajouter les infos sur les parents (vrai par dÈfaut).
	 */
	function getNodeInfo( $type, $id, $addparents=true ) {
		$return = array();
		switch( $type ) {
			case "ROOT":
				$return["type"] = $type;
				$return["id"] = $id;
				$return["nom"] = 'Administration';
				break;
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
				if( isset($this->cache_getNodeInfo_ville[$id.'-'.($addparents?"parent":"noparent")]) ) {
					$return = $this->cache_getNodeInfo_ville[$id.'-'.($addparents?"parent":"noparent")];
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
						
					$this->cache_getNodeInfo_ville[$id.'-'.($addparents?"parent":"noparent")] = $return;
				}
				break;
			case "BU_ECOLE":
				if( isset($this->cache_getNodeInfo_ecole[$id.'-'.($addparents?"parent":"noparent")]) ) {
					$return = $this->cache_getNodeInfo_ecole[$id.'-'.($addparents?"parent":"noparent")];
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

					$this->cache_getNodeInfo_ecole[$id.'-'.($addparents?"parent":"noparent")] = $return;
				}
				break;
			case "BU_CLASSE":
				if( isset($this->cache_getNodeInfo_classe[$id.'-'.($addparents ? 'parent' : 'noparent' )]) ) {
					$return = $this->cache_getNodeInfo_classe[$id.'-'.($addparents?"parent":"noparent")];
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
						
					$this->cache_getNodeInfo_classe[$id.'-'.($addparents?"parent":"noparent")] = $return;
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



    /*
     * $options[strict] = true -> Si le user n'existe pas, renvoie false
     */
    function getUserInfo( $type = "ME", $id = 0, $options = array( ) )
    {
        //Kernel::deb("getUserInfo / type=$type / id=$id");
        $user = $users = array( );

        switch ( $type ) {
            case "ID":
                $user_dao = _dao( "kernel|kernel_bu2user" );
                $users = $user_dao->getByUserID( $id );
                break;
            case "LOGIN":
                $user_dao = _dao( "kernel|kernel_bu2user" );
                $users = $user_dao->getByLogin( $id );
                break;
            case "ME":
                if ( Kernel::is_connected() ) {
                    return( Kernel::getUserInfo( _currentUser()->getExtra( 'type' ), _currentUser()->getExtra( 'id' ) ) );
                }
            default:
                $user_dao = _dao( "kernel|kernel_bu2user" );
                $users = $user_dao->getByBUID( $type, $id );

                if ( count( $users ) ) {
                    $users[0]->bu_type = $type;
                    $users[0]->bu_id = $id;
                } else {
                    if ( !isset( $options['strict'] ) || $options['strict'] ) { // Si pas strict
                        $record = _record( "kernel|kernel_bu2user" );
                        $record->bu_type = $type;
                        $record->bu_id = $id;
                        $record->user_id = '';
                        $record->user_login = '';
                        $users = array( );
                        $users[0] = $record;
                    }
                }
                break;
        }

        if ( !sizeof( $users ) ) {
            if ( isset( $options['strict'] ) && $options['strict'] ) {
                return false;
            }
            return array_merge( $user, array( 'nom' => 'Utilisateur inconnu', 'prenom' => $type . ' ' . $id, 'login' => '', 'ALL' => null ) );
        } else {
            $userval = $users[0];
            $user["type"] = $users[0]->bu_type;
            $user["id"] = $users[0]->bu_id;
            if ( isset( $users[0]->user_id ) ) {
                $user["user_id"] = $users[0]->user_id;
            }
            if ( isset( $users[0]->user_login ) ) {
                $user["login"] = $users[0]->user_login;
            }

            switch ( $userval->bu_type ) {
                case "USER_VIL" :
                case "USER_ENS" :
                case "USER_ADM" :
                    $pers_dao = _dao( "kernel|kernel_bu_personnel" );
                    $personne = $pers_dao->get( $userval->bu_id );

                    if ( !$personne ) {
                        return array_merge( $user, array( 'nom' => 'Utilisateur inconnu', 'prenom' => $userval->bu_type . ' ' . $userval->bu_id, 'ALL' => null ) );
                    }

                    $user["nom"] = $personne->pers_nom;
                    $user["prenom"] = $personne->pers_prenom1;
                    $user["civilite"] = $personne->pers_civilite;
                    $user["sexe"] = $personne->pers_id_sexe;
                    $user["cle_privee"] = $personne->pers_cle_privee;
                    $user["ALL"] = $personne;

                    $pers_entite_dao = _dao( "kernel|kernel_bu_personnel_entite" );
                    $pers_entites = $pers_entite_dao->getById( $userval->bu_id );
                    foreach ( $pers_entites AS $key => $value ) {
                        switch ( $value->pers_entite_type_ref ) {
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
                    $ele_dao = _dao( "kernel|kernel_bu_ele" );
                    $eleve = $ele_dao->get( $userval->bu_id );
                    if ( !$eleve ) {
                        return array_merge( $user, array( 'nom' => 'Utilisateur inconnu', 'prenom' => $userval->bu_type . ' ' . $userval->bu_id, 'ALL' => null ) );
                    }
                    $user["nom"] = $eleve->ele_nom;
                    $user["prenom"] = $eleve->ele_prenom1;
                    $user["civilite"] = $eleve->ele_civilite;
                    $user["sexe"] = $eleve->ele_id_sexe;
                    $user["ALL"] = $eleve;

                    $parents = Kernel::getNodeParents( "USER_ELE", $userval->bu_id );
                    foreach ( $parents AS $key => $value ) {
                        switch ( $value['type'] ) {
                            case "BU_CLASSE":
                                $user["link"]->classe[$value['id']] = 1;
                                if (isset($value['ALL'])) {
                                    $user["link"]->ecole[$value['ALL']->cla_ecole] = 1;
                                    $user["link"]->ville[$value['ALL']->eco_id_ville] = 1;
                                }
                                if ( isset( $options['link_data'] ) && $options['link_data'] ) {
                                    $user["link_data"]->classe[$value['id']] = Kernel::getNodeInfo( "BU_CLASSE", $value['id'], true );
                                }
                                break;
                        }
                    }
                    break;

                case "USER_RES" :
                    $res_dao = _dao( "kernel|kernel_bu_res" );
                    $reponsable = $res_dao->get( $userval->bu_id );
                    if ( !$reponsable ) {
                        return array_merge( $user, array( 'nom' => 'Utilisateur inconnu', 'prenom' => $userval->bu_type . ' ' . $userval->bu_id, 'ALL' => null ) );
                    }
                    $user["nom"] = $reponsable->res_nom;
                    $user["prenom"] = $reponsable->res_prenom1;
                    $user["civilite"] = $reponsable->res_civilite;
                    $user["sexe"] = $reponsable->res_id_sexe;
                    $user["ALL"] = $reponsable;
                    $user['link'] = array();
                    
                    $parents = Kernel::getNodeParents ("USER_RES", $userval->bu_id);

                    foreach ( $parents AS $parent ) {
                        switch ( $parent['type'] ) {
                            case "USER_ELE":
                                if(isset($parent['link'])) foreach ($parent['link'] as $nodeType => $nodeValue) {
                                    if (!isset($user['link'][$nodeType])) {
                                        $user['link'][$nodeType] = array();
                                    }
                                    foreach ($nodeValue as $id => $value) {
                                        $user['link'][$nodeType][$id]= $value;
                                    }
                                }
                                break;
                        }
                    }
                    break;

                case "USER_EXT" :
                    $ext_dao = _dao( "kernel|kernel_ext_user" );
                    $extuser = $ext_dao->get( $userval->bu_id );
                    if ( !$extuser ) {
                        return array_merge( $user, array( 'nom' => 'Utilisateur inconnu', 'prenom' => $userval->bu_type . ' ' . $userval->bu_id, 'ALL' => null ) );
                    }
                    $user["nom"] = $extuser->ext_nom;
                    $user["prenom"] = $extuser->ext_prenom;
                    $user["ALL"] = $extuser;
                    $user['link'] = array();

                    $parents = Kernel::getNodeParents ("USER_EXT", $userval->bu_id);
                    foreach( $parents AS $parent) {
                        if (!isset($user['link'][$parent['type']])) {
                            $user['link'][$parent['type']] = array();
                        }
                        $user['link'][$parent['type']][$parent['id']] = $parent['droit'];
                    }
                    break;
                default :
                    break;
            } // switch( $userval->bu_type )
            // } // foreach( $users as $key => $userval )
        } // if( sizeof( $users ) )
        return( $user );
    }

    /**
     * CB 01/06/2010
     * Renvoie un tableau avec les droits de l'usager courant sur un noeud renvoye par getUserInfo
     */
	function getUserInfoMatrix ($userInfoCible) 
    {
		$matrix = & enic::get('matrixCache');
		$res = array('voir'=>false, 'communiquer'=>false);
        $userCible = array();
        
        /*
         * Déterminer les noeuds de rattachement du userCible
         * et ses profils sur chaque noeud
         * si USER_ENS, vérifier s'il s'agit d'un directeur ou non
         * $userCible['ecole'] = array (1 => 'USER_DIR')
         * $userCible['ecole'] = array (1 => 'USER_ENS')
         * $userCible['classe'] = array (1 => 'USER_ELE')
         * $userCible['classe'] = array (1 => 'USER_RES')
         * ...
         */
        foreach ($userInfoCible['link'] as $nodeType => $userInfo) {
            // ecole | classe | ville | ...
            if (!isset($userCible[$nodeType])) {
                $userCible[$nodeType] = array();
            }
            foreach ($userInfo as $nodeId => $role) {
                switch ($userInfoCible['type']) {
                    case 'USER_ENS':
                        if ($role == 2 && $nodeType = 'ecole') {
                            $userCible[$nodeType][$nodeId] = 'USER_DIR';
                        } else {
                            $userCible[$nodeType][$nodeId] = 'USER_ENS';
                        }
                        break;
                    case 'USER_EXT':
                    case 'USER_VIL':
                    case 'USER_ELE':
                    case 'USER_ADM':
                    case 'USER_RES':
                    default:
                        $userCible[$nodeType][$nodeId] = $userInfoCible['type'];
                        break;
                }
            }
        }

        /*
         * Pour chaque noeud de rattachement / profil du user cible, 
         * vérifie ses droits.
         */
        foreach ($userCible as $nodeType => $userInfo) {
            foreach ($userInfo as $nodeId => $role) {
                    $res['communiquer'] = ($matrix->$nodeType($nodeId)->_right->$role->communiquer) ? true : $res['communiquer'];
                    $res['voir'] = ($matrix->$nodeType($nodeId)->_right->$role->voir) ? true : $res['voir'];
            }
        }

        return $res;
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

		if( preg_match( "/(.*)_(.*)/", $type, $regs ) ) {
			$list = $dao->getByNode( $regs[1]."_%" );
			foreach ($list as $r)
			$result[] = $r;
		}
		return $result;
	}

	/**
	 * getModEnabled
	 *
	 * Retourne la liste des modules attachÈs ‡ un noeud, et en option, ceux dÈpendant du type d'utilisateur.
	 *
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @param string  $node_type Type de noeud.
	 * @param integer $node_id   Identifiant du noeud.
	 * @param string  $user_type Type d'utilisateur (facultatif).
	 * @param integer $user_id   Identifiant du noeud (facultatif).
	 */
	function getModEnabled( $node_type, $node_id, $user_type='', $user_id=0, $full=0 ) {
		// echo "getModEnabled( $node_type, $node_id, $user_type, $user_id)";

        $carnetDeLiaison = (CopixConfig::exists('kernel|carnetDeLiaison') && CopixConfig::get('kernel|carnetDeLiaison'));
		$dao = _dao("kernel|kernel_mod_enabled");
		$modules = array();

		// Parent d'eleve...
		if( 0 == strncmp($node_type,"USER_ELE",8) && 0 == strncmp($user_type,"USER_RES",8) ) {
			$parents = Kernel::getNodeParents( $node_type, $node_id );
			$parent  = Kernel::filterNodeList( $parents, 'BU_CLASSE' );
			if(count($parent)) {
				if( $parent[0]['droit']>=30 ) {
					$parent_modules = Kernel::getModEnabled( $parent[0]['type'], $parent[0]['id'], $node_type, $node_id );

					/*
					 echo '<li>$parent[0][] = '.$parent[0]['type']."/".$parent[0]['id']."</li>";
					 echo '<li>$node_* = '.$node_type."/".$node_id."</li>";
					 */

					foreach( $parent_modules AS $parent_module ) {
						/*
						 $perso->node_type   = $parent[0]['type'];
						 $perso->node_id     = $parent[0]['id'];
						 */
						$perso->node_type   = $node_type;
						$perso->node_id     = $node_id;

						$perso->module_type = $parent_module->module_type;
						$perso->module_id   = $parent_module->module_id;
						$perso->module_nom   = Kernel::Code2Name ($parent_module->module_type);
						$modules[] = clone $perso;
					}
						
					/*
					 $perso->node_type   = $parent[0]['type'];
					 $perso->node_id     = $parent[0]['id'];
					 */
					$perso->node_type   = $node_type;
					$perso->node_id     = $node_id;
						
					$perso->module_type = 'MOD_CARNET';
					$perso->module_id   = 'ELEVE_'.$node_id;
					$perso->module_nom   = Kernel::Code2Name ('MOD_CARNET');
                    if ($carnetDeLiaison)
                        $modules[] = clone $perso;

				}
			}
			// _dump($modules);
			reset($modules);
			return $modules;
		}
			
		$list = $dao->getByNode($node_type,$node_id);

		foreach ($list as $v) {
			if(!$full) if($v->module_type=='MOD_MAGICMAIL') continue;
			$v->module_nom	 = Kernel::Code2Name ($v->module_type);
			$modules[] = clone $v;
		}
		// _dump($modules);

		//print_r($modules);

		if( $user_type == "USER_ENS" &&
		$node_type == "BU_CLASSE" &&
		Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$carnetcorresp->node_type   = $node_type;
			$carnetcorresp->node_id     = $node_id;
			$carnetcorresp->module_type = 'MOD_CARNET';
			$carnetcorresp->module_id   = 'CLASSE_'.$node_id;
			$carnetcorresp->module_nom	 = Kernel::Code2Name ('MOD_CARNET');
			if ($carnetDeLiaison)
                $modules[] = clone $carnetcorresp;
		}

		//for KNE
		if(in_array($user_type, array('USER_ELE', 'USER_ENS', 'USER_DIR', 'USER_DID')) && $node_type == 'BU_CLASSE' && CopixClassesFactory::create('kne|kneService')->active){
			$modKne = new stdClass();
			$modKne->node_type = $node_type;
			$modKne->node_id = $node_id;
			$modKne->module_type = 'MOD_KNE';
			$modKne->module_id = $node_id;
			$modKne->module_nom = kernel::Code2Name('MOD_KNE');
			$modules[] = $modKne;
		}

                //for Coreprim
		if(in_array($user_type, array('USER_ELE', 'USER_ENS', 'USER_DIR', 'USER_DID')) && $node_type == 'BU_CLASSE' && CopixConfig::exists('default|rssEtagereEnabled') && CopixConfig::get('default|rssEtagereEnabled')){
                    $modRssEtagere = new stdClass();
			$modRssEtagere->node_type = $node_type;
			$modRssEtagere->node_id = $node_id;
			$modRssEtagere->module_type = 'MOD_RSSETAGERE';
			$modRssEtagere->module_id = $node_type."-".$node_id;
			$modRssEtagere->module_nom = kernel::Code2Name('MOD_RSSETAGERE');
			$modules[] = $modRssEtagere;
		}

		if( CopixConfig::exists('|conf_ModTeleprocedures') && CopixConfig::get('|conf_ModTeleprocedures')==0 )
		{
			// Pas de module de tÈlÈprocÈdures...
		} else {
			if( $user_type == "USER_ENS" &&
			$node_type == "BU_ECOLE" &&
			Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
				$teleprocedures->node_type   = $node_type;
				$teleprocedures->node_id     = $node_id;
				$teleprocedures->module_type = 'MOD_TELEPROCEDURES';
				$teleprocedures->module_id   = 'ECOLE_'.$node_id;
				$teleprocedures->module_nom   = Kernel::Code2Name ('MOD_TELEPROCEDURES');
				$modules[] = clone $teleprocedures;
			} elseif ( CopixConfig::exists('teleprocedures|USER_ADM_as_USER_ENS') && CopixConfig::get('teleprocedures|USER_ADM_as_USER_ENS') && $user_type == "USER_ADM" &&
			$node_type == "BU_ECOLE" &&
			Kernel::getLevel( $node_type, $node_id ) >= 30 ) {
				$teleprocedures->node_type   = $node_type;
				$teleprocedures->node_id     = $node_id;
				$teleprocedures->module_type = 'MOD_TELEPROCEDURES';
				$teleprocedures->module_id   = 'ECOLE_'.$node_id;
				$teleprocedures->module_nom   = Kernel::Code2Name ('MOD_TELEPROCEDURES');
				$modules[] = clone $teleprocedures;
			}
		}

		// Cas particuliers : modules personnels sans numÈros
		if( 0 == strncmp($node_type,"USER_",5) /* && 0 != strncmp($user_type,"USER_RES",8) */ ) {
			$perso_list = array( 'MOD_ANNUAIRE', 'MOD_MINIMAIL', 'MOD_GROUPE', 'MOD_RESSOURCE' );
			foreach( $perso_list AS $perso_module ) {
				$perso->node_type   = $node_type;
				$perso->node_id     = $node_id;
				$perso->module_id   = NULL;
				$perso->module_type = $perso_module;
				$perso->module_nom   = Kernel::Code2Name ($perso_module);
				$modules[] = clone $perso;
				unset ($perso);
			}
		}

		// Cas particulier : module d'administration
		if( $node_type == "ROOT" && Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$sysutils->node_type   = $node_type;
			$sysutils->node_id     = $node_id;
			$sysutils->module_id   = NULL;
			$sysutils->module_type = 'MOD_SYSUTILS';
			$sysutils->module_nom   = Kernel::Code2Name ('MOD_SYSUTILS');
			$modules[] = clone $sysutils;
				
			$charte->node_type   = $node_type;
			$charte->node_id     = $node_id;
			$charte->module_id   = NULL;
			$charte->module_type = 'MOD_CHARTE';
			$charte->module_nom   = Kernel::Code2Name ('MOD_CHARTE');
			$modules[] = clone $charte;
		}

		// Cas ENS+VIL : SSO vers Gael si tout est configurÈ.
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
			$modules[] = clone $comptes;
		}

		// Cas particulier : gestion des groupes de ville (AC/TICE)
		if( $node_type == "ROOT" && Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$mod_grvilles->node_type   = $node_type;
			$mod_grvilles->node_id     = $node_id;
			$mod_grvilles->module_type = 'MOD_REGROUPEMENTS';
			$mod_grvilles->module_id = $node_type.'-'.$node_id;
			$mod_grvilles->module_nom   = Kernel::Code2Name ('MOD_REGROUPEMENTS');
			$modules[] = clone $mod_grvilles;
		}

		// Cas particulier : Gestion autonome
		// if(    $user_type == "USER_EXT"
		//    && $node_type == "ROOT"
		//    && Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
		if( CopixConfig::exists('kernel|gestionAutonomeEnabled') && CopixConfig::get('kernel|gestionAutonomeEnabled') ) {
			if( (
			($node_type == "ROOT") ||
			($user_type == "USER_ENS" && $node_type == "BU_ECOLE") ||
			($user_type == "USER_ENS" && $node_type == "BU_CLASSE") ||
			($user_type == "USER_VIL" && $node_type == "BU_VILLE")
			) &&
			Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
				$mod_grvilles->node_type   = $node_type;
				$mod_grvilles->node_id     = $node_id;
				$mod_grvilles->module_type = 'MOD_GESTIONAUTONOME';
				$mod_grvilles->module_id = $node_type.'-'.$node_id;
				$mod_grvilles->module_nom   = Kernel::Code2Name ('MOD_GESTIONAUTONOME');
				$modules[] = clone $mod_grvilles;
			}
		} elseif( (
		($node_type == "ROOT") ||
		($user_type == "USER_ENS" && $node_type == "BU_ECOLE") ||
		($user_type == "USER_ENS" && $node_type == "BU_CLASSE") ||
		($user_type == "USER_VIL" && $node_type == "BU_VILLE")
		) &&
		Kernel::getLevel( $node_type, $node_id ) >= 60 ) {
			$comptes->node_type   = $node_type;
			$comptes->node_id     = $node_id;
			$comptes->module_type = 'MOD_COMPTES';
			$comptes->module_id = $node_type.'-'.$node_id;
			$comptes->module_nom   = Kernel::Code2Name ('MOD_COMPTES');
			$modules[] = clone $comptes;
		}

		if( $user_type=="USER_ENS" && (($node_type=="BU_ECOLE"&&Kernel::getLevel( $node_type, $node_id ) >= 60 )||$node_type=="BU_CLASSE") && CopixConfig::exists('default|conf_Ceriseprim_actif') && CopixConfig::get ('default|conf_Ceriseprim_actif') ) {
			$perso->node_type   = $node_type;
			$perso->node_id     = $node_id;
			
			$perso->module_type = 'MOD_CERISEPRIM';
			$perso->module_id   = $node_type."-".$node_id;
			$perso->module_nom   = Kernel::Code2Name ('MOD_CERISEPRIM');
			$modules[] = clone $perso;
		}
		
		// _dump($modules);

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
		// Kernel::deb ("getModRight( $mod_type, $mod_id, $user_type, $user_id )");
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
		// Rustine CB 21/09/2010 pour les administratifs dans les ecoles
		elseif ($mod_type == 'MOD_TELEPROCEDURES' && $user_type == 'USER_ADM' && CopixConfig::exists('teleprocedures|USER_ADM_as_USER_ENS') && CopixConfig::get('teleprocedures|USER_ADM_as_USER_ENS')) {
			$villeMod = ($mod_parents[0]->node_type == 'BU_VILLE') ? $mod_parents[0]->node_id : null;
			foreach ($user_parents as $parent) {
				if ($parent['type'] == 'BU_ECOLE' && $parent['droit']>=30 && $parent['ALL']->eco_id_ville == $villeMod)
				return PROFILE_CCV_READ;
			}
		}
		// Rustine CB 05/02/2010 pour les droits des agents de ville dans les teleprocedures
		elseif (isset($mod_parents[0]) && $mod_parents[0]->node_type == 'MOD_TELEPROCEDURES' && $user_type == 'USER_VIL') {
			return Kernel::getModRight ($mod_parents[0]->node_type, $mod_parents[0]->node_id, $user_type, $user_id);
		}


		//print_r("getModRight( $mod_type, $mod_id, $user_type, $user_id)<br/>");

		//print_r($mod_parents);

		foreach( $mod_parents AS $mod_key=>$mod_val ) {

			// Check user -> admin
			if( $mod_val->node_type==$user_type && $mod_val->node_id==$user_id ) $droit=PROFILE_CCV_ADMIN;

			// Rustine CB 25/08/2010 Si c'est un droit d'un module du groupe d'assistance
			if( CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance')) && $mod_val->node_type=='CLUB' && $mod_val->node_id==$groupeAssistance) {
				//print_r();
				switch ($mod_type) {
					case 'MOD_FORUM' : // Forum : on peut ecrire
						$droit = PROFILE_CCV_MEMBER;
						break;
					case 'MOD_MALLE' : // Malle : on peut lire
					case 'MOD_BLOG' : // Blog : on peut lire
						$droit = PROFILE_CCV_READ;
						break;
				}
			}
				
			// Check parents nodes -> right (DB)
			reset( $user_parents );
			//foreach( $user_parents AS $user_key=>$user_val ) {
			while ($user_val = array_pop($user_parents)) {

				// Rustine 1 : les gens rattachÈs ‡ une classe ont le mÍme droit dans l'Ècole de la classe
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

		if (!CopixCache::exists($cache_id, $cache_type)) { //La donnee níest pas en cache, on traite la demande.

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

  

	function MyDebug( $var, $die=false) {
		echo( "<pre>".print_r($var,true)."</pre>" );
		if ($die)
		die();
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
		$tofind = " ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
		$replac = "-AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
		
		// Patch UTF8
		$res = utf8_decode($res);
		$tofind = utf8_decode($tofind);
		
		
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

		$modenabled = Kernel::getModEnabled($node_type, $node_id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'), 1);

		$modinstalled = array();
		foreach( $modenabled AS $module ) {
			$modinstalled[] = strtolower( $module->module_type );
		}

		//var_dump($modinstalled);

		$nodeInfo = Kernel::getNodeInfo ($node_type, $node_id);


		if ($node_type == 'CLUB') {
			foreach( $modavailable AS $module ) {
				// echo "<li>".$module->module_type."</li>";
				if( $module->module_type == 'MOD_MAGICMAIL' ) {
					$modname = 'magicmail';
						
					// _dump($modinstalled);
						
					// if( array_search("mod_magicmail", $modinstalled)===false ) echo "magicmail ";
					// if( array_search("mod_blog", $modinstalled)!==false ) echo "blog ";
						
					if( array_search("mod_magicmail", $modinstalled)===false && array_search("mod_blog", $modinstalled)!==false ) {
						$file     = & CopixSelectorFactory::create($modname."|".$modname);
						$filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
						//var_dump($filePath);
						if (is_readable($filePath)){
							$modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
							if( method_exists( $modservice, "create" ) ) {
								$subtitle = ($node_type=='BU_ECOLE' && isset($nodeInfo['ALL']->eco_type)) ? $nodeInfo['ALL']->eco_type : '';
								$prenom = isset($nodeInfo['prenom']) ? $nodeInfo['prenom'] : '';
								$modid = $modservice->create(array('title'=>trim($prenom.' '.$nodeInfo['nom']), 'subtitle'=>$subtitle, 'node_type'=>$node_type, 'node_id'=>$node_id));
								if( $modid != null ) {
									// _dump( array( $module->module_type, $modid, $node_type, $node_id ));
									Kernel::registerModule( $module->module_type, $modid, $node_type, $node_id );
								}
							}
						}
					}
				}
			}
			return false;
		}

		foreach( $modavailable AS $module ) {
			//var_dump($module);

			if( preg_match( "/^MOD_(.+)$/", $module->module_type, $modinfo ) ) {
				$modname = strtolower( $modinfo[1] );

				if( array_search("mod_".$modname, $modinstalled)===false ) {
					$file     = & CopixSelectorFactory::create($modname."|".$modname);
					$filePath = $file->getPath() .COPIX_CLASSES_DIR."kernel".strtolower ($file->fileName).'.class.php' ;
					//var_dump($filePath);
					if (is_readable($filePath)){
						$modservice = & CopixClassesFactory::Create ($modname.'|kernel'.$modname);
						if( method_exists( $modservice, "create" ) ) {
							$subtitle = ($node_type=='BU_ECOLE' && isset($nodeInfo['ALL']->eco_type)) ? $nodeInfo['ALL']->eco_type : '';
							$prenom = isset($nodeInfo['prenom']) ? $nodeInfo['prenom'] : '';
							$modid = $modservice->create(array('title'=>trim($prenom.' '.$nodeInfo['nom']), 'subtitle'=>$subtitle, 'node_type'=>$node_type, 'node_id'=>$node_id));
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
	 * @return	array	tableau contenant les informations sur l'entitÈ courante
	 * @author	FrÈdÈric Mossmann
	 */
	function getSessionBU() {
		return _currentUser()->getExtras();
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
    
    // Patch EN2010
    return array();
  
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
	 * Détermine si l'usager courant est un ÈlËve
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/06/15
	 * @return bool True si c'est un ÈlËve, false sinon
	 */
	function isEleve () {
		return (_currentUser()->getExtra('type') == 'USER_ELE');
	}
	
	/**
	 * Détermine si l'usager courant est un élève de la classe indiquée
	 *
	 * @author Jérémy FOURNAISE
	 * @return bool True s'il est élève de la classe, false sinon
	 */
	function isEleveOfClasse ($idClasse) {
	  
		return (_currentUser()->getExtra('type') == 'USER_ELE' && in_array($idClasse, _currentUser()->getExtra('link')->classe));
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
	 * Détermine si l'usager courant est parent de l'élève indiqué
	 *
	 * @author Jérémy FOURNAISE
	 * @return bool True s'il est parent de l'élève, false sinon
	 */
	function isParentOfEleve ($idEleve) {
	  
	  $resDAO = _ioDAO('kernel|kernel_bu_res');
	  
		return (_currentUser()->getExtra('type') == 'USER_RES' && $resDAO->isParentOfStudent (_currentUser()->getExtra('id'), $idEleve)) ;
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
	 * Détermine si l'usager courant est enseignant ou directeur de la classe indiquée
	 *
	 * @author Jérémy FOURNAISE
	 * @return bool True si c'est un enseignant ou directeur de la classe, false sinon
	 */
	function isEnseignantOfClasse ($idClasse) {
	  
	  return (_currentUser()->getExtra('type') == 'USER_ENS' && in_array($idClasse, _currentUser()->getExtra('link')->classe));
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
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
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

	/**
	 * Détermine si l'usager courant est une personne externe
	 *
	 * @author fmadrolle
	 * @since 2010/09/01
	 * @return bool True si c'est une personne externe, false sinon
	 */
	function isPersonneExterne () {
		return ( _currentUser()->getExtra('type') == 'USER_EXT');
	}

	/**
	 * Détermine si l'usager courant est un animateur
	 *
	 * @author fmadrolle
	 * @since 2010/09/01
	 * @return bool True si c'est un animateur, false sinon
	 */
	function isAnimateur () {
		$user = _currentUser ();
		return $user->hasAssistance();
	}


  
	/**
	 * DÈtermine si Iconito est en mode "dÈmo" (accËs limitÈ et auto-login).
	 *
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/11/23
	 * @return bool True si Iconito est en mode "dÈmo" (cf. config |isdemo)
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
	 * Retourne la visibilitÈ d'un type d'utilisateur
	 *
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/07
	 * @param string $dst Type personne ‡ voir.
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
	 * Retourne la visibilitÈ d'un utilisateur
	 *
	 * @author FrÈdÈric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/07
	 * @param string $dst_type Type personne ‡ voir.
	 * @param integer $dst_id Identifiant personne ‡ voir.
	 * @param string $src_type Type personne qui veut voir (option). L'utilisateur connectÈ par dÈfaut.
	 * @param integer $src_id Identifiant personne qui veut voir (option). L'utilisateur connectÈ par dÈfaut.
	 * @return bool True si la visibilitÈ est autorisÈe
	 * @todo Affiner la recherche si la visibilitÈ est limitÈ ‡ un noeud (ville, ecole, etc.)
	 */
	function getUserVisibility( $dst_type, $dst_id, $src_type="ME", $src_id=0 ) {
		if( $src_type=='ME' ) {
			if( (_currentUser()->getExtra('type')) ) {
				$src_type=_currentUser()->getExtra('type');
				$src_id=_currentUser()->getExtra('id');
			}
			else return( false );
		}

		// Recherche la visibilitÈ gÈnÈrique en fonction du type d'utilisateur.
		$type_visibility = Kernel::getUserTypeVisibility( $dst_type, $src_type );

		// Si c'est FULL ou NONE, on rÈpond de suite. Sinon, on doit affiner la recherche (TODO)
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
	 * Renvoie tous les modules dÈveloppÈs. Parcourt le dossier modules
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
		} elseif ($value == '__/__/____')
		$value = '';
		return $value;
	}


	/**
	 * Le theme utilise actuellement. Regarde si on force un template selon l'URL.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/09/21
	 * @param non
	 * @return string Nom du theme
	 */
	function getTheme () {
		if (1 || !$theme = CopixSession::get ('theme')) {
			$theme = self::getKernelLimits('theme');
			if (!$theme)
			$theme = CopixConfig::get ('admin|defaultThemeId');
			//self::setTheme($theme);
		}
		return $theme;
	}
	function setTheme ($pTheme) {
		CopixSession::set ('theme', $pTheme);
	}


	/**
	 * Les limites de l'utilisateur, basees sur l'URL, sur un champ particulier ou toutes les infos
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/09/24
	 * @param $pField string (option) Si on veut un champ particulier. Si null, on renvoit tous les champs
	 * @return mixed Si pField=null, tout le recordset, sinon la valeur du champ (ou null si vide)
	 */
	function getKernelLimits ($pField=null) {
		_classInclude('welcome|welcome');
		$null = _dao("kernel|kernel_limits_urls");
		$cache_type = 'kernel_limits_urls';
		$cache_id = CopixUrl::get();
		if (0 && CopixCache::exists ($cache_id, $cache_type)) {
			$node = CopixCache::read ($cache_id, $cache_type);
		} else {
			$node = Welcome::findNodeByUrl($cache_id);
			if ($node != null) {
				if ($node->ville) {
					$node->ville_as_array = explode(',',$node->ville);
				} else
				$node->ville_as_array = array();
			}
			//var_dump($node);
			CopixCache::write ($cache_id, $node, $cache_type);
		}
		//var_dump($node);
		if ($pField && $node != null && $node->$pField)
		$return = $node->$pField;
		elseif ($pField)
		$return = null;
		else
		$return = $node;
		return $return;
	}


	/**
	 * L'annee scolaire courante
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/05/06
	 * @return object Recordset de la table annee_scolaire, ou null si aucune
	 */
	function getAnneeScolaireCourante () {
		$res = null;
		$sql = "SELECT * FROM kernel_bu_annee_scolaire WHERE current=1";
		if ($ar = _doQuery ($sql))
		$res = $ar[0];
		return $res;
	}

	/**
	 * createLogin
	 *
	 * Propose un login en fonction des information de l'utilisateur (nom, prénom, rôle, etc.)
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @param  array $user_infos Tableau des informations de l'utilisateur.
	 * @param  array $excluded   Identifiants à exclure lors de la génération (import massif)
	 * @return string Login composé des information disponibles.
	 */
	function createLogin( $user_infos, $excluded = array() ) {

		// Caractères pouvant être dans un nom/prenom.
		$interdits = array(" ", "'", "-");

		$nom = $user_infos['nom'];
		$prenom = $user_infos['prenom'];
		$nom_init = $prenom_init = '';

		// Recherche des initiales : la première lettre de chaque entité dans un nom/prenom.
		$separateur_init = implode( '', $interdits );
		$tok = strtok($nom, $separateur_init);
		while ($tok !== false) {
			$nom_init .= $tok{0};
			$tok = strtok($separateur_init);
		}
		$tok = strtok($prenom, $separateur_init);
		while ($tok !== false) {
			$prenom_init .= $tok{0};
			$tok = strtok($separateur_init);
		}

		// Retrait des caractères spéciaux des noms/prénoms.
		$nom       = str_replace($interdits, "", $nom);
		$prenom    = str_replace($interdits, "", $prenom);

		// Simplification (accents, majuscules, etc.)
		$nom         = Kernel::simpleName($nom);
		$nom_init    = Kernel::simpleName($nom_init);
		$prenom      = Kernel::simpleName($prenom);
		$prenom_init = Kernel::simpleName($prenom_init);

		$login_parts = array();
		switch( $user_infos['type'] ) {
      // case 'USER_ELE': // Elèves : Prénom et initiale du nom
      //  if( trim($prenom)   != '' ) $login_parts[] = $prenom;
      //  // if( trim($nom_init) != '' ) $login_parts[] = $nom_init;
      //  $login = implode( '', $login_parts );
      //  break;
			case 'USER_VIL': // Officiels : prénom et nom séparés par un point
				if( trim($prenom) != '' ) $login_parts[] = $prenom;
				if( trim($nom)    != '' ) $login_parts[] = $nom;
				$login = implode( '.', $login_parts );
				break;
			default; // Par défaut : initiale du prénom et nom
			if( trim($prenom_init) != '' ) $login_parts[] = $prenom_init;
			if( trim($nom)         != '' ) $login_parts[] = $nom;
			$login = implode( '', $login_parts );
			break;
		}

		$ext=''; $fusible=1000; // Fusible pour éviter les boucles sans fin.

		$get = _dao('kernel|kernel_copixuser')->getByLogin($login.$ext);
		while( (count($get) || in_array($login.$ext, $excluded)) && $fusible-- ) {
			if( $ext=='' ) $ext=1;
			else $ext++;
			$get = _dao('kernel|kernel_copixuser')->getByLogin($login.$ext);
		}

		return $login.$ext;
	}

	/**
	 * createPasswd
	 *
	 * Propose un mot de passe aléatoire.
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @return string Mot de passe aléatoire.
	 */
	function createPasswd() {
		$lettres  = 'abcdefghijklmnopqrstuvwxyz';
		$chiffres = '0123456789';
		$passwd = '';

		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $lettres{mt_rand(0, strlen($lettres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};
		$passwd .= $chiffres{mt_rand(0, strlen($chiffres)-1)};

		return( $passwd );
	}

	/**
	 * isLoginAvailable
	 *
	 * Vérifie que le login soit disponible.
	 *
	 * @author Jérémy FOURNAISE
	 * @return string Login souhaité.
	 */
	function isLoginAvailable ($login) {
		 
		$dbUserDAO = _ioDAO ('kernel|kernel_copixuser');

		if ($dbUserDAO->getByUserLogin ($login)) {

			return false;
		}

		return true;
	}

	/**
	 * checkPasswordFormat
	 *
	 * Vérifie le format du password : au moins 6 caractères dont 1 chiffre.
	 *
	 * @author Jérémy FOURNAISE
	 * @return string Password souhaité.
	 */
	function checkPasswordFormat ($password) {
		 
		if (strlen ($password) < 6) {
			 
			return false;
		}

		if (!preg_match('/[0-9]/', $password)) {
			 
			return false;
		}

		return true;
	}

  /**
	 * createCanon
	 *
	 * Génère un slug unique (canon) pour le nom d'une ville
	 *
	 * @author Jérémy FOURNAISE
	 * @return string slug
	 */
	function createCanon ($cityName) {

		$canon = strtolower (trim ($cityName));

		$cityDAO = _ioDAO('kernel|kernel_bu_ville');
		$city = $cityDAO->getByCanon($canon);
		$cpt = '';
		 
		while (count ($city)) {

			if ($cpt == '') {

				$cpt = 1;
			}
			else {
					
				$cpt++;
			}
				
			$city = $cityDAO->getByCanon($canon.$cpt);
		}
		 
		return $canon.$cpt;
	}

  /**
	 * generateBreadcrumbs
	 *
	 * Génère le breadcrumb pour le module "gestionautonome"
	 *
	 * @author Jérémy FOURNAISE
	 * @return array  Breadcrumb
	 */
	function generateBreadcrumbs ($nodeInfos) {

		$breadcrumbs=array();
		$breadcrumbs[] = array('txt' => 'Gestion de la structure scolaire', 'url' => CopixUrl::get('gestionautonome||showTree'));
		if (isset ($nodeInfos['ALL']->vil_id_vi)) {
			 
			$breadcrumbs[] = array('txt' => $nodeInfos['ALL']->vil_nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $nodeInfos['ALL']->vil_id_vi)));
		}
		elseif (isset ($nodeInfos['ALL']->eco_id_ville)) {
			 
			$cityDAO = _dao('kernel|kernel_bu_ville');
			$city = $cityDAO->get ($nodeInfos['ALL']->eco_id_ville);
			$breadcrumbs[] = array('txt' => $city->nom, 'url' => CopixUrl::get('gestionautonome||updateCity', array ('nodeId' => $nodeInfos['ALL']->eco_id_ville)));
		}
		if (isset ($nodeInfos['ALL']->eco_numero)) {
			 
			$breadcrumbs[] = array('txt' => $nodeInfos['ALL']->eco_nom, 'url' => CopixUrl::get('gestionautonome||updateSchool', array ('nodeId' => $nodeInfos['ALL']->eco_numero)));
		}
		if (isset ($nodeInfos['ALL']->cla_id)) {
			 
			$breadcrumbs[] = array('txt' => $nodeInfos['ALL']->cla_nom, 'url' => CopixUrl::get('gestionautonome||updateClass', array ('nodeId' => $nodeInfos['ALL']->cla_id)));
		}
		 
		return $breadcrumbs;
	}

	/**
	 * Retourne vrai si le module de gestion autonome est activé
	 * 
	 * @author Jérémy FOURNAISE
	 * @return bool True si la gestion autonome est activée, false sinon
	 */
	public function isGestionAutonomeEnabled () {

		return CopixConfig::get ('kernel|gestionAutonomeEnabled');
	}

	public function disableCopixUser ( $userid ) {
		$dbUserDAO = _ioDAO ('kernel|kernel_copixuser');
		if ( $user = $dbUserDAO->get ($userid)) {
			$user->enabled_dbuser = 0;
			$dbUserDAO->update ($user);
			return true;
		}
		return false;
	}

	public function enableCopixUser ( $userid ) {
		$dbUserDAO = _ioDAO ('kernel|kernel_copixuser');
		if ( $user = $dbUserDAO->get ($userid)) {
			$user->enabled_dbuser = 1;
			$dbUserDAO->update ($user);
			return true;
		}
		return false;
	}



  /**
   * Verification de spam, selon les parametres passes et le referer
   * @author Christophe Beyer <cbeyer@cap-tic.fr>
   * @since 2011/04/08
   * @return boolean True si le visiteur est un robot spammeur, true si c'est un humain
   */
  public function isSpam()
  {
    $oIsSpam = false;
    if (!isset($_SERVER["HTTP_REFERER"]) || !$_SERVER["HTTP_REFERER"]) // Pas de referer
      $oIsSpam = true;
    elseif (CopixRequest::get('url1')) // Champ en commentaire rempli
      $oIsSpam = true;
    elseif (CopixRequest::get('url2') != 'Abracadabra') // Champ non visible modifie
      $oIsSpam = true;
    return $oIsSpam;
  }
    
  /**
   * Retourne la valeur en bytes (utilisé pour le calcul du upload_max_size dans le module classeur)
   * 
   * @author Jérémy FOURNAISE
   * @return int $val Valeur en bytes
   */
  public static function return_bytes($val) {
    
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
      case 'g':
        $val *= 1024;
      case 'm':
        $val *= 1024;
      case 'k':
        $val *= 1024;
    }
    
    return $val;
  }
  
  /**
   * Supprime tous les caractères non conformes
   *
   * @author Jérémy FOURNAISE
   *
   * @param    string   $text
   * @param    string   $encoding
   * @return   string   stripped text
   */
  public static function stripText ($text, $encoding = 'UTF-8') {
  
    $text = mb_strtolower($text, $encoding);

    $patterns = array('/à/', '/á/', '/â/', '/ã/', '/ä/', '/å/', '/ò/', '/ó/',
                      '/ô/', '/õ/', '/ö/', '/ø/', '/è/', '/é/', '/ê/', '/ë/',
                      '/ç/', '/ì/', '/í/', '/î/', '/ï/', '/ù/', '/ú/', '/û/',
                      '/ü/', '/ÿ/', '/ñ/');

    $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'o', 'o',
                     'o', 'o', 'o', 'o', 'e', 'e', 'e', 'e',
                     'c', 'i', 'i', 'i', 'i', 'u', 'u', 'u',
                     'u', 'y', 'n');
    
    $text = preg_replace($patterns, $replace, $text);
    
    // strip all non word chars
    $text = preg_replace('/[^a-z0-9]/', ' ', $text);
    
    // replace all white space sections with a dash
    $text = preg_replace('/\ +/', '-', $text);
 
    // trim dashes
    $text = preg_replace('/\-$/', '', $text);
    $text = preg_replace('/^\-/', '', $text);
 
    return $text;
  }
}
