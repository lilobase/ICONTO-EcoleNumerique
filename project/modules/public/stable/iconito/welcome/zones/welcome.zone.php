<?php

/**
 * Zone qui affiche la page d'accueil
 * 
 * @package Iconito
 * @subpackage Welcome
 */

class ZoneWelcome extends CopixZone {

	/**
	 * Affiche la page d'accueil
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/05
	 */

	function _createContent (&$toReturn) {
	
		// $this->getParam('page',null)
		
		$tpl = & new CopixTpl ();

		$tpl->assign ('isDemo', CopixConfig::get ('default|isDemo'));
		
		/*
		$tpl->assign ("zoneEcoles", CopixZone::process ('welcome|Ecoles', array(
		 'titre'=>'Les &eacute;coles :',
		 'colonnes'=>1,
		 'grville'=>1,
		 'ville'=>1,
		)));
		
		$tpl->assign ("zonePhotos", CopixZone::process ('welcome|Photos', array(
		 'mode'=>'dewslider',
		 'titre'=>'Test photos Dewslider :',
		 'album'=>1,
		 'dossier'=>0,
		 'width'=>640,
		 'height'=>230,
		 'legendes'=>true,
		)));
		
		$tpl->assign ("zoneActualites", CopixZone::process ('welcome|Actualites', array(
		 'titre'=>'',
		 'blog'=>'Ecole_du_bois_fleuri',
		 'nb'=>2,
		 'colonnes'=>2,
		 'chapo'=>false,
		 'hreflib'=>'',
		))); 
		*/
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_welcome.css"));
		$toReturn = $tpl->fetch('welcome_'.CopixI18N::getLang().'.tpl');
		return true;
	}
}
?>
