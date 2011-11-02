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

class ActionGroupDefault extends EnicActionGroup {

	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');

	}

	/**
   * Affichage de la fiche d'une ecole
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param integer $id Id de l'ecole
	 * @param integer $popup 1 pour afficher la fiche en popup Fancybox
   */
	 function fiche () {
		
		$id = $this->getRequest('id', null);
		$iPopup = CopixRequest::getInt('popup');

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

		$tpl = new CopixTpl ();
		CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_fichesecoles.js');

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
		if ($rEcole->type)
                    $title .= ' ('.$rEcole->type.')';
		
		$tpl->assign ('TITLE_PAGE', $title);
        if (strtolower($rEcole->type) == 'crèche')
            $tpl->assign ('TITLE_CONTEXT', CopixI18N::get ('kernel|kernel.codes.mod_fichesecoles_creche'));
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
    
		$tpl->assign ('MENU', $menu);
		$tpl->assign ("MAIN", $main);
    
    
    if ($iPopup) {
      $ppo = new CopixPPO ();
      $ppo->fiche = $fiche;
      $ppo->TITLE = $title;
      //return _arDirectPPO ($ppo, 'fiche_popup.tpl');
      //return new CopixActionReturn (COPIX_AR_NONE, 0);
      return _arPPO ($ppo, array ('template'=>'fiche_popup.tpl', 'mainTemplate'=>'main|main_fancy.php'));	

    }
    
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

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
							header("Content-Type: image/".substr($format_pict,1));
							readfile($file, 'r+');
							return new CopixActionReturn (COPIX_AR_NONE, 0);
						}
				}
			}
		}
		header("HTTP/1.0 404 Not Found");
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}


	/**
     * Telechargement d'un document joint a une fiche
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2011/01/31
     * @param string $fichier Nom du fichier
     *
     */
    function processDoc() {
        $iFichier = CopixRequest::get('fichier');
        $malleService = & CopixClassesFactory::Create ('malle|malleService');
        preg_match('/^([0-9]+)_(.+)$/', $iFichier, $regs);
        $file = COPIX_VAR_PATH . CopixConfig::get('fichesecoles|docPath') . $iFichier;
        if (@file_exists($file)) {
            $filename = $regs[2];
            return _arFile ($file, array ('filename'=>$filename, 'content-type'=>$malleService->getMimeType($file), 'content-disposition'=>'attachement'));
        }
        header("HTTP/1.0 404 Not Found");
        return new CopixActionReturn(COPIX_AR_NONE, 0);
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
		$pAnnee = $this->getRequest('annee', null);
		
		//
		
		$ecoleDAO = CopixDAOFactory::create('kernel|kernel_bu_ecole');
		$ficheDAO = CopixDAOFactory::create("fiches_ecoles");
		
		$criticErrors = array();
		if (!$rEcole = $ecoleDAO->get($id))
			$criticErrors[] = CopixI18N::get ('fichesecoles.error.param');
		elseif (!FichesEcolesService::canMakeInFicheEcole($id,'VIEW'))
			$criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

		if ($criticErrors)
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('annuaire||')));

		$arClasses = AnnuaireService::getClassesInEcole ($rEcole->numero, array('forceCanViewEns'=>true, 'onlyWithBlog'=>true, 'onlyWithBlogIsPublic'=>1, 'enseignant'=>false, 'annee'=>$pAnnee));

		$rEcole->blog = getNodeBlog ('BU_ECOLE', $rEcole->numero, array('is_public'=>1));

		$tpl = new CopixTpl ();
		$tpl->assign ('rEcole', $rEcole);
	  $tpl->assign ('arClasses', $arClasses);
		
		if ( ($anneeDebutBlogs = CopixConfig::get ('fichesecoles|anneeDebutBlogs')) ) {

			$anneeFinBlogs = Kernel::getAnneeScolaireCourante()->id_as;
			
			//Kernel::deb("anneeDebutBlogs=$anneeDebutBlogs / anneeFinBlogs=$anneeFinBlogs");
			
			if (!$pAnnee)
				$pAnnee = $anneeFinBlogs;
						
			if ($anneeFinBlogs > $anneeDebutBlogs) {
				$comboAnnees = CopixZone::process('kernel|combo_annees', array('name'=>'annee', 'selected'=>$pAnnee, 'debut'=>$anneeDebutBlogs, 'fin'=>$anneeFinBlogs, 'extra2'=>'onChange="ficheViewBlogs('.$id.',this.options[this.selectedIndex].value);"'));		
				$tpl->assign ('comboAnnees', $comboAnnees);
			}
			
		
		}
		
		
		$result = $tpl->fetch ('blogs.tpl');
		

		header('Content-type: text/html; charset=utf-8');
		//echo utf8_encode($result);
		echo $result;
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);

	}












}


?>
