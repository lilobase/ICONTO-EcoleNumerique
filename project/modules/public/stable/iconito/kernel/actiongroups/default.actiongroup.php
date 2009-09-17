<?php
/**
 * Kernel - ActionGroup
 *
 * Fonctions du coeur d'Iconito : Gestion des utilisateurs, des liens avec les entités, de l'accès à la base élève, des droits.
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: kernel.actiongroup.php,v 1.50 2009-07-10 09:13:20 cbeyer Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

_classInclude ('welcome|welcome');
  

class ActionGroupDefault extends CopixActionGroup {
	
	
	function processDefault () {
		return _arRedirect (_url ('|getHome'));
	}
	
	
	
	/**
	 * getNodes
	 *
	 * Affiche la liste des entités reliées à l'utilisateur connecté (classe, école, ville, etc.)
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function processGetNodes () {
		$tpl = & new CopixTpl ();
		$tplModule = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel.menu.accueil'));
		
		if( !isset($_SESSION["user"]->bu) || !isset($_SESSION["user"]->bu["type"]) || !isset($_SESSION["user"]->bu["id"]) ) {
	      return CopixActionGroup::process ('genericTools|Messages::getError',
	      array ('message'=>CopixI18N::get ('kernel.error.nologin'),
	      'back'=>CopixUrl::get ('auth|default|login')));
		}
		
		if( isset($_SESSION["user"]->home["type"]) && $_SESSION["user"]->home["type"] == 'CLUB' ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHome', array('id'=>$_SESSION["user"]->home['id'] ) ));
		}
		
		$result = "";
		$nodes_perso = $nodes_children = array();
		$nodes_all = Kernel::getNodeParents( $_SESSION["user"]->bu["type"], $_SESSION["user"]->bu["id"] );
		
		foreach( $nodes_all as $key => $val ) {
			switch( $val["type"] ) {
				case "ROOT":
					if( $val["droit"] > 0 ) {
						$nodes_perso['ROOT']["droit"] = $val["droit"];
						$nodes_perso['ROOT']['type'] = 'ROOT';
						$nodes_perso['ROOT']['id'  ] = 0;
						$nodes_perso['ROOT']['nom' ] = 'Root';
					}
					break;
				case "BU_CLASSE":
					$ecole = Kernel::getNodeParents( $val["type"], $val["id"] );
					$ville = Kernel::getNodeParents( $ecole[0]["type"], $ecole[0]["id"] );
					$grville = Kernel::getNodeParents( $ville[0]["type"], $ville[0]["id"] );
					$nodes_perso[$grville[0]["id"]][$ville[0]["id"]][$ecole[0]["id"]][$val["id"]]["droit"] = $val["droit"];
					break;
				case "BU_ECOLE":
					$ville = Kernel::getNodeParents( $val["type"], $val["id"] );
					$grville = Kernel::getNodeParents( $ville[0]["type"], $ville[0]["id"] );
					$nodes_perso[$grville[0]["id"]][$ville[0]["id"]][$val["id"]]["droit"] = $val["droit"];
					break;
				case "BU_VILLE":
					$grville = Kernel::getNodeParents( $val["type"], $val["id"] );
					$nodes_perso[$grville[0]["id"]][$val["id"]]["droit"] = $val["droit"];
					break;
				case "BU_GRVILLE":
					$nodes_perso[$val["id"]]["droit"] = $val["droit"];
					break;
			}
		}
		
		$nodes_copy = $nodes_perso;
		
		if( isset($nodes_perso['ROOT']) ) {
			$nodes_perso['ROOT']["info"]["nom"] = CopixI18N::get ('kernel|kernel.message.admin');
			$nodes_perso['ROOT']["info"]["type_nom"] = "Root";
			$nodes_perso['ROOT']["info"]["type"] = "ROOT";
			$nodes_perso['ROOT']["info"]["id"] = 0;
			$nodes_perso['ROOT']["info"]["selected"] = ($_SESSION["user"]->home["type"]=="ROOT" )?true:false;
		}
		
		// Pour tous les groupes de ville...
		foreach( $nodes_copy as $key_grville => $val_grville ) { // GRVILLE
			if( !is_int($key_grville) ) continue;
			$nodes_perso[$key_grville]["info"] = Kernel::getNodeInfo( "BU_GRVILLE", $key_grville, false );
			$nodes_perso[$key_grville]["info"]["type_nom"] = CopixI18N::get ('kernel.codes.bu_grville');
			$nodes_perso[$key_grville]["info"]["selected"] = ($_SESSION["user"]->home["type"]=="BU_GRVILLE" && $_SESSION["user"]->home["id"]==$key_grville)?true:false;
			
			// Pour toutes les villes...
			foreach( $val_grville as $key_ville => $val_ville ) { // VILLE
				if( !is_int($key_ville) ) continue;
				$nodes_perso[$key_grville][$key_ville]["info"] = Kernel::getNodeInfo( "BU_VILLE", $key_ville, false );
				$nodes_perso[$key_grville][$key_ville]["info"]["type_nom"] = CopixI18N::get ('kernel.codes.bu_ville');
				$nodes_perso[$key_grville][$key_ville]["info"]["selected"] = ($_SESSION["user"]->home["type"]=="BU_VILLE" && $_SESSION["user"]->home["id"]==$key_ville)?true:false;
				
				// Pour toutes les écoles...
				foreach( $val_ville as $key_ecole => $val_ecole ) { // ECOLE
					if( !is_int($key_ecole) ) continue;
					$info = Kernel::getNodeInfo( "BU_ECOLE", $key_ecole, false );
					//print_r($info);
					$nodes_perso[$key_grville][$key_ville][$key_ecole]["info"] = $info;
					$nodes_perso[$key_grville][$key_ville][$key_ecole]["info"]["type_nom"] = CopixI18N::get ('kernel.codes.bu_ecole');
					$nodes_perso[$key_grville][$key_ville][$key_ecole]["info"]["type_nom_plus"] = $info['ALL']->eco_type;
					$nodes_perso[$key_grville][$key_ville][$key_ecole]["info"]["selected"] = ($_SESSION["user"]->home["type"]=="BU_ECOLE" && $_SESSION["user"]->home["id"]==$key_ecole)?true:false;
					
					// Pour toutes les classes...
					foreach( $val_ecole as $key_classe => $val_classe ) { // CLASSE
						if( !is_int($key_classe) ) continue;
						$nodes_perso[$key_grville][$key_ville][$key_ecole][$key_classe]["info"] = Kernel::getNodeInfo( "BU_CLASSE", $key_classe, false );
						$nodes_perso[$key_grville][$key_ville][$key_ecole][$key_classe]["info"]["type_nom"] = CopixI18N::get ('kernel.codes.bu_classe');
						$nodes_perso[$key_grville][$key_ville][$key_ecole][$key_classe]["info"]["selected"] = ($_SESSION["user"]->home["type"]=="BU_CLASSE" && $_SESSION["user"]->home["id"]==$key_classe)?true:false;
					}
				}
			}
		}
		
		// Cas du parent d'élève
		if ($_SESSION["user"]->bu["type"] == "USER_RES") {
			$childs = Kernel::getNodeParents( $_SESSION["user"]->bu["type"], $_SESSION["user"]->bu["id"] );
			while (list(,$child) = each($childs)) {
				if ($child["type"] != "USER_ELE") continue;
				// Les modules de l'enfant
				$nodes_all = Kernel::getNodeParents( $child["type"], $child["id"] );
				foreach ($nodes_all as $node) {
					if ($node['type'] != 'BU_CLASSE') continue;
					//print_r($node);
					$child['classe'] = $node['nom'];
					$modules = Kernel::getModEnabled(
						$node['type'], $node['id'],
						$child["type"],   $child["id"]   );
					//print_r($modules);
				}
				$nodes_children[] = array('info'=>$child, 'modules'=>$modules);
				//$children[] = $child;
			}
			$tplModule->assign ("groupes", CopixZone::process ('groupe|mygroupes', array('where'=>'home')));
			//$tplModule->assign ('children', $childs);
		}
		
		// Pas de noeud perso ni d'enfant -> page des groupes
		if( 0==count($nodes_children) && 0==count($nodes_perso) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getListMy' ));
		}
		
		$tplModule->assign ("data_children", $nodes_children);
		$tplModule->assign ("data", $nodes_perso);
		$result = $tplModule->fetch("getnodes.tpl");
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * doSelectHome
	 *
	 * Mémorisation en session du noeud (ville, école, classe) actuel de travail de l'utilisateur connecté.
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function processDoSelectHome() {
		if( !isset($_SESSION["user"]->bu) || !isset($_SESSION["user"]->bu["type"]) || !isset($_SESSION["user"]->bu["id"]) ) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel.error.nologin'),
			'back'=>CopixUrl::get ('auth|default|login')));
		}
		
		if( !isset($_GET["type"]) || !isset($_GET["id"]) ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getNodes' ));
		}
		
		// Cas du parent d'élève qui ne peut revenir qu'à son accueil personnalisé avec ses enfants. CB 06/12/2006
    //print_r($_SESSION);
    if ($_SESSION['user']->bu['type'] != 'USER_RES') {
  		Prefs::set( 'kernel', 'home', $_GET["type"]."-".$_GET["id"] );
	  	Logs::set( array('type'=>'INFO', 'message'=>'SelectHome: '.$_GET["type"]."-".$_GET["id"]) );
  		Kernel::setMyNode( $_GET["type"], $_GET["id"] );
    }
		
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getHome' ));
	}

	/**
	 * getHome
	 *
	 * Affiche les information de la zone de travail active (modules disponibles, etc.)
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function processGetHome() {
		$tpl = & new CopixTpl ();
		$tplModule = & new CopixTpl ();
		
		$user = _currentUser ();
		
		$acc = (isset($_SESSION["user"]->home["titre1"])) ? $_SESSION["user"]->home["titre1"] : '';
		if( $acc != '' )
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel.title.accueil', array($acc)));
		else
			$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel.title.accueilsimple'));
		
		$menu = '';
		if( count(Kernel::getMyHomes()) > 1 )
		$menu .= '<a href="'.CopixUrl::get ('kernel||getNodes' ).'">'.CopixI18N::get ('kernel|kernel.message.changezone').'</a>';
		
		if( CopixConfig::exists('|conf_ModSso') && CopixConfig::get('|conf_ModSso') )
		{
			$modavailable = Kernel::getModAvailable ($_SESSION['user']->bu['type']);
			$hasSSO = false;
			foreach ($modavailable as $mod) {
				if ($mod->module_type == 'MOD_SSO') $hasSSO = true;
			}
			if ($hasSSO) {
				$menu .= ($menu) ? ' :: ' : '';
				$menu .= '<a href="'.CopixUrl::get ('sso||').'">'.CopixI18N::get ('kernel|kernel.message.servicesexternes').'</a>'; 
			}
		}
      
		$tpl->assign ('MENU', $menu );
		$return_str = "";
					

		if( !$user->isConnected() || !$user->getExtra('type') || !$user->getExtra('id') ) {
			$dispBlog = false;
			$node = Welcome::findNodeByUrl(CopixUrl::get());
			//print_r($node);
			if ( isset($node->node_type) && isset($node->node_id) ) {
				$blog = getNodeBlog ($node->node_type, $node->node_id);
				if ($blog) {
					// On vérifie qu'il y a au moins un article
					$blog->stats = KernelBlog::getStats ($blog->id_blog);
					//print_r($blog);
					if ($blog->stats['nbArticles']['value']>0)
						$dispBlog = true;
				}
			}
			if ($dispBlog) {
				return CopixActionGroup::process ('blog|frontblog::getListArticle', array ('blog'=>$blog->url_blog));	
			} else {
				CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('public||rss', array()).'" type="application/rss+xml" title="'.htmlentities(CopixI18N::get ('public|public.rss.flux.title')).'" />');
				$tpl->assign ('TITLE_PAGE', ''.CopixI18N::get ('public|public.welcome.title'));
				//print_r($tpl);
				//return new CopixActionReturn ('COPIX_AR_DISPLAY', $tpl);
				return _arPPO ($tpl, 'get-home.tpl');
			}		
		}
		
		//print_r($_SESSION);
		//die();
		
		if( !isset($_SESSION["user"]) ||
		    !isset($_SESSION["user"]->home) ||
		    !isset($_SESSION["user"]->home["type"]) ||
		    !isset($_SESSION["user"]->home["id"]) ) {
			
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getNodes' ));
		}
		
		// Cas particulier (invité) : non attaché à la base unique, mais à un club
		if( $_SESSION["user"]->home["type"] == 'CLUB' ) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHome', array('id'=>$_SESSION["user"]->home['id'] ) ));
		}
		
		Kernel::createMissingModules( $_SESSION["user"]->home["type"], $_SESSION["user"]->home["id"] );
		
		$modules = Kernel::getModEnabled(
			$_SESSION["user"]->home["type"], $_SESSION["user"]->home["id"],
			$_SESSION["user"]->bu["type"],   $_SESSION["user"]->bu["id"]    );
		
		$tplModule->assign ("modules", $modules);
		$tplModule->assign ("groupes", CopixZone::process ('groupe|mygroupes', array('where'=>'home')));
		// $tplModule->assign ("aujourdhui", CopixZone::process ('agenda|agendatoday', array('where'=>'home')));
		$return_str = $tplModule->fetch("getmodules.tpl");
		
		$tpl->assign ('MAIN', $return_str );
		return new CopixActionReturn ('COPIX_AR_DISPLAY', $tpl);
	}






	/**
	 * getTree
	 *
	 * DEBUG: Affiche l'arbre d'information de l'utilisateur connecté.
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function processGetTree () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "getTree");
		
		$tpl->assign ('MAIN', '<pre>'.print_r(Kernel::getTree(),true).'</pre>' );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * debug
	 *
	 * DEBUG: Fonction de tests, librement modifiable...
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function processDebug () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Debug");
		$return_str = "Debug...";
		
		/*
		$tmp = Kernel::getModParentInfo( "MOD_RESSOURCE", 2 );
		Kernel::MyDebug( $tmp );
		*/
		
		/*
		$pref_class = & CopixClassesFactory::Create ('prefs|prefs');
		echo $pref_class->get( 'minimail', 'alerte_mail_email' );
		*/
		
		/** Test getUserInfo
		$result = Kernel::getUserInfo( "LOGIN", "blob" );
		$result = Kernel::getUserInfo( "ID", 2 );
		$result = Kernel::getUserInfo( "USER_EXT", 1 );
		die( "<pre>".print_r($result,true)."</pre>" );
		*/
		
		// Kernel::MyDebug( Kernel::getUserInfo( "USER_EXT",1 ) );
		
		// Kernel::MyDebug( Kernel::getNodeInfo( "BU_CLASSE",1 ) );
		
		// $infos = Kernel::getMyHomes();
		// $return_str=print_r($infos,true);
		
		$tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * walk
	 *
	 * DEBUG: Affichage des parents et enfants d'un noeud, pour valider le
	 * fonctionnement des fonctions getNodeParents et getNodeChilds.
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @see getNodeParents( $type, $id )
	 * @see getNodeChilds( $type, $id )
	 */
	function processWalk () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Walk");
		
		$return_left   ="";
		$return_center ="";
		$return_right  ="";
		
		if( _request("type")) {
			$type=_request("type");      $id=_request("id");
		} else {
			if( isset($_SESSION["user"]->bu["type"]) && isset($_SESSION["user"]->bu["id"]) ) {
				$type=$_SESSION["user"]->bu["type"];      $id=$_SESSION["user"]->bu["id"];
			} else {
				$type="USER_ELE"; $id=3777; 
			}
		}
		
		$result=Kernel::getNodeParents( $type, $id );
		foreach ($result AS $key=>$value) {
			$return_left .= '<a href="'.CopixUrl::get ('kernel||walk', array("type"=>$value["type"], "id"=>$value["id"])).'">';
			$return_left .= $value["type"]."/".$value["id"];
			if( isset($value["droit"]) && $value["droit"] ) $return_left .= ' ('.$value["droit"].')';
			$return_left .= '</a>';
			// $tmp = Kernel::getNodeChilds( $value["type"], $value["id"] );
			// if( sizeof($tmp) ) $return_left .= " (".sizeof($tmp).")";
			$return_left .= '<br />';
		}
		
		$result=Kernel::getNodeChilds( $type, $id );
		foreach ($result AS $key=>$value) {
			// $tmp = Kernel::getNodeChilds( $value["type"], $value["id"] );
			// if( sizeof($tmp) ) $return_right .= "(".sizeof($tmp).") ";
			$return_right .= '<a href="'.CopixUrl::get ('kernel||walk', array("type"=>$value["type"], "id"=>$value["id"])).'">';
			$return_right .= $value["type"]."/".$value["id"];
			if( isset($value["droit"]) && $value["droit"] ) $return_right .= ' ('.$value["droit"].')';
			$return_right .= '</a>';
			$return_right .= '<br />';
		}

		$return_center .= $type."/".$id;
		
		if(ereg("^USER_", $type)) {
			$user_infos = Kernel::getUserInfo( $type, $id );
			if( isset( $user_infos["login"] ) ) $return_center .= "<br />Login: ".$user_infos["login"];
			if( isset( $user_infos["nom"] ) && isset( $user_infos["prenom"] ) ) {
				$return_center .= "<br />(";
				$return_center .= $user_infos["prenom"]." ".$user_infos["nom"];
				$return_center .= ")";
			}
		} else {
			$node_infos = Kernel::getNodeInfo( $type, $id, false );
			if( isset( $node_infos["nom"] ) ) $return_center .= "<br />".$node_infos["nom"];
			if( isset( $node_infos["desc"] ) ) {
				$return_center .= "<br />(";
				if( strlen( $node_infos["desc"] ) > 45 ) $return_center .= substr($node_infos["desc"], 0, 40)."...";
				else $return_center .= $node_infos["desc"];
				$return_center .= ")";
			}
		}


		$return_str = '<center><h3>'.$return_center.'</h3></center>';
		$return_str.= '<table width="100%"><tr><td valign="top" align="left"><strong>Parents</strong></td><td valign="top" align="right"><strong>Childs</strong></td></tr>';
		$return_str.= '<tr><td valign="top" align="left">'.$return_left.'</td><td valign="top" align="right">'.$return_right.'</td></tr></table>';
				
		$tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * getLink
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @todo A faire...
	 */
	function processGetLink () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('kernel|kernel.message.profil'));
		
		$linkedin = Kernel::getMyParents();
		foreach( $linkedin["direct"] as $key => $val ) {
			$linkeddata[] = $key." (".sizeof($val).")";
		}
		$linkedstr = "[ ".implode(" | ", $linkeddata)." ]";
		
		$tpl->assign ('MAIN', "<pre>".$linkedstr."</pre>" );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

	/**
	 * doLink
	 *
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @todo A faire...
	 */
	function processDoLink () {
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', "Walk");
		
		$tpl->assign ('MAIN', "<pre>".$return_str."</pre>" );
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}
?>
