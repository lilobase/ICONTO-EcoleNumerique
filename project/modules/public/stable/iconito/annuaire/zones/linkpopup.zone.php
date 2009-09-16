<?php

/**
 * Zone qui affiche un lien permettant d'ouvrir l'annuaire avec les utilisateurs
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneLinkPopup extends CopixZone {

	/**
	 * Zone qui affiche un lien permettant d'ouvrir l'annuaire avec les utilisateurs
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/23
	 * @param string $field Nom du champ où vont figurer les logins
	 * @param string $profil (option) Profil qu'on veut afficher par defaut
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$field = isset($this->getParam('field')) ? $this->getParam('field') : NULL;
		$profil = $this->getParam('profil');

		$tpl = & new CopixTpl ();
		$tpl->assign ('field', $field);
		$tpl->assign ('profil', $profil);
    $toReturn = $tpl->fetch ('linkpopup.tpl');

    return true;
	}

}


?>
