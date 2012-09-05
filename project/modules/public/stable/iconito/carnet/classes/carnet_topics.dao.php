<?php


/**
 * Surcharge de la DAO carnet_topics
 *
 * @package Iconito
 * @subpackage Carnet
 */
class DAOCarnet_Topics
{
    /**
     * Renvoie la liste des topics dans un carnet, pour une liste d'élèves
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param array $eleves Tableau avec les ID des élèves. Attention c'est un tableau évolué, avec différentes infos sur les élèves. Exemple : 		Array (
        [0] => Array
        (
            [type] => USER_ELE
            [id] => 3776
            [prenom] => Naël
            [nom] => SIRIEIX
        )

        [1] => Array
        (
            [type] => USER_ELE
            [id] => 3777
            [prenom] => Doryan
            [nom] => SOUMAGNAS
        )
            )
     * @param integer $classe Id de la classe
     * @return mixed Objet DAO
     */
    public function getListCarnetsTopicsForElevesInClasse ($eleves, $classe, $user)
    {
        $idEleves = array(); foreach ($eleves as $item) $idEleves[] = $item["id"];
        if (count($idEleves)>0) {
            $critere = 'SELECT DISTINCT(TOP.id), TOP.*, MAX(MSG.id) AS last_msg_id, MAX(MSG.date) AS last_msg_date, TRA.last_visite FROM (module_carnet_topics TOP, module_carnet_topics_to DEST) LEFT JOIN module_carnet_messages MSG ON (MSG.topic=DEST.topic AND MSG.eleve=DEST.eleve) LEFT JOIN module_carnet_tracking TRA ON (TRA.topic=TOP.id AND TRA.utilisateur='.$user.') WHERE DEST.topic=TOP.id AND DEST.eleve IN ('.implode(", ",$idEleves).') AND TOP.classe='.$classe.' GROUP BY TOP.id ORDER BY last_msg_date DESC, TOP.date_creation DESC';
            $arTopics = _doQuery($critere);
            //print_r($arTopics);
            usort ($arTopics, array('DAOCarnet_Topics', 'usortListTopics'));
            return $arTopics;
        } else {
            return array();
        }
    }

    public function usortListTopics ($a, $b)
    {
        $a->nonLu = (!$a->last_visite || $a->last_visite<$a->last_msg_date);
        $b->nonLu = (!$b->last_visite || $b->last_visite<$b->last_msg_date);

    if ($a->nonLu == $b->nonLu) {
            if ($a->last_msg_date == $b->last_msg_date)
          return ($a->date_creation>$b->date_creation) ? -1 : 1;
            else
                return ($a->last_msg_date>$b->last_msg_date) ? -1 : 1;
    }
    return ($a->nonLu > $b->nonLu) ? -1 : 1;
    }


    /**
     * Les messages d'une discussion, pour une liste d'élèves
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/17
     * @see getListCarnetsTopicsForElevesInClasse()
     * @param integer $id_topic Id de la discussion
     * @param array $eleves Tableau avec les ID des élèves. Attention c'est un tableau évolué, avec différentes infos sur les élèves. Voir ci-dessus pour exemple.
     * @return mixed Objet DAO
     */
    public function getListCarnetsMessagesForTopicAndEleves ($id_topic, $eleves)
    {
        $idEleves = array(); foreach ($eleves as $item) $idEleves[] = $item["id"];
        $critere = 'SELECT MSG.* FROM module_carnet_messages MSG WHERE MSG.topic='.$id_topic.' AND MSG.eleve IN ('.implode(", ",$idEleves).') ORDER BY MSG.date ASC, MSG.id ASC';
        //print_r2($critere);
        return _doQuery($critere);
    }

    /**
     * Les messages d'une discussion, pour un élève précis
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/17
     * @param integer $id_topic Id de la discussion
     * @param integer $eleve Id de l'élève
     * @return mixed Objet DAO
     */
    public function getListCarnetsMessagesForTopicAndEleve ($id_topic, $eleve)
    {
        $critere = 'SELECT MSG.* FROM module_carnet_messages MSG WHERE MSG.topic='.$id_topic.' AND MSG.eleve = '.$eleve.' ORDER BY MSG.date ASC, MSG.id ASC';
        return _doQuery($critere);
    }

    /**
     * Les destinataire (=élèves distincts) d'une discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id_topic Id de la discussion
     * @return mixed Objet DAO
     */
    public function getElevesForTopic ($id_topic)
    {
        $critere = 'SELECT (DEST.eleve) FROM module_carnet_topics_to DEST WHERE DEST.topic='.$id_topic.'';
        return _doQuery($critere);
    }



}




