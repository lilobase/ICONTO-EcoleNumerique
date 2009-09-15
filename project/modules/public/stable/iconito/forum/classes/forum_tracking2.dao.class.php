<?php

/**
 * Surcharge de la DAO forum_tracking2
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class DAOForum_Tracking2 {
	
	/**
	 * Renvoie le premier message non lu d'une discussion par rapport à la dernière date de lecture par un utilisateur de cette discussion.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/09
	 * @param integer $topic Id de la discussion
	 * @param integer $user Id de l'utilisateur concerné
	 * @return mixed Objet DAO ou NULL si l'utilisateur n'a jamais lu la discussion
	 */
	function getFirstUnreadMessage ($topic, $user) {
		$dbw = & CopixDbFactory::getDbWidget ();
		//    $sql = "SELECT COUNT(FM.id_message) AS nb, MIN(FM.id_message) AS id_message, FT.titre, FT.lastMsgAuteur, FC.libelle, FC.id_categ FROM forum_messages FM, forum_threads FT, forum_categ FC WHERE FM.id_thread=FT.id AND FT.categ=FC.id_categ AND FT.status<>3 AND FM.status=1 AND FM.date>'".$date."' GROUP BY FM.id_thread ORDER BY FC.ordre, FT.lastMsgDate DESC";
    $sql = "SELECT MIN(FM.id) AS id FROM (module_forum_messages FM, module_forum_topics FT) LEFT JOIN module_forum_tracking TRA ON (TRA.topic=$topic AND TRA.utilisateur=$user) WHERE FM.topic=FT.id AND FM.status=1 AND FM.date>TRA.last_visite ORDER BY 1";
		return $dbw->fetchAll ($sql);
	}

}

?>