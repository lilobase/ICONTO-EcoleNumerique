<?php

/**
 * Fonctions diverses du module Liste
 *
 * @package Iconito
 * @subpackage Liste
 */
class ListeService
{
    /**
     * Ajoute un message à une liste, et s'occupe de l'envoyer à tous ses membres en minimail
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/23
     * @param integer $liste Id de la liste
     * @param integer $auteur Id de l'utilisateur auteur du message
     * @param string $titre Titre du message
     * @param string $message Corps du message
     * @return integer l'Id du message inséré ou NULL si erreur
     */
    public function addListeMessage ($liste, $auteur, $titre, $message)
    {
        $daoListes = _dao("liste|liste_listes");
        $daoMessages = _dao("liste|liste_messages");
        $kernelService = & CopixClassesFactory::Create ('kernel|kernel');
        $minimailService = & CopixClassesFactory::Create ('minimail|minimailService');

        $res = NULL;

        $rListe = $daoListes->get($liste);
        if ($rListe) {

            $parent = $kernelService->getModParentInfo( "MOD_LISTE", $liste);
            //print_r($parent);
            $rListe->parent = $parent;
            //die();

            $newMessage = _record("liste|liste_messages");
            $newMessage->liste = $liste;
            $newMessage->auteur = $auteur;
            $newMessage->titre = $titre;
            $newMessage->message = $message;
            $newMessage->date = date("Y-m-d H:i:s");
            $daoMessages->insert ($newMessage);
            if ($newMessage->id!==NULL) {
                $dest = array();
                // On cherche le parent
                $parent = $kernelService->getModParentInfo( "MOD_LISTE", $liste);
                // Puis les membres du parent
                $childs = $kernelService->getNodeChilds ($parent["type"], $parent["id"]);
                // On parcourt les membres
                foreach ($childs as $child) {
                    $userInfo = $kernelService->getUserInfo ($child["type"], $child["id"]);
                    if ($userInfo && $userInfo["user_id"] && $userInfo["user_id"]!=$auteur) {
                        $dest[$userInfo["user_id"]] = 1;
                    }
                }
                if ($dest) {

          $format = CopixConfig::get ('minimail|default_format');

          if ($format == 'dokuwiki' || $format == 'wiki')
                      $message .= "\n\n----\n".CopixI18N::get ('liste|liste.message.footer', array(1=>$rListe->parent["nom"], 2=>CopixUrl::get($rListe->parent["module"].'||getHomeAdmin', array("id"=>$rListe->parent["id"]))));
          else
                      $message .= "<p>-- </p><p>".CopixI18N::get ('liste|liste.message.footerHtml', array(1=>$rListe->parent["nom"], 2=>CopixUrl::get($rListe->parent["module"].'||getHomeAdmin', array("id"=>$rListe->parent["id"]))))."</p>";

                    $send = $minimailService->sendMinimail ($titre, $message, $auteur, $dest, CopixConfig::get ('minimail|default_format'));
                }
                $res = $newMessage->id;
            }
        }
        return $res;
    }


    /**
     * Gestion des droits dans une liste
     *
     * Teste si l'usager peut effectuer une certaine opération par rapport à son droit. Le droit sur la liste nécessite d'être connu, renvoyé par le kernel avant l'entrée dans cette fonction.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/30
     * @param string action Action pour laquelle on veut tester le droit
     * @param integer droit Le droit de l'usager
     * @return bool true s'il a le droit d'effectuer l'action, false sinon
     */
    public function canMakeInListe ($action, $droit)
    {
        $can = false;
        switch ($action) {
            case "VIEW_HOME" :	// Accueil du module
                $can = ($droit >= PROFILE_CCV_READ);
                break;
            case "WRITE" :	// Ecrire un message
                $can = ($droit >= PROFILE_CCV_MEMBER);
                break;
        }
        return $can;
    }

}


