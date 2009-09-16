<?php

/**
 * Surcharge du DAO peri_infos_benef_valeurs
 * 
 * @package inscription
 */
class DAOInfos_valeurs {

	/**
	 * Les valeurs des infos specifiques d'un beneficiaire
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/07/15
	 * @param string $pBenefType Type du beneficiaire
	 * @param integer $pBenefId Id du beneficiaire
	 * @return array Liste des valeurs. Cle = Id du champ, valeur = valeur du champ
	 */
	function getForBenef ($pBenefType, $pBenefId) {
		$sql = "SELECT * FROM pe_infos_valeurs WHERE benef_id=:benef_id AND benef_type=:benef_type";
		$list = _doQuery ($sql, array(':benef_type'=>$pBenefType, ':benef_id'=>$pBenefId));
		$ar = array();
		foreach ($list as $v) {
			$ar[$v->champ] = $v->valeur;
		
		}
		//print_r($ar);
		return $ar;
	}
	

	/**
	 * Met a jour une valeur deja dans la base, sur la base du beneficiaire (type+id) et du champ
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/07/15
	 * @param string $pBenefType Type du beneficiaire
	 * @param integer $pBenefId Id du beneficiaire
	 * @param integer $pChamp Id du champ
	 * @param string $pValeur Nouvelle valeur
	 * @return none
	 */
	function update2 ($pBenefType, $pBenefId, $pChamp, $pValeur) {
		$sql = "UPDATE pe_infos_valeurs SET valeur=:valeur WHERE benef_id=:benef_id AND benef_type=:benef_type AND champ=:champ";
		_doQuery ($sql, array(':valeur'=>$pValeur, ':benef_type'=>$pBenefType, ':benef_id'=>$pBenefId, ':champ'=>$pChamp));
	}


	/**
	 * Efface les valeurs d'un ou plusieurs champs pour un beneficiaire
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/07/16
	 * @param string $pBenefType Type du beneficiaire
	 * @param integer $pBenefId Id du beneficiaire
	 * @param mixed $pChamp Id du champ, ou tableau d'Ids
	 * @return none
	 */
	function delete2 ($pBenefType, $pBenefId, $pChamp) {
		if (is_array($pChamp)) {
			$sql = "DELETE FROM pe_infos_valeurs WHERE benef_id=:benef_id AND benef_type=:benef_type AND champ IN (".implode(",",$pChamp).")";
			_doQuery ($sql, array(':benef_type'=>$pBenefType, ':benef_id'=>$pBenefId));
		} else {
			$sql = "DELETE FROM pe_infos_valeurs WHERE benef_id=:benef_id AND benef_type=:benef_type AND champ=:champ";
			_doQuery ($sql, array(':benef_type'=>$pBenefType, ':benef_id'=>$pBenefId, ':champ'=>$pChamp));
		}
	}


	/**
	 * Enregistrement des donnees specifiques d'un beneficiaire
   *	
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/07/30
	 * @param date $pBenefType Type du beneficiaire
	 * @param date $pBenefId Id du beneficiaire
	 * @param array $pInfosBenefValeurs Valeurs deja presentes dans la base
	 * @param array $pValeurs Valeurs submitees par le formulaire
	 * @return todo
	 */
	function saveInfosValeurs ($pBenefType, $pBenefId, $pInfosBenefValeurs, $pValeurs, $auteurLogin) {
		
		
		if ($pValeurs) {
			foreach ($pValeurs as $idChamp=>$valeur) {
				if (isset($pInfosBenefValeurs[$idChamp])) { // Deja
					if ($pInfosBenefValeurs[$idChamp]!=$valeur) { // On MAJ seulement si valeur differente
						_dao ('kernel|infos_valeurs')->update2($pBenefType, $pBenefId, $idChamp, $valeur);
					}
				} elseif ($valeur) { // Si ajout
					$rValeur = _record('kernel|infos_valeurs');
					$rValeur->benef_type = $pBenefType;
					$rValeur->benef_id = $pBenefId;
					$rValeur->champ = $idChamp;
					$rValeur->valeur = $valeur;
					_ioDAO ('kernel|infos_valeurs')->insert ($rValeur);
				}
			}
		} // Fin if $pValeurs
		
		// Donnees dans la base mais non enregistrees, a effacer
		$reste = array_diff_key($pInfosBenefValeurs, $pValeurs);
		if ($reste) {
			_dao ('kernel|infos_valeurs')->delete2($pBenefType, $pBenefId, array_keys($reste));
		}
		
	}
	
	
}

?>