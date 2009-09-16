<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les classes d'une école
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboClassesInEcole extends CopixZone {

	/**
	 * Affiche la liste déroulante avec toutes les classes d'une école
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/06
	 * @param integer $ecole Id de l'école
	 * @param integer $value Valeur actuelle de la combo
	 * @param string $fieldName Nom du champ de type SELECT qui en résulte
	 * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
	 * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez l'école"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$ecole = isset($this->getParam('ecole')) ? $this->getParam('ecole') : NULL;
		$value = isset($this->getParam('value')) ? $this->getParam('value') : 0;
		$fieldName = isset($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
		$attribs = isset($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;
		$linesSup = isset($this->getParam('linesSup')) ? $this->getParam('linesSup') : NULL;
		
		$classes = $annuaireService->getClassesInEcole ($ecole, array('getNodeInfo_light'=>1) );

		$tpl = & new CopixTpl ();
		$tpl->assign('items', $classes);
		$tpl->assign('value', $value);
		$tpl->assign('fieldName', $fieldName);
		$tpl->assign('attribs', $attribs);
		$tpl->assign('linesSup', $linesSup);
		
    $toReturn = $tpl->fetch ('comboclasses.tpl');
    return true;
	}

}


?>
