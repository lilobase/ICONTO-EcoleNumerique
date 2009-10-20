<?php

/**
 * Affichage de la liste des ecoles
 * 
 * @package Iconito
 * @subpackage Welcome
 */
class ZoneEcoles extends CopixZone {

	/**
	 * Affiche la liste des ecoles
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/11/10
	 * @param string $titre Titre a donner a la zone
	 * @param integer $ajax 1 si on veut afficher le lien vers la fiche en Ajax, 0 pour afficher le lien Href classique. Par defaut : 0
	 * @param integer $colonnes Nb de colonnes. Par defaut : 1
	 * @param integer $grville Id du groupe de villes dans lequel on pioche les ecoles. Par defaut : 1
	 * @param integer $ville Id de la ville dans laquelle on pioche les ecoles. Par defaut : null (prend le groupe de ville). Si on passe un grville et une ville, on prend la ville
	 * @param string $groupBy Si regroupement. Peut valoir "type"
	 * @param integer $dispType 1 pour afficher le type des ecoles, 0 pour n'afficher que leur nom. Par defaut : 1
	 */
	function _createContent (&$toReturn) {
		
		$annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');
		
		$titre = $this->getParam('titre');
		$ajax = $this->getParam('ajax',false);
		$colonnes = $this->getParam('colonnes',1);
		$grville = $this->getParam('grville',1);
		$ville = $this->getParam('ville',null);
		$pGroupBy = $this->getParam('groupBy');
		$pDispType = $this->getParam('dispType');
		
		if ($ville)
			$list = $annuaireService->getEcolesInVille ($ville);
		elseif ($grville)
			$list = $annuaireService->getEcolesInGrville ($grville);
		//var_dump($list);
		
		if ($pGroupBy == 'type') {
			usort( $list, array($this,"usort_ecoles"));
		}
		
		$nbEcoles = 0;
		foreach ($list as $ecole) {
			if ($ecole['id']>0)
				$nbEcoles++;
		}
		
		// Nb elements par colonnes
		$parCols = ceil($nbEcoles/$colonnes);

		$tpl = & new CopixTpl ();
		$tpl->assign ('titre', $titre);
		$tpl->assign ('ajax', $ajax);
		$tpl->assign ('list', $list);
		$tpl->assign ('parCols', $parCols);
		$tpl->assign ('widthColonne', (round(100/$colonnes,1)-1).'%');

		$tpl->assign ('groupBy', $pGroupBy);
		$tpl->assign ('dispType', $pDispType);

		
		if ($nbEcoles>0)
			$toReturn = $tpl->fetch('zone_ecoles.tpl');
		
		return true;
		
	}
	
	
	// Tri des ecoles
	// CB 20/10/2009
	function usort_ecoles ($a, $b)
	{
		if ($a['type'] == $b['type']) {
			if ($a['nom'] == $b['nom']) {
				return 0;
			}
			return ($a['nom'] < $b['nom']) ? -1 : 1;
		}
		return ($a['type'] < $b['type']) ? -1 : 1;
	}


}
?>
