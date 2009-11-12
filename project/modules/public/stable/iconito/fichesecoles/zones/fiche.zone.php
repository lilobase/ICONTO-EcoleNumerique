<?php

/**
 * Fiche d'une ecole
 * 
 * @package Iconito
 * @subpackage Fichesecoles
 */

 _classInclude ('annuaire|annuaireservice');
_classInclude ('blog|blogutils');

class ZoneFiche extends CopixZone {


	/**
	 * Detail d'une ecole
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/09/03
	 * @param object $rEcole Recordset de l'ecole
	 * @param object $rFiche Recordset de la fiche ecole
	 * @param boolean $isAjax Indique si la fiche est affichee en Ajax ou pas
	 */

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$rEcole = $this->getParam('rEcole');
		$rFiche = $this->getParam('rFiche');
		$isAjax = $this->getParam('isAjax');

		$arClasses = AnnuaireService::getClassesInEcole ($rEcole->numero, array('forceCanViewEns'=>true, 'withNiveaux'=>true));
		usort($arClasses, array("ZoneFiche", "_usortClasses"));
		
		//var_dump($arClasses);
		
		$canViewEns = Kernel::getUserVisibility('USER_ENS', -1);
		
		$canModify = FichesEcolesService::canMakeInFicheEcole($rEcole->numero,'MODIFY');
		
		$blog = getNodeBlog ('BU_ECOLE', $rEcole->numero, array('is_public'=>1));
		//var_dump($blog);
		$arClassesBlogs = false;
    if ($blog)
			$rEcole->blog = $blog;
		else // Si pas de blog, on regarde s'il y a des blogs publics de classes
			$arClassesBlogs = AnnuaireService::getClassesInEcole ($rEcole->numero, array('onlyWithBlog'=>true, 'onlyWithBlogIsPublic'=>1, 'enseignant'=>false));
		//Kernel::deb($arClassesBlogs);
		
		
		$rEcole->directeur = AnnuaireService::getDirecteurInEcole($rEcole->numero);
		//var_dump($rEcole);
		
	  $tpl->assign ('rEcole', $rEcole);
	  $tpl->assign ('rFiche', $rFiche);
	  $tpl->assign ('isAjax', $isAjax);
	  $tpl->assign ('arClasses', $arClasses);
	  $tpl->assign ('arClassesBlogs', $arClassesBlogs);
	  $tpl->assign ('canModify', $canModify);
	  $tpl->assign ('googleMapsKey', CopixConfig::get ('fichesecoles|googleMapsKey'));
	  $tpl->assign ('canViewEns', $canViewEns);

		if ($isAjax)
	    $toReturn = $tpl->fetch ('ficheajax.tpl');
		else
	    $toReturn = $tpl->fetch ('fiche.tpl');
		return true;
	 
	}
	
	function _usortClasses ($a, $b) {
		//var_dump($a);
		$aNiv1 = isset($a['niveaux'][0]) ? $a['niveaux'][0]->id_n : '';
		$bNiv1 = isset($b['niveaux'][0]) ? $b['niveaux'][0]->id_n : '';
		if ($aNiv1 == $bNiv1) {
			$aNiv2 = isset($a['niveaux'][1]) ? $a['niveaux'][1]->id_n : '';
			$bNiv2 = isset($b['niveaux'][1]) ? $b['niveaux'][1]->id_n : '';
			if ($aNiv2 == $bNiv2) {
				$aNiv3 = isset($a['niveaux'][2]) ? $a['niveaux'][2]->id_n : '';
				$bNiv3 = isset($b['niveaux'][2]) ? $b['niveaux'][2]->id_n : '';
				if ($aNiv3 == $bNiv3) {
					return 0;
				}
				return ($aNiv3 < $bNiv3) ? -1 : 1;
			}
			return ($aNiv2 < $bNiv2) ? -1 : 1;
    }
    return ($aNiv1 < $bNiv1) ? -1 : 1;
	
	
	}
	
}
?>
