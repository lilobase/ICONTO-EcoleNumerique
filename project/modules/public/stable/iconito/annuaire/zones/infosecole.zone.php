<?php

/**
 * Zone qui affiche les infos d'une école (coordonnées, directeur, classes...)
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneInfosEcole extends CopixZone {

	/**
	 * Affiche les infos d'une école (coordonnées, directeur, classes...)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/18
	 * @param integer $rEcole Recordset de l'école
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$rEcole = isset($this->getParam('rEcole')) ? $this->getParam('rEcole') : NULL;

		if ($rEcole) {
			//print_r($rEcole);
			
			$ecole = $rEcole['id'];

			// BOOST 2.5s
			if( $this->getParam('classes') )
				$classes = $this->getParam('classes');
			else
				$classes = $annuaireService->getClassesInEcole($ecole);
			
			$rEcole['directeur'] = $annuaireService->getDirecteurInEcole($ecole);
			
			$rEcole['directeur'] = $annuaireService->checkVisibility( $rEcole['directeur'] );
			
			$rEcole['administratif'] = $annuaireService->getAdministratifInEcole($ecole);
			
			$rEcole['administratif'] = $annuaireService->checkVisibility( $rEcole['administratif'] );
			
			$tpl = & new CopixTpl ();
			$tpl->assign ('ecole', $rEcole);
			$tpl->assign ('classes', $classes);
			
			// BOOST 1s
			$tpl->assign ('comboecoles', CopixZone::process ('annuaire|comboecolesinville', array('ville'=>$rEcole['ALL']->vil_id_vi, 'value'=>$ecole, 'fieldName'=>'ecole', 'attribs'=>'CLASS="annu_combo_popup" ONCHANGE="if (this.value) this.form.submit();"')));

	    $toReturn = $tpl->fetch ('infosecole.tpl');
			
		}
		
    return true;
	}

}


?>
