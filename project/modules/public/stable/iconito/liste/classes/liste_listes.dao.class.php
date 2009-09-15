<?php

/**
 * Surcharge de la DAO liste_listes
 * 
 * @package Iconito
 * @subpackage Liste
 */
class DAOListe_Listes {
	
	/**
	 * Renvoie nb de messages envoyés sur une liste
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/23
	 * @param integer $id_liste Id de la liste
	 * @return mixed Objet DAO
	 */
	function getNbMessagesInListe ($id_liste) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$critere = 'SELECT COUNT(MSG.id) AS nb FROM module_liste_messages MSG WHERE MSG.liste='.$id_liste.'';
		return $dbw->fetchAll ($critere);
	}


}




?>