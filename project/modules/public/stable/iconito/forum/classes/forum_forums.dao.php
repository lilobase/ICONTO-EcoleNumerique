<?php

/**
 * Surcharge de la DAO forum_forums
 * 
 * @package Iconito
 * @subpackage	Forum
 */
class DAOForum_Forums {
	
	/**
	 * Renvoie le nb de topics (valides) d'un forum
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param integer $forum Id du forum
	 * @return mixed Objet DAO
	 */
	function getNbTopicsInForum ($forum) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$critere = 'SELECT COUNT(TOP.id) AS nb FROM module_forum_topics TOP WHERE TOP.status=1 AND TOP.forum='.$forum.'';
		return $dbw->fetchAll ($critere);
	}

	/**
	 * Renvoie nb de messages (valides) dans un forum
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/11/08
	 * @param integer $forum Id du forum
	 * @return mixed Objet DAO
	 */
	function getNbMessagesInForum ($forum) {
		$dbw = & CopixDbFactory::getDbWidget ();
		$critere = 'SELECT COUNT(MSG.id) AS nb FROM module_forum_messages MSG, module_forum_topics TOP WHERE MSG.topic=TOP.id AND TOP.status=1 AND MSG.status=1 AND TOP.forum='.$forum.'';
		return $dbw->fetchAll ($critere);
	}

}




?>