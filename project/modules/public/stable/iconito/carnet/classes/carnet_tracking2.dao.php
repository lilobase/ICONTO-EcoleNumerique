<?php

/**
 * Surcharge de la DAO carnet_tracking2
 *
 * @package Iconito
 * @subpackage	Carnet
 */
class DAOCarnet_Tracking2
{
    /**
     * Renvoie le premier message non lu d'une discussion par rapport à la dernière date de lecture par un utilisateur de cette discussion.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/09
     * @param integer $topic Id de la discussion
     * @param integer $user Id de l'utilisateur concerné
     * @param array $eleves Tableau avec les ids des élèves (en valeurs)
     * @return mixed Objet DAO ou NULL si l'utilisateur n'a jamais lu la discussion
     */
    public function getFirstUnreadMessage ($topic, $user, $eleves)
    {
        //print_r($eleves);
    $sql = "SELECT MIN(FM.id) AS id FROM (module_carnet_messages FM, module_carnet_topics FT) LEFT JOIN module_carnet_tracking TRA ON (TRA.topic=$topic AND TRA.utilisateur=$user AND TRA.eleve IN (".implode(", ",$eleves).")) WHERE FM.topic=FT.id AND FM.date>TRA.last_visite ORDER BY 1";
        //print_r($sql);
        return _doQuery($sql);
    }

}

