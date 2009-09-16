<?php

/**
 * Surcharge de la DAO Kernel_bu_personnel
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_personnel {

	/**
	 * Renvoie la liste du personnel école rattaché à une classe et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getPersonnelInClasse ($classe) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$classe." AND PE.type_ref='CLASSE' ORDER BY PR.priorite, P.nom, P.prenom1";
		return $dbw->fetchAll ($query);
	}
	
	/**
	 * Renvoie la liste du personnel école rattaché à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getPersonnelInEcole ($ecole) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}
	
	/**
	 * Renvoie la liste du personnel école rattaché aux écoles d'une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelInVille ($ville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}
	

	/**
	 * Renvoie la liste du personnel école rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelInGrville ($grville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}	


	/**
	 * Renvoie la liste du personnel administratif rattaché à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInEcole ($ecole) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}
	
	/**
	 * Renvoie la liste du personnel administratif rattaché aux écoles d'une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInVille ($ville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}
	

	/**
	 * Renvoie la liste du personnel administratif rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInGrville ($grville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}	

	
	/**
	 * Renvoie la liste des agents de villes rattachés a une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/11/06
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelVilInVille ($ville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_vi=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}


	/**
	 * Renvoie la liste des agents de ville rattachés aux villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/11/06
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelVilInGrville ($grville) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.login_cusr AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, copixuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_cusr AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_grville=".$grville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return $dbw->fetchAll ($query);
	}

}




?>