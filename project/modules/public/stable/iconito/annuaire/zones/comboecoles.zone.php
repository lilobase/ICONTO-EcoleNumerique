<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les écoles d'une ville
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboEcoles extends CopixZone {

	/**
	 * Affiche la liste déroulante avec toutes les écoles d'une ville
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/06
	 * @param integer $ville Id de la ville
	 * @param integer $value Valeur actuelle de la combo
	 * @param string $fieldName Nom du champ de type SELECT qui en résulte
	 * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$ville = isset($this->params['ville']) ? $this->params['ville'] : NULL;
		$value = isset($this->params['value']) ? $this->params['value'] : 0;
		$fieldName = isset($this->params['fieldName']) ? $this->params['fieldName'] : NULL;
		$attribs = isset($this->params['attribs']) ? $this->params['attribs'] : NULL;
		
		$ecoles = $annuaireService->getEcolesInVille ($ville, 'TYPE');

		$tpl = & new CopixTpl ();
		$tpl->assign('items', $ecoles);
		$tpl->assign('value', $value);
		$tpl->assign('fieldName', $fieldName);
		$tpl->assign('attribs', $attribs);
		
    $toReturn = $tpl->fetch ('comboecoles.tpl');
    return true;
	}

}


?>
