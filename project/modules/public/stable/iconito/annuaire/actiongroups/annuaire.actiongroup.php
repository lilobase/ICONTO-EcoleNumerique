<?php

require_once (COPIX_MODULE_PATH.'blog/'.COPIX_CLASSES_DIR.'blogutils.class.php');

/**
 * Actiongroup du module Annuaire
 * 
 * @package Iconito
 * @subpackage Annuaire
 */
class ActionGroupAnnuaire extends CopixActionGroup {

   /**
   * Redirection vers un annuaire. On peut demander � afficher un annuaire de ville ($id vaut alors "VILLE_XX"), d'�cole ("ECOLE_XX") ou de classe ("CLASSE_XX")
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/17
	 * @param string $id Annuaire demand�
   */
	 function go () {
   
    if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));
    
		$classe = $ecole = $ville = null;
		
		if( isset( $this->vars["id"] ) ) {
			if( ereg( 'CLASSE_([0-9]+)', $this->vars["id"], $regs ) )
				$classe = $regs[1];
			elseif( ereg( 'ECOLE_([0-9]+)', $this->vars["id"], $regs ) )
				$ecole = $regs[1];
			elseif( ereg( 'VILLE_([0-9]+)', $this->vars["id"], $regs ) )
				$ville = $regs[1];
		}
		
		// Annuaire par d�faut, on regarde sa session
		if (!$classe && !$ecole && !$ville) {
			$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
			$home = $annuaireService->getAnnuaireHome ();
			switch ($home['type']) {
				case 'BU_VILLE' :
					$ville = $home['id'];
					break;		
				case 'BU_ECOLE' :
					$ecole = $home['id'];
					break;		
				case 'BU_CLASSE' :
					$classe = $home['id'];
					break;
				default :	// On prend la 1e ville
			}
		}
    
		if ($classe)
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('annuaire||getAnnuaireClasse', array('classe'=>$classe) ));
		elseif ($ecole)
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('annuaire||getAnnuaireEcole', array('ecole'=>$ecole) ));
		elseif ($ville)
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('annuaire||getAnnuaireVille', array('ville'=>$ville) ));
		else
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noGrville'), 'back'=>CopixUrl::get('annuaire||')));
	}
	

	
   /**
   * Affichage d'un annuaire de ville
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/17
	 * @param integer $ville Id de la ville
	 * @todo Positionner grville selon $rVille
   */
	function getAnnuaireVille () {
	 	
    if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));

		$ville = isset($this->vars["ville"]) ? $this->vars["ville"] : NULL;
	  $grville = 1;
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		$criticErrors = array();
		
		$rVille = Kernel::getNodeInfo ('BU_VILLE', $ville, false);
		
		if (!$rVille)
			$criticErrors[] = CopixI18N::get ('annuaire|annuaire.error.noVille');

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));
		
    // Blog de la ville
		$blog = getNodeBlog ('BU_VILLE', $ville);
    if ($blog)
      $rVille['blog'] = CopixUrl::get('blog||', array('blog'=>$blog->url_blog));

		$ecoles = $annuaireService->getEcolesInVille ($ville);
		$agents = $annuaireService->getAgentsInVille ($ville);
		$agents = $annuaireService->checkVisibility ($agents);
		
		//print_r($ecoles);
    
    // On cherche les blogs
    foreach ($ecoles as $k=>$e) {
      $blog = getNodeBlog ('BU_ECOLE', $e['id']);
      if ($blog)
        $ecoles[$k]['blog'] = CopixUrl::get('blog||', array('blog'=>$blog->url_blog));
      // On zappe le site web
      $ecoles[$k]['web'] = NULL;
    }
    
		//foreach ($result AS $key=>$value) {
		
		$tplListe = & new CopixTpl ();
		$tplListe->assign ('ecoles', $ecoles);
		$tplListe->assign ('agents', $agents);
		$tplListe->assign ('ville', $rVille);
		$tplListe->assign ('combovilles', CopixZone::process ('annuaire|combovilles', array('grville'=>$grville, 'value'=>$ville, 'fieldName'=>'ville', 'attribs'=>'class="annu_combo_popup" onchange="this.form.submit();"')));
		$result = $tplListe->fetch("getannuaireville.tpl");

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $rVille["nom"]);
		$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('public||getListBlogs').'">'.CopixI18N::get ('public|public.blog.annuaire').'</a>');
		$tpl->assign ("MAIN", $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
		

   /**
   * Affichage d'un annuaire d'�cole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/18
	 * @param integer $ecole Id de l'�cole
   */
	function getAnnuaireEcole () {
    if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));

		$ecole = isset($this->vars["ecole"]) ? $this->vars["ecole"] : NULL;

		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		$fichesEcolesService = & CopixClassesFactory::Create ('fichesecoles|FichesEcolesService');
		$criticErrors = array();

		$rEcole = Kernel::getNodeInfo ('BU_ECOLE', $ecole, false);
		//print_r($rEcole);

		if (!$rEcole)
			$criticErrors[] = CopixI18N::get ('annuaire|annuaire.error.noEcole');
			
		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));
		
		$tplListe = & new CopixTpl ();
		//$tplListe->assign ('ecoles', $ecoles);
		
    // Blog de l'�cole
		$blog = getNodeBlog ('BU_ECOLE', $ecole);
    if ($blog)
      $rEcole['blog'] = CopixUrl::get('blog||', array('blog'=>$blog->url_blog));

    //print_r($rEcole);
    
		//On se place sur la 1e classe
		
// BOOST 3s
//$start = microtime(true);
		$classes = $annuaireService->getClassesInEcole ($ecole);
//echo "getClassesInEcole : ".(microtime(true)-$start)."<br />";
//echo "<pre>"; print_r($classes); die();
		if ($classes[0]['id']) {
			$rClasse = Kernel::getNodeInfo ('BU_CLASSE', $classes[0]['id'], false);
			$tplListe->assign ('infosclasse', CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse)));
		}
		
// BOOST 3s
//$start = microtime(true);
		$tplListe->assign ('infosecole', CopixZone::process ('annuaire|infosecole', array('rEcole'=>$rEcole, 'classes'=>$classes)));
//echo "zone infosecole : ".(microtime(true)-$start)."<br />";
		
		$result = $tplListe->fetch('getannuaireecole.tpl');

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $rEcole["nom"]." (".$rEcole["desc"].")");
		$menu = array();
		$menu[] = array (
			'url' => CopixUrl::get ('public||getListBlogs'),
			'txt' => CopixI18N::get ('public|public.blog.annuaire'),
		);
		if ($fichesEcolesService->canMakeInFicheEcole($ecole,'VIEW'))
			$menu[] = array(
				'url' => CopixUrl::get('fichesecoles||fiche', array('id'=>$ecole)),
				'txt' => CopixI18N::get ('annuaire|annuaire.fiche'),
			);
		$menu[] = array (
			'url' => CopixUrl::get ('|getAnnuaireVille', array('ville'=>$rEcole['ALL']->vil_id_vi)),
			'txt' => CopixI18N::get ('annuaire|annuaire.backVille'),
		);
		
		$tpl->assign ('MENU', $menu);
		$tpl->assign ("MAIN", $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

   /**
   * Affichage d'un annuaire de classe
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/18
	 * @param integer $classe Id de la classe
   */
	function getAnnuaireClasse () {
	 	
    if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));

		$classe = isset($this->vars["classe"]) ? $this->vars["classe"] : NULL;

		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		$criticErrors = array();
		
		$rClasse = Kernel::getNodeInfo ('BU_CLASSE', $classe, false);
		
		if (!$rClasse)
			$criticErrors[] = CopixI18N::get ('annuaire|annuaire.error.noClasse');
		
		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));
		
		// Si c'est le d�tail d'une classe, on en d�duit l'�cole
		$parent = Kernel::getNodeParents ('BU_CLASSE', $classe);
		if ($parent[0]['type']=='BU_ECOLE')
			$ecole = $parent[0]['id'];

		$rEcole = Kernel::getNodeInfo ('BU_ECOLE', $ecole, false);
    
     // Blog de l'�cole
		$blog = getNodeBlog ('BU_ECOLE', $ecole);
    if ($blog)
      $rEcole['blog'] = CopixUrl::get('blog||', array('blog'=>$blog->url_blog));
    
		$tplListe = & new CopixTpl ();
		
		$tplListe->assign ('infosecole', CopixZone::process ('annuaire|infosecole', array('rEcole'=>$rEcole)));
		$tplListe->assign ('infosclasse', CopixZone::process ('annuaire|infosclasse', array('rClasse'=>$rClasse)));
		
		$tplListe->assign ('classe', $rClasse);
		$result = $tplListe->fetch('getannuaireclasse.tpl');
		
		//print_r($rEcole);
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $rClasse["nom"]);
		$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('public||getListBlogs').'">'.CopixI18N::get ('public|public.blog.annuaire').'</a> :: <a href="'.CopixUrl::get ('|getAnnuaireEcole', array('ecole'=>$ecole)).'">'.CopixI18N::get ('annuaire|annuaire.backEcole').'</a> :: <a href="'.CopixUrl::get ('|getAnnuaireVille', array('ville'=>$rEcole['ALL']->vil_id_vi)).'">'.CopixI18N::get ('annuaire|annuaire.backVille').'</a>');
		$tpl->assign ('MAIN', $result);
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}


	
	/**
   * Affichage d'une fiche d�taill�e d'un utilisateur. Appell� en Ajax
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/06
	 * @param string $type Type de personne (USER_ELE, USER_ELE...)
	 * @param integer $id Id de la personne
   */
	function getUserProfil () {
	 	
    if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));

		$type = isset($this->vars['type']) ? $this->vars['type'] : NULL;
		$id = isset($this->vars['id']) ? $this->vars['id'] : NULL;
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('zone', CopixZone::process ('annuaire|getUserProfil', array('type'=>$type, 'id'=>$id)));
		$result = $tpl->fetch('getuser.tpl');

		//$tpl->assign ('MAIN', $result);
		header('Content-type: text/html; charset=utf-8');
		echo utf8_encode($result);
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}

	/**
   * Affichage de l'annuaire en version popup
	 * 
	 * Affiche les discussions d'un forum et les informations sur les discussions (titre, dernier message...), avec un lien pour lire chaque discussion.
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/18
   */
	function getPopup () {

		if (!Kernel::is_connected())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('annuaire|annuaire.error.noLogged'), 'back'=>CopixUrl::get('||')));

		$grville = isset($this->vars['grville']) ? $this->vars['grville'] : NULL;
		$ville = isset($this->vars['ville']) ? $this->vars['ville'] : NULL;
		$ecole = isset($this->vars['ecole']) ? $this->vars['ecole'] : NULL;
		$classe = isset($this->vars['classe']) ? $this->vars['classe'] : NULL;
		$field = isset($this->vars['field']) ? $this->vars['field'] : '';
		//$profils = isset($this->vars['profils']) ? $this->vars['profils'] : array('ELE'=>1);
		$profils = isset($this->vars['profils']) ? $this->vars['profils'] : array();
		$profil = $this->getRequest ('profil'); // Si on force sur un profil unique a afficher
		
		
		$ALL = CopixConfig::get ('annuaire|annu_combo_all');

		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
			
		// Annuaire par d�faut, on regarde sa session
		if (!$classe && !$ecole && !$ville) {
			$home = $annuaireService->getAnnuaireHome ();
			//print_r($home);
			switch ($home['type']) {
				case 'BU_GRVILLE' :
					$grville = $home['id'];
					$ville = $ALL;
					$ecole = $ALL;
					$classe = $ALL;
					break;		
				case 'BU_VILLE' :
					$info = Kernel::getNodeInfo ($home['type'], $home['id']);
					//print_r($info);
					$grville = $info['ALL']->vil_id_grville;
					$ville = $home['id'];
					$ecole = $ALL;
					$classe = $ALL;
					break;		
				case 'BU_ECOLE' :
					$info = Kernel::getNodeInfo ($home['type'], $home['id']);
					//print_r($info);
					$grville = $info['ALL']->vil_id_grville;
					$ville = $info['ALL']->eco_id_ville;
					$ecole = $home['id'];
					$classe = $ALL;
					break;		
				case 'BU_CLASSE' :
					$info = Kernel::getNodeInfo ($home['type'], $home['id']);
					//var_dump($info);
					$grville = $info['parent']['ALL']->vil_id_grville;
					$ville = $info['parent']['ALL']->eco_id_ville;
					$ecole = $info['parent']['id'];
					$classe = $home['id'];
					//echo "grville=$grville / ville=$ville / ecole=$ecole / classe=$classe";
					break;		
			}
		}

		$comboEcoles = $comboClasses = true;
		// On fore les valeurs des combos
		if ($profil) {
			switch ($profil) {
				case 'USER_VIL':
					$comboEcoles = $comboClasses = false;
					$ecole = $classe = $ALL;
					break;
			}
		}
		
		$tplListe = & new CopixTpl ();

$debug = false;
$start = microtime(true);
		$tplListe->assign ('combovilles', CopixZone::process ('annuaire|combovilles', array('grville'=>$grville, 'value'=>$ville, 'fieldName'=>'ville', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_ville(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllVilles'))))));
if($debug) echo "combovilles ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		
$start = microtime(true);
		if ($ville == $ALL && $comboEcoles) {
			$tplListe->assign ('comboecoles', CopixZone::process ('annuaire|comboecolesingrville', array('grville'=>$grville, 'value'=>$ecole, 'fieldName'=>'ecole', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_ecole(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllEcoles'))))));
if($debug) echo "comboecolesingrville ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		} elseif ($comboEcoles) {
			$tplListe->assign ('comboecoles', CopixZone::process ('annuaire|comboecolesinville', array('ville'=>$ville, 'value'=>$ecole, 'fieldName'=>'ecole', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_ecole(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllEcoles'))))));
if($debug) echo "comboecolesinville ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		}

		
//$dbw = & CopixDbFactory::getDbWidget ();
//$sql = "SELECT 1 AS DEBUT";
//$dbw->fetchAll ($sql);
   	
$start = microtime(true);
		if ($ville == $ALL && $ecole == $ALL && $comboClasses) {
			$tplListe->assign ('comboclasses', CopixZone::process ('annuaire|comboclassesingrville', array('grville'=>$grville, 'value'=>$classe, 'fieldName'=>'classe', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllClasses'))))));
if($debug) echo "comboclassesingrville ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		} elseif ($ecole == $ALL && $comboClasses) {
			$tplListe->assign ('comboclasses', CopixZone::process ('annuaire|comboclassesinville', array('ville'=>$ville, 'value'=>$classe, 'fieldName'=>'classe', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllClasses'))))));
if($debug) echo "comboclassesinville ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		} elseif ($ecole && $comboClasses) {
			$tplListe->assign ('comboclasses', CopixZone::process ('annuaire|comboclassesinecole', array('ecole'=>$ecole, 'value'=>$classe, 'fieldName'=>'classe', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"', 'linesSup'=>array(0=>array('value'=>$ALL, 'libelle'=>CopixI18N::get ('annuaire|annuaire.comboAllClasses'))))));
if($debug) echo "comboclassesinecole ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		} elseif ($comboClasses) {
			$tplListe->assign ('comboclasses', CopixZone::process ('annuaire|comboempty', array('fieldName'=>'classe', 'attribs'=>'class="annu_combo_popup" ONCHANGE="change_classe(this,this.form);"')));
if($debug) echo "comboempty ".date("H:i:s")." ".(microtime(true)-$start)."<br />";
		}
		
//$sql = "SELECT 1 AS FIN";
//$dbw->fetchAll ($sql);

		$visib = array (
			'USER_ELE' => Kernel::getUserTypeVisibility ('USER_ELE'),
			'USER_ENS' => Kernel::getUserTypeVisibility ('USER_ENS'),
			'USER_RES' => Kernel::getUserTypeVisibility ('USER_RES'),
			'USER_EXT' => Kernel::getUserTypeVisibility ('USER_EXT'),
			'USER_ADM' => Kernel::getUserTypeVisibility ('USER_ADM'),
			'USER_VIL' => Kernel::getUserTypeVisibility ('USER_VIL'),
		);
		//print_r($visib);
		//print_r($profils);
    
		if (!$profils && $visib['USER_ELE']!='NONE') $profils['ELE']=1;
		if (!$profils && $visib['USER_ENS']!='NONE') $profils['PEC']=1;
		if (!$profils && $visib['USER_RES']!='NONE') $profils['PAR']=1;
		if (!$profils && $visib['USER_EXT']!='NONE') $profils['EXT']=1;
		if (!$profils && $visib['USER_ADM']!='NONE') $profils['ADM']=1;
		if (!$profils && $visib['USER_VIL']!='NONE') $profils['VIL']=1;
		//print_r($profils);
    
		// Si on restreint a un profil
		if ($profil && $visib[$profil]!='NONE') {
			switch ($profil) {
				case 'USER_VIL':
					$profils = array();
					$profils['VIL']=1;
					break;
			}
		}
		
		// =============== ELEVES =========================
		$eleves = array();
		if (isset($profils['ELE']) && $grville && $ville && $ecole && $classe && $visib['USER_ELE']!='NONE') {
			if ($classe != $ALL)	// Une classe pr�cise
				$eleves = $annuaireService->getEleves ('BU_CLASSE', $classe);
			elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une �cole
				$eleves = $annuaireService->getEleves ('BU_ECOLE', $ecole);
			elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
				$eleves = $annuaireService->getEleves ('BU_VILLE', $ville);
			elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
				$eleves = $annuaireService->getEleves ('BU_GRVILLE', $grville);
		}
			
		// =============== PERSONNEL =========================
		$personnel = array();
		if (isset($profils['PEC']) && $grville && $ville && $ecole && $classe && $visib['USER_ENS']!='NONE') {
			if ($classe != $ALL)	// Une classe pr�cise
				$personnel = $annuaireService->getPersonnel ('BU_CLASSE', $classe);
			elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une �cole
				$personnel = $annuaireService->getPersonnel ('BU_ECOLE', $ecole);
			elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
				$personnel = $annuaireService->getPersonnel ('BU_VILLE', $ville);
			elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
				$personnel = $annuaireService->getPersonnel ('BU_GRVILLE', $grville);
		}
		
		// =============== PARENTS =========================
		$parents = array();
		if (isset($profils['PAR']) && $grville && $ville && $ecole && $classe && $visib['USER_RES']!='NONE') {
			if ($classe != $ALL)	// Une classe pr�cise
				$parents = $annuaireService->getParents ('BU_CLASSE', $classe);
			elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une �cole
				$parents = $annuaireService->getParents ('BU_ECOLE', $ecole);
			elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
				$parents = $annuaireService->getParents ('BU_VILLE', $ville);
			elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
				$parents = $annuaireService->getParents ('BU_GRVILLE', $grville);
		}
		
		// =============== PERSONNEL ADMINISTRATIF =========================
		$adm = array();
		if (isset($profils['ADM']) && $grville && $ville && $ecole && $classe && $visib['USER_ADM']!='NONE') {
			if ( ($classe != $ALL || $classe == $ALL) && $ecole != $ALL) // Les classes d'une �cole
				$adm = $annuaireService->getPersonnelAdm ('BU_ECOLE', $ecole);
			elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
				$adm = $annuaireService->getPersonnelAdm ('BU_VILLE', $ville);
			elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
				$adm = $annuaireService->getPersonnelAdm ('BU_GRVILLE', $grville);
		}
		
		// =============== PERSONNEL EXTERIEUR =========================
		$ext = array();
		if (isset($profils['EXT']) && $grville && $ville && $ecole && $classe && $visib['USER_EXT']!='NONE') {
			if ($classe != $ALL)	// Une classe pr�cise
				$ext = $annuaireService->getPersonnelExt ('BU_CLASSE', $classe);
			elseif ($classe == $ALL && $ecole != $ALL) // Les classes d'une �cole
				$ext = $annuaireService->getPersonnelExt ('BU_ECOLE', $ecole);
			elseif ($classe == $ALL && $ecole == $ALL && $ville != $ALL) // Les classes d'une ville
				$ext = $annuaireService->getPersonnelExt ('BU_VILLE', $ville);
			elseif ($classe == $ALL && $ecole == $ALL && $ville == $ALL) // Les classes d'un groupe de villes
				$ext = $annuaireService->getPersonnelExt ('BU_GRVILLE', $grville);
		}
		
		// =============== PERSONNEL VILLE =========================
		$vil = array();
		if (isset($profils['VIL']) && $grville && $ville && $visib['USER_VIL']!='NONE') {
			if ($ville != $ALL) // Dans une ville
				$vil = $annuaireService->getPersonnelVil ('BU_VILLE', $ville);
			elseif ($ville == $ALL) // Dans un groupe de villes
				$vil = $annuaireService->getPersonnelVil ('BU_GRVILLE', $grville);
		}
		
		$droits = array(
			'checkEleves'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_ELEVES'),
			'checkParents'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_PARENTS'),
			'checkPersonnel'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_PERSONNEL'),
			'checkPersonnelAdm'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_PERSONNEL_ADM'),
			'checkPersonnelVil'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_PERSONNEL_VIL'),
			'checkPersonnelExt'=>$annuaireService->canMakeInAnnuaire('POPUP_CHECK_ALL_PERSONNEL_EXT'),
		);
		
		$tplListe->assign ('TITLE_PAGE', CopixI18N::get ('annuaire|annuaire.moduleDescription'));
		$tplListe->assign ('LANGUE', PluginI18n::getLang());
		$tplListe->assign ('field', $field);
		$tplListe->assign ('grville', $grville);
		$tplListe->assign ('eleves', $eleves);
		$tplListe->assign ('personnel', $personnel);
		$tplListe->assign ('parents', $parents);
		$tplListe->assign ('ext', $ext);
		$tplListe->assign ('adm', $adm);
		$tplListe->assign ('vil', $vil);
		$tplListe->assign ('profils', $profils);
		$tplListe->assign ('droits', $droits);
		$tplListe->assign ('ville', $ville);
		$tplListe->assign ('ecole', $ecole);
		$tplListe->assign ('classe', $classe);
		$tplListe->assign ('visib', $visib);
		$tplListe->assign ('profil', $profil);
		$result = $tplListe->fetch('getpopup.tpl');

		echo $result;
		//print_r($rEcole);
		/*
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('annuaire|annuaire.moduleDescription'));
		$tpl->assign ('MAIN', $result);
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		*/
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}
}

?>