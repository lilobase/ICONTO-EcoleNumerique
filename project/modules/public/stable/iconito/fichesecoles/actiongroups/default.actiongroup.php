<?php
/**
 * Actiongroup du module Fichesecoles - Front office
 * 
 * @package	Iconito
 * @subpackage fichesecole
 */

_classInclude('fichesecoles|fichesecolesservice');
_classInclude('annuaire|annuaireservice');
_classInclude('blog|blogutils');

class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');

	}

	/**
   * Affichage de la fiche d'une ecole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param integer $id Id de l'ecole
   */
	 function fiche () {
		
		$id = $this->getRequest('id', null);

		$ecoleDAO = CopixDAOFactory::create('kernel|kernel_bu_ecole');
		$ficheDAO = CopixDAOFactory::create("fiches_ecoles");
		
		$criticErrors = array();
		
		if (!$rEcole = $ecoleDAO->get($id))
			$criticErrors[] = CopixI18N::get ('fichesecoles.error.param');
		elseif (!FichesEcolesService::canMakeInFicheEcole($id,'VIEW'))
			$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
			
		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));

		$rFiche = $ficheDAO->get($id);

		$tpl = & new CopixTpl ();
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_annuaire.js');

		$coords = null;
		if ($googleAdresse = AnnuaireService::googleMapsFormatAdresse ('ecole', $rEcole)) {
			//var_dump($googleAdresse);
			$rEcole->googleAdresse = $googleAdresse;
			if ($coords = AnnuaireService::googleMapsAdresseCoords ($googleAdresse)) {
				//var_dump($coords);
				CopixHtmlHeader::addJSLink('http://maps.google.com/maps?hl=en&amp;file=api&amp;v=2.x&amp;key='.CopixConfig::get ('fichesecoles|googleMapsKey'));
				$tpl->assign ('BODY_ON_LOAD', "loadGoogleMapsEcole(".$coords['latitude'].",".$coords['longitude'].");");
				$tpl->assign ('BODY_ON_UNLOAD', 'GUnload();');
			}
		}
		$rEcole->coords = $coords;


		$fiche = CopixZone::process('fiche',array('rEcole'=>$rEcole, 'rFiche'=>$rFiche));		
		
		$main = $fiche;
		$title = $rEcole->nom;
		if ($rEcole->type) $title .= ' ('.$rEcole->type.')';
		
		$tpl->assign ('TITLE_PAGE', $title);
		$menu = array();
		$menu[] = array(
			'url' => CopixUrl::get('public||getListBlogs'),
			'txt' => CopixI18N::get ('public|public.blog.annuaire'),
		);
		if (Kernel::is_connected())
			$menu[] = array(
				'url' => CopixUrl::get('annuaire||getAnnuaireEcole', array('ecole'=>$rEcole->numero)),
				'txt' => CopixI18N::get ('annuaire|annuaire.backEcole'),
			);

		if (FichesEcolesService::canMakeInFicheEcole($id,'MODIFY'))
			$menu[] = array(
				'url' => CopixUrl::get('admin|form', array('id'=>$rEcole->numero)),
				'txt' => CopixI18N::get ('kernel|kernel.btn.modify'),
			);

		$tpl->assign ('MENU', $menu);
		$tpl->assign ("MAIN", $main);
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

	}

	
	/**
   * Affichage de la fiche d'une ecole en Ajax
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param integer $id Id de l'ecole
   */
	 function ficheAjax () {

		$id = $this->getRequest('id', null);

		$ecoleDAO = CopixDAOFactory::create('kernel|kernel_bu_ecole');
		$ficheDAO = CopixDAOFactory::create("fiches_ecoles");
		
		if (!$rEcole = $ecoleDAO->get($id))
			$criticErrors[] = CopixI18N::get ('fichesecoles.error.param');
		elseif (!FichesEcolesService::canMakeInFicheEcole($id,'VIEW'))
			$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));


		$rFiche = $ficheDAO->get($id);
		
		$tpl = & new CopixTpl ();

		$coords = null;
		if ($googleAdresse = AnnuaireService::googleMapsFormatAdresse ('ecole', $rEcole)) {
			//var_dump($googleAdresse);
			$rEcole->googleAdresse = $googleAdresse;
			if ($coords = AnnuaireService::googleMapsAdresseCoords ($googleAdresse)) {
				//var_dump($coords);
				CopixHtmlHeader::addJSLink('http://maps.google.com/maps?file=api&amp;v=2.x&amp;key='.CopixConfig::get ('fichesecoles|googleMapsKey'));
				CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_annuaire.js');
				$tpl->assign ('BODY_ON_LOAD', "loadGoogleMapsEcole(".$coords['latitude'].",".$coords['longitude'].");");
				$tpl->assign ('BODY_ON_UNLOAD', 'GUnload();');
			}
		}
		$rEcole->coords = $coords;
		
		$fiche = CopixZone::process('fiche',array('rEcole'=>$rEcole, 'rFiche'=>$rFiche, 'isAjax'=>true));		
		
		$result = $fiche;

		header('Content-type: text/html; charset=utf-8');
		echo utf8_encode($result);
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);

	}

	/**
   * Affichage de la photo d'une fiche d'une ecole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/09
	 * @param string $photo Fichier de la photo
   */
	 function photo () {
		
		$photo = $this->getRequest('photo', null);
		
		if ($photo != null) {
			
			$photo = str_replace (array("..","/"), array("","/"), $photo);
		
			$file = COPIX_VAR_PATH.CopixConfig::get ('fichesecoles|photoPath').$photo;
			if (@file_exists($file)) {
				if ($size = @getimagesize ($file)) {
					$formats = array(IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF);
						if (in_array($size[2],$formats)) {
							$format_pict = strrchr($photo,'.');
							return new CopixActionReturn (COPIX_AR_BINARY, $file, 'image/'.substr($format_pict,1));
						}
				}
			}
		}
		header("HTTP/1.0 404 Not Found");
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}


	/**
   * Affichage de la fiche d'une ecole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param integer $id Id de l'ecole
   */
	 function blogs () {
		
		$id = $this->getRequest('id', null);

		$ecoleDAO = CopixDAOFactory::create('kernel|kernel_bu_ecole');
		$ficheDAO = CopixDAOFactory::create("fiches_ecoles");
		
		$criticErrors = array();
		if (!$rEcole = $ecoleDAO->get($id))
			$criticErrors[] = CopixI18N::get ('fichesecoles.error.param');
		elseif (!FichesEcolesService::canMakeInFicheEcole($id,'VIEW'))
			$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));

		$arClasses = AnnuaireService::getClassesInEcole ($rEcole->numero, array('forceCanViewEns'=>true, 'onlyWithBlog'=>true, 'enseignant'=>false));
		
		$rEcole->blog = getNodeBlog ('BU_ECOLE', $rEcole->numero);

		$tpl = & new CopixTpl ();
		$tpl->assign ('rEcole', $rEcole);
	  $tpl->assign ('arClasses', $arClasses);
		$result = $tpl->fetch ('blogs.tpl');

		header('Content-type: text/html; charset=utf-8');
		echo utf8_encode($result);
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);

	}












}


?>
