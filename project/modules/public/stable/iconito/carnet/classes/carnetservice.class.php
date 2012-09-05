<?php

/**
 * Fonctions diverses du module Carnet
 *
 * @package Iconito
 * @subpackage	Carnet
 */
class CarnetService
{
    /**
     * Renvoie le droit de l'usager courant sur un carnet de correspondance
     *
     * Test du droit de l'usager courant sur un carnet, selon qu'on affiche un carnet d'une classe ou d'un élève.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param array $params Tableau avec un numéro de classe et/ou d'élève. Cles=eleve/classe valeurs=x/y (ids)
     * @return integer Le droit: 0 si aucun, PROFILE_CCV_ADMIN s'il peut lire/écrire
     */
    public function getUserDroitInCarnet ($params)
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $res = 0;

        if ($params["eleve"] && $params["eleve"]!='CLASSE') {
            switch (_currentUser()->getExtra('type')) {

                case "USER_ENS" :

                    // On vérifie que l'enseignant a des droits sur la classe de l'élève
                    $parentEns = $kernel_service->getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
                    //print_r($parentEns);
                    $parentEle = $kernel_service->getNodeParents( "USER_ELE", $params["eleve"] );
                    //print_r($parentEns);
                    //print_r($parentEle);


                    //while (!$res && list(,$v) = each($parentEns)) {
                    foreach ($parentEns as $v) {
                        if ($res)
                            break;
                        if ($v["type"] != "BU_CLASSE") continue;
                        //print_r($v);
                        reset ($parentEle);
                        //while (!$res && list(,$w) = each($parentEle)) {
                        foreach ($parentEle as $w) {
                            if ($res)
                                break;
                            if ($w["type"] != "BU_CLASSE") continue;
                            //print_r("---");
                            //print_r($w);
                            //Kernel::deb("v[id]=".$v["id"]." / w[id]=".$w["id"]);
                            if ($v["id"]==$w["id"]) {
                                //var_dump($v);
                                //var_dump($w);
                                $res = ($v["droit"]>$w["droit"]) ? $v["droit"] : $w["droit"];
                            }
                        }
                    }
                    break;
                case "USER_RES" :		// Parents
                    $parent = $kernel_service->getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
                    //while (!$res && list(,$v) = each($parent)) {
                    foreach ($parent as $v) {
                        if ($res)
                            break;
                        if ($v["type"] != "USER_ELE") continue;
                        if ($v["id"]==$params["eleve"])
                            $res = ($v["droit"]);
                    }
                    break;
            }
        } elseif ($params["classe"] || (!$params["classe"] && $params["eleve"]=='CLASSE')) {
            switch (_currentUser()->getExtra('type')) {
                case "USER_ENS" :
                    $mondroit = $kernel_service->getLevel ("BU_CLASSE", $params["classe"]);
                    if ($mondroit) $res = PROFILE_CCV_ADMIN;
                    break;
            }
        }
        //print_r($res);
        return $res;
    }

    /**
     * Gestion des droits dans un carnet
     *
     * Teste si l'usager peut effectuer une certaine opération par rapport à son droit. Le droit sur le cahier nécessite d'être connu, renvoyé par getUserDroitInCarnet avant l'entrée dans cette fonction. Le droit peut être zappé
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/25
     * @param string action Action pour laquelle on veut tester le droit
     * @param integer droit Le droit de l'usager
     * @return bool true s'il a le droit d'effectuer l'action, false sinon
     */
    public function canMakeInCarnet ($action, $droit)
    {
        $can = false;
        switch ($action) {
            case "WRITE_CLASSE" : // Ecrire à toute la classe courante (en plus de sa/ses élève(s))
            case "PRINT_TOPIC" : // Imprimer une correspondance
                $can = (_currentUser()->getExtra('type') == "USER_ENS");
                break;
        }
        return $can;
    }

    /**
     * Renvoie la liste des élèves d'une classe pour lesquels l'usager courant pour voir les carnets de correspondance et/ou démarrer une discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $classe Id de la classe
     * @return array Tableau avec des infos sur les élèves
     */
    public function getUserElevesInClasse ($classe)
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $res = array();

        switch (_currentUser()->getExtra('type')) {
            case "USER_ENS" :	// Enseignant
                $childs = $kernel_service->getNodeChilds ("BU_CLASSE", $classe);
                //var_dump($childs);
                foreach ($childs as $k=>$child) {
                //while (list($k,$child) = each($childs)) {
                    if ($child["type"] != "USER_ELE") continue;
                    $userInfo = $kernel_service->getUserInfo ($child["type"], $child["id"]);
                    $childs[$k]["prenom"] = $userInfo["prenom"];
                    $childs[$k]["nom"] = $userInfo["nom"];
                    $res[] = $childs[$k];
                }
                break;

            case "USER_RES" : // Parent, OK seulement son enfant de cette classe
                $parent = $kernel_service->getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
                while (!$res && list($k,$v) = each($parent)) {
                    if ($v["type"] != "USER_ELE") continue;
                    // Pour chaque enfant, on regarde s'il est dans cette classe
                    //print_r2($v);
                    $parentEle = $kernel_service->getNodeParents( $v["type"], $v["id"] );
                    //print_r2($parentEle);
                    while (!$res && list(,$w) = each($parentEle)) {
                        if ($w["type"] != "BU_CLASSE") continue;
                        if ($w["id"]==$classe) {	// Un enfant trouvé dans la bonne classe
                            $userInfo = $kernel_service->getUserInfo ($v["type"], $v["id"]);
                            $parent[$k]["prenom"] = $userInfo["prenom"];
                            $parent[$k]["nom"] = $userInfo["nom"];
                            $res[] = $parent[$k];
                            //print_r2($res);
                        }
                    }
                }
                break;
        }
        return $res;
    }


    /**
     * Ajoute une discussion (avec le premier message) dans un ou plusieurs carnets de correspondance
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $classe Id de la classe
     * @param integer $createur Id utilisateur du créateur de la discussion
     * @param string $titre Titre de la discussion
     * @param string $message Corps du premier message
     * @param array $eleves Id des élèves concernés
     * @param string $format Format de la discussion
     * @return integer Id du topic crée ou NULL si erreur
     */
    public function addCarnetTopic ($classe, $createur, $titre, $message, $eleves, $format)
    {
        $res = NULL;

        $daoTopics = _dao("carnet|carnet_topics");
        $kernelClasse = CopixClassesFactory::create("kernel|Kernel");

        $newTopic = _record("carnet|carnet_topics");
        $newTopic->titre = $titre;
        $newTopic->message = $message;
        $newTopic->format = $format;
        $newTopic->classe = $classe;
        $newTopic->createur = $createur;
        $newTopic->date_creation = date("Y-m-d H:i:s");
        $daoTopics->insert ($newTopic);

        if ($newTopic->id!==NULL) {

            $daoTopicsTo = _dao("carnet|carnet_topics_to");

            /*
            if (!$eleve) {		// Tous les élèves de la classe !
                $eleves = $kernelClasse->getNodeChilds ("BU_CLASSE", $classe);
                while (list(,$v) = each($eleves)) {
                    if ($v["type"]=="USER_ELE") { 	// Todo prévoir fonction qui ne renvoie que les élèves pour zapper ce test
                        $newTopicTo = _record("carnet|carnet_topics_to");
                        $newTopicTo->topic = $newTopic->id;
                        $newTopicTo->eleve = $v["id"];
                        $daoTopicsTo->insert ($newTopicTo);
                    }
                }
            } else {	// Chez un élève précis
                $daoTopicsTo = _dao("carnet|carnet_topics_to");
                $newTopicTo = _record("carnet|carnet_topics_to");
                $newTopicTo->topic = $newTopic->id;
                $newTopicTo->eleve = $eleve;
                $daoTopicsTo->insert ($newTopicTo);
            }
            */

            foreach ($eleves as $eleve) {
                $daoTopicsTo = _dao("carnet|carnet_topics_to");
                $newTopicTo = _record("carnet|carnet_topics_to");
                $newTopicTo->topic = $newTopic->id;
                $newTopicTo->eleve = $eleve;
                $daoTopicsTo->insert($newTopicTo);
            }

            $res = $newTopic->id;
        }

        return $res;
    }


    /**
     * Ajoute un message dans un carnet de correspondance
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $topic Id de la discussion
     * @param integer $eleve Id de l'élève
     * @param integer auteur Id utilisateur de l'auteur du message
     * @param string $message Corps du message
     * @param string $format Format du message
     * @return integer Id du message inséré ou NULL si erreur
     */
    public function addCarnetMessage ($topic, $eleve, $auteur, $message, $format)
    {
        $res = NULL;

        $daoMessages = _dao("carnet_messages");

        $newMessage = _record("carnet_messages");
        $newMessage->topic = $topic;
        $newMessage->eleve = $eleve;
        $newMessage->auteur = $auteur;
        $newMessage->message = $message;
        $newMessage->format = $format;
        $newMessage->date = date("Y-m-d H:i:s");
        $daoMessages->insert ($newMessage);

        if ($newMessage->id!==NULL) {
            $res = $newMessage->id;
        }
        return $res;
    }


    /**
     * Enregistre la date de passage d'un utilisateur dans une discussion
     *
     * Cette fonction de "tracking" permet ensuite d'afficher, pour un utilisateur, les discussions dans lesquelles de nouveaux messages ont été écrits depuis sa dernière lecture, et de le rediriger vers le premier message non lu.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/09
     * @param integer $id_topic Id de la discussion
     * @param integer $user Id de l'utilisateur
     * @param array $eleves Tableau avec les ids des élèves (en valeurs)
     */
    public function userReadTopic ($id_topic, $user, $eleves)
    {
        $daoTracking = _dao("carnet|carnet_tracking3");

        foreach ($eleves as $eleve) {
            $visite = $daoTracking->get($id_topic, $user, $eleve);
            if ($visite) {	// Il a déjà visité ce topic
                $visite->last_visite = date("Y-m-d H:i:s");
                $daoTracking->update($visite);
            } else {	// 1e visite !
                $newVisite = _record("carnet|carnet_tracking3");
                $newVisite->topic = $id_topic;
                $newVisite->utilisateur = $user;
                $newVisite->eleve = $eleve;
                $newVisite->last_visite = date("Y-m-d H:i:s");
                $daoTracking->insert ($newVisite);
            }
        }
    }


    /**
     * Renvoie le nb d'élèves d'une classe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/05/18
     * @param integer $classe Id de la classe
     * @return integer Nb d'léèves
     */
    public function getNbElevesInClasse ($classe)
    {
        $childs = Kernel::getNodeChilds ("BU_CLASSE", $classe);
        $res = 0;
        while (list($k,$child) = each($childs)) {
            if ($child["type"] != "USER_ELE") continue;
            $res++;
        }
        return $res;
    }









}


