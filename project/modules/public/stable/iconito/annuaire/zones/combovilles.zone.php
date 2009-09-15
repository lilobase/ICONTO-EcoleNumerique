<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les villes d'un groupe de ville
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboVilles extends CopixZone {

	/**
	 * Affiche la liste déroulante avec toutes les villes d'un groupe de ville
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/17
	 * @param integer $grville Id du groupe de ville
	 * @param integer $value Valeur actuelle de la combo
	 * @param string $fieldName Nom du champ de type SELECT qui en résulte
	 * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
	 * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez la ville"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$grville = isset($this->params['grville']) ? $this->params['grville'] : NULL;
		$value = isset($this->params['value']) ? $this->params['value'] : 0;
		$fieldName = isset($this->params['fieldName']) ? $this->params['fieldName'] : NULL;
		$attribs = isset($this->params['attribs']) ? $this->params['attribs'] : NULL;
		$linesSup = isset($this->params["linesSup"]) ? $this->params["linesSup"] : NULL;
		
		$villes = $annuaireService->getVillesInGrville ($grville);

		$tpl = & new CopixTpl ();
		$tpl->assign('items', $villes);
		$tpl->assign('value', $value);
		$tpl->assign('fieldName', $fieldName);
		$tpl->assign('attribs', $attribs);
		$tpl->assign('linesSup', $linesSup);
		
    $toReturn = $tpl->fetch ('combovilles.tpl');
    return true;
	}

}


?>
