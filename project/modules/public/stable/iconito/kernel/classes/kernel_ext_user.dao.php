<?php

/**
 * Surcharge de la DAO Kernel_bu_ext
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_ext_user {

	/**
	 * Renvoie la liste des personnes extérieures rattachées à une classe et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/15
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getPersonnelExtInClasse ($classe) {
		$query = "SELECT E.id, E.nom, E.prenom, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_ext_user E, kernel_bu_ecole_classe EC, kernel_link_bu2user LI, kernel_link_user2node NO, dbuser U WHERE LI.user_id=U.id_dbuser AND NO.user_type=LI.bu_type AND NO.user_id=LI.bu_id AND NO.node_type='BU_CLASSE' AND NO.node_id=EC.id AND LI.bu_type='USER_EXT' AND LI.bu_id=E.id AND EC.id=".$classe." ORDER BY nom, prenom";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste des personnes extérieures rattachées à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/15
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getPersonnelExtInEcole ($ecole) {
		$query = "SELECT E.id, E.nom, E.prenom, EC.nom AS nom_ecole, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_ext_user E, kernel_bu_ecole EC, kernel_link_bu2user LI, kernel_link_user2node NO, dbuser U WHERE LI.user_id=U.id_dbuser AND NO.user_type=LI.bu_type AND NO.user_id=LI.bu_id AND NO.node_type='BU_ECOLE' AND NO.node_id=EC.numero AND LI.bu_type='USER_EXT' AND LI.bu_id=E.id AND EC.numero=".$ecole." ORDER BY nom, prenom";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste des personnes extérieures rattachées à une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/15
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelExtInVille ($ville) {
		$query = "SELECT E.id, E.nom, E.prenom, VI.nom AS nom_ville, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_ext_user E, kernel_bu_ville VI, kernel_link_bu2user LI, kernel_link_user2node NO, dbuser U WHERE LI.user_id=U.id_dbuser AND NO.user_type=LI.bu_type AND NO.user_id=LI.bu_id AND NO.node_type='BU_VILLE' AND NO.node_id=VI.id_vi AND LI.bu_type='USER_EXT' AND LI.bu_id=E.id AND VI.id_vi=".$ville." ORDER BY nom, prenom";
		//print_r($query);
		return _doQuery($query);
	}
	

	/**
	 * Renvoie la liste des personnes extérieures rattachées à un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/15
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelExtInGrville ($grville) {
		$query = "SELECT E.id, E.nom, E.prenom, GR.nom_groupe AS nom_grville, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_ext_user E, kernel_bu_groupe_villes GR, kernel_link_bu2user LI, kernel_link_user2node NO, dbuser U WHERE LI.user_id=U.id_dbuser AND NO.user_type=LI.bu_type AND NO.user_id=LI.bu_id AND NO.node_type='BU_GRVILLE' AND NO.node_id=GR.id_grv AND LI.bu_type='USER_EXT' AND LI.bu_id=E.id AND GR.id_grv=".$grville." ORDER BY nom, prenom";
		//print_r($query);
		return _doQuery($query);
	}	

}




?>
