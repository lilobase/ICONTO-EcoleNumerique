<?php


/**
 * Zone PetitPoucet, qui affiche le chemin depuis la racine jusqu'à une discussion ou un forum
 *
 * @package Iconito
 * @subpackage	Forum
 */
class ZonePetitPoucet extends CopixZone
{
    /**
     * Affiche le chemin d'accès à une discussion ou un forum, depuis la racine d'un forum
     *
     * Les paramètres dépendent de la navigation dans le forum (il suffit de passer un paramètre)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/08
     * @param integer $forum Id du forum
     * @param integer $topic Id de la discussion
     * @param integer $message Id du message
     * @param integer $modifyTopic Id de la discussion (formulaire de modification)
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $forum = ($this->getParam('forum')) ? $this->getParam('forum') : NULL;
        $topic = ($this->getParam('topic')) ? $this->getParam('topic') : NULL;
        $message = ($this->getParam('message')) ? $this->getParam('message') : NULL;

        $modifyTopic = ($this->getParam('modifyTopic')) ? $this->getParam('modifyTopic') : NULL;


        $res = array();

        if ($forum) {
            $res[] = array("libelle"=>CopixI18N::get ('forum|forum.poucetIndex'), "lien"=>CopixUrl::get ('forum||getForum', array("id"=>$forum->id)));
        }	elseif ($topic) {
            $res[] = array("libelle"=>CopixI18N::get ('forum|forum.poucetIndex'), "lien"=>CopixUrl::get ('forum||getForum', array("id"=>$topic->forum)));
            $res[] = array("libelle"=>$topic->titre, "lien"=>CopixUrl::get ('forum||getTopic', array("id"=>$topic->id)));
        }	elseif ($message) {
            $res[] = array("libelle"=>CopixI18N::get ('forum|forum.poucetIndex'), "lien"=>CopixUrl::get ('forum||getForum', array("id"=>$message->forum)));
            $res[] = array("libelle"=>$message->topic_titre, "lien"=>CopixUrl::get ('forum||getTopic', array("id"=>$message->topic)));
        } elseif ($modifyTopic) {
            //print_r($modifyTopic);
            $res[] = array("libelle"=>CopixI18N::get ('forum|forum.poucetIndex'), "lien"=>CopixUrl::get ('forum||getForum', array("id"=>$modifyTopic->forum)));
            $res[] = array("libelle"=>$modifyTopic->titre, "lien"=>CopixUrl::get ('forum||getTopic', array("id"=>$modifyTopic->id)));
        }

        $tpl->assign('petitpoucet', $res);

        // retour de la fonction :
    $toReturn = $tpl->fetch ('petitpoucet.tpl');

    return true;

    }



}
