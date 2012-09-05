<?php

/**
 * Surcharge de la DAO forum_tracking2
 *
 * @package Iconito
 * @subpackage	Forum
 */
class DAOForum_Tracking2
{
    /**
     * Renvoie le premier message non lu d'une discussion par rapport à la dernière date de lecture par un utilisateur de cette discussion.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/09
     * @param integer $topic Id de la discussion
     * @param integer $user Id de l'utilisateur concerné
     * @return mixed Objet DAO ou NULL si l'utilisateur n'a jamais lu la discussion
     */
    public function getFirstUnreadMessage ($topic, $user)
    {
    $sql = "SELECT MIN(FM.id) AS id FROM (module_forum_messages FM, module_forum_topics FT) LEFT JOIN module_forum_tracking TRA ON (TRA.topic=$topic AND TRA.utilisateur=$user) WHERE FM.topic=FT.id AND FM.status=1 AND FM.date>TRA.last_visite ORDER BY 1";
        return _doQuery($sql);
    }

}

