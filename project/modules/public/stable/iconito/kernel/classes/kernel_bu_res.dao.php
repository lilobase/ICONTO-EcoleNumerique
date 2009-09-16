<?php

/**
 * Surcharge de la DAO Kernel_bu_res
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_res {

	/**
	 * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une classe
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getParentsInClasse ($classe) {
		$dbw = & CopixDbFactory::getDbWidget ();
	  $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_cusr AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_affectation EA, kernel_link_bu2user LI, copixuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND LI.user_id=U.id_cusr AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND EA.classe=".$classe." ORDER BY R.nom, R.prenom1";
		return $dbw->fetchAll ($query);
	}
	
	/**
	 * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getParentsInEcole ($ecole) {
		$dbw = & CopixDbFactory::getDbWidget ();
	  $query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_cusr AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_link_bu2user LI, copixuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND LI.user_id=U.id_cusr AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND EA.etablissement=".$ecole." ORDER BY R.nom, R.prenom1";
		return $dbw->fetchAll ($query);
	}
	
	/**
	 * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école d'une ville
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getParentsInVille ($ville) {
		$dbw = & CopixDbFactory::getDbWidget ();
  	$query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_cusr AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_bu_ecole E, kernel_link_bu2user LI, copixuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND EA.etablissement=E.numero AND LI.user_id=U.id_cusr AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND E.id_ville=".$ville." ORDER BY R.nom, R.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}
	

	/**
	 * Renvoie la liste des parents ayant un compte utilisateur et un enfant rattaché à une école d'une ville d'un groupe de villes
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getParentsInGrville ($grville) {
		$dbw = & CopixDbFactory::getDbWidget ();
  	$query = "SELECT DISTINCT(R.numero) AS id, R.nom, R.prenom1 AS prenom, U.login_cusr AS login, LI.bu_type, LI.bu_id FROM kernel_bu_responsable R, kernel_bu_sexe S, kernel_bu_responsables RE, kernel_bu_eleve_admission EA, kernel_bu_ecole E, kernel_bu_ville V, kernel_link_bu2user LI, copixuser U WHERE R.id_sexe=S.id_s AND R.numero=RE.id_responsable AND RE.type='responsable' AND RE.id_beneficiaire=EA.eleve AND RE.type_beneficiaire='eleve' AND EA.etablissement=E.numero AND E.id_ville=V.id_vi AND LI.user_id=U.id_cusr AND LI.bu_type='USER_RES' AND LI.bu_id=R.numero AND  V.id_grville=".$grville." ORDER BY R.nom, R.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}	

}




?>