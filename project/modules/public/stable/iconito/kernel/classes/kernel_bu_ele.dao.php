<?php

/**
 * Surcharge de la DAO Kernel_bu_ele
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele {

	/**
	 * Renvoie la liste des élèves rattachés à une classe et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getElevesInClasse ($classe) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_cusr AS login, LI.bu_type, LI.bu_id FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND E.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EC.id=".$classe." ORDER BY nom, prenom1";
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste des élèves rattachés à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getElevesInEcole ($ecole) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_cusr AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EC.ecole=".$ecole." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste des élèves rattachés à une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getElevesInVille ($ville) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_cusr AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND ECO.id_ville=".$ville." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	

	/**
	 * Renvoie la liste des élèves rattachés à un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getElevesInGrville ($grville) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_cusr AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND ECO.id_ville=VIL.id_vi AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND VIL.id_grville=".$grville." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}	

}




?>
