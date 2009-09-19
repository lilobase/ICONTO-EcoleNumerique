<?php
/**
 * Fonctions relatives au kernel et au module VisioScopia
 * 
 * @package Iconito
 * @subpackage	VisioScopia
 */

class KernelVisioScopia {


	/**
	 * Création d'une malle
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/06
	 * @param array $infos (option) informations permettant d'initialiser la malle. Index: title, node_type, node_id
	 * @return integer l'Id de la malle créée ou NULL si erreur
	 */
	function create ($infos=array()) {
		
		
	}

	/**
	 * Suppression d'une malle
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/09
	 * @param integer $id Id de la malle
	 * @return boolean true si la suppression s'est bien passée, false sinon
	 */
	function delete ($id) {

		
	}

	/**
	 * Statistiques d'une malle
	 *
	 * Renvoie des éléments chiffrés relatifs à une malle : taille occupée (format "humain"), nombre de dossiers, nombre de fichiers
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param integer $malle Id de la malle
	 * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbFiles"] ["nbFolders"] ["size"]
	 */
	function getStats ($malle) {
		
		
	}
	
	/**
	 * Statistiques du module documents
	 *
	 * Renvoie des éléments chiffrés relatifs aux documents et dédiés à un utilisateur système : taille occupée (format "humain"), nombre de zones de documents, nombre de dossiers, nombre de fichiers
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbMalles"] ["nbFolders"] ["nbFiles"] ["size"]
	 */
	function getStatsRoot () {
		
		
	}

	
}

?>
