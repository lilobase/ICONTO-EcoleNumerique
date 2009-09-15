<?php

/**
 * Zone qui affiche les infos d'une classe (enseignant et élèves)
 * 
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneInfosClasse extends CopixZone {

	/**
	 * Affiche les infos d'une classe (enseignant et élèves)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/18
	 * @param integer $rClasse Recordset de la classe
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$rClasse = isset($this->params['rClasse']) ? $this->params['rClasse'] : NULL;

		if ($rClasse) {
			$classe = $rClasse['id'];
			
			$enseignants = $annuaireService->getEnseignantInClasse ($classe);
			$enseignants = $annuaireService->checkVisibility( $enseignants );
			$eleves = $annuaireService->getElevesInClasse ($classe);
			// $eleves = $annuaireService->checkVisibility( $eleves );
			
			if( Kernel::getUserTypeVisibility( 'USER_ELE' ) == 'NONE' )
				$rClasse["eleves"] = 'NONE';
			else
				$rClasse["eleves"] = $eleves;

			if( Kernel::getUserTypeVisibility( 'USER_ENS' ) == 'NONE' )
				$rClasse["enseignants"] = 'NONE';
			else
				$rClasse["enseignants"] = $enseignants;
			
			$tpl = & new CopixTpl ();
			$tpl->assign ('classe', $rClasse);
	    $toReturn = $tpl->fetch ('infosclasse.tpl');
		}
		
    return true;
	}

}


?>
