<?php

/**
 * Zone ComboFolders, qui affiche la liste déroulante avec tous les dossiers d'une malle
 * 
 * @package Iconito
 * @subpackage	Malle
 */
class ZoneComboFolders extends CopixZone {

	/**
	 * Affiche la liste déroulante avec tous les dossiers d'une malle
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param integer $malle Id de la malle
	 * @param integer $folder Id du dossier (0 si racine)
	 * @param string $fieldName Nom du champ de type SELECT qui en résulte
	 * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
	 * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez le dossier"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
	 */
	function _createContent (&$toReturn) {

		//$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		$malleService = & CopixClassesFactory::Create ('malle|malleService');
		
		$tpl = & new CopixTpl ();
		$res = array();
		$malle = isset($this->params["malle"]) ? $this->params["malle"] : NULL;
		$folder = isset($this->params["folder"]) ? $this->params["folder"] : 0;
		$fieldName = isset($this->params["fieldName"]) ? $this->params["fieldName"] : NULL;
		$attribs = isset($this->params["attribs"]) ? $this->params["attribs"] : NULL;
		$linesSup = isset($this->params["linesSup"]) ? $this->params["linesSup"] : NULL;
		
		$res = $malleService->buildComboFolders ($malle);
		//print_r($res);
		$tpl->assign('combofolders', $res);
		$tpl->assign('folder', $folder);
		$tpl->assign('fieldName', $fieldName);
		$tpl->assign('attribs', $attribs);
		$tpl->assign('linesSup', $linesSup);
		
    $toReturn = $tpl->fetch ('combofolders.tpl');
    return true;
	}

}






?>
