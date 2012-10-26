<?php
/**
 * Actiongroup du module Forum
 *
 * @package Iconito
 * @subpackage	Forum
 */
class ActionGroupForum extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }


   /**
   * Affiche la liste de tous les forums de la base
     *
     * Affiche l'ensemble des forums presents dans la base, avec  un lien pour acceder a chacun d'entre eux. Utilisee a des fins de tests, est desactivee dans le DESC.
     *
     * @deprecated Ne sert qu'a des fins de tests
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/08
   */
   public function getListForums ()
   {
         $dao = CopixDAOFactory::create("forum_forums");
        $forums = $dao->getList();

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('forum.title.lesforums'));

        $tplListe = new CopixTpl ();
        $tplListe->assign ("list", $forums);
        $result = $tplListe->fetch("getlistforums.tpl");

        $tpl->assign ("MAIN", $result);


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }


   /**
   * Affichage d'un forum (ses discussions)
     *
     * Affiche les discussions d'un forum et les informations sur les discussions (titre, dernier message...), avec un lien pour lire chaque discussion.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/08
     * @param integer $id Id du forum
     * @param string $orderby Ordre d'affichage (last_msg_date ou date_creation)
   */
   public function getForum ()
   {
        $forumService = & CopixClassesFactory::Create ('forum|forumService');

        $id = _request("id") ? _request("id") : NULL;
        $orderby = _request("orderby") ? _request("orderby") : NULL;
        if ($orderby != "last_msg_date" && $orderby != "date_creation") $orderby = "last_msg_date";
        $errors = array();

         $dao_forums = CopixDAOFactory::create("forum|forum_forums");
         $dao_topics = CopixDAOFactory::create("forum|forum_topics");

        $forum = $dao_forums->getForum($id);

        if (!count($forum))
            $errors[] = CopixI18N::get ('forum|forum.error.noForum');
        else {
            $mondroit = Kernel::getLevel( "MOD_FORUM", $id );
            if (!$forumService->canMakeInForum("READ",$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                $parent = Kernel::getModParentInfo( "MOD_FORUM", $id);
                $forum[0]->parent = $parent;
            }
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('forum||')));
        } else {

            // Les topics de ce forum
            $page = _request("page") ? _request("page") : 1;
            $offset = ($page-1)*CopixConfig::get ('forum|list_nbtopics');
            $all = $dao_topics->getListTopicsInForumAll($id);
            $nbPages = ceil(count($all) / CopixConfig::get ('forum|list_nbtopics'));

            $user = _currentUser ()->getId();
            $list = $dao_topics->getListTopicsInForum($id,$offset,CopixConfig::get ('forum|list_nbtopics'),$orderby, $user);

            // Pour chaque message on cherche les infos de son createur et du dernier message
            //print_r($list);
      foreach ($list as $k=>$topic) {
                $userInfo = Kernel::getUserInfo("ID", $list[$k]->createur);
                $list[$k]->createur_infos = $userInfo["prenom"]." ".$userInfo["nom"];
                if ($list[$k]->last_msg_auteur) {
                    $userInfo = Kernel::getUserInfo("ID", $list[$k]->last_msg_auteur);
                    $list[$k]->last_msg_auteur_infos = $userInfo["prenom"]." ".$userInfo["nom"];
                }
                //print_r($infos);
            }

            //print_r($list);

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $forum[0]->parent["nom"]);

            $tplForum = new CopixTpl ();
            $tplForum->assign ('forum', $forum[0]);
            $tplForum->assign ('list', $list);
            $tplForum->assign ('page', $page);

            $tplForum->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('forum'=>$forum[0])));

            $tplForum->assign ('canAddTopic', ($forumService->canMakeInForum('ADD_TOPIC',$mondroit)) ? 1 : 0);
            $tplForum->assign ('reglettepages', CopixZone::process ('kernel|reglettepages', array('page'=>$page, 'nbPages'=>$nbPages, 'separator' => '&', 'url'=>CopixUrl::get('forum||getForum', array("id"=>$id, "orderby"=>$orderby)))));

            $result = $tplForum->fetch('getforum.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }

   /**
   * Affichage d'une discussion (ses messages)
     *
     * Affiche les messages d'une discussion.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/09
     * @param integer $id Id de la discussion
     * @param integer $page (option) Page courante. Si null, vaut 1.
     * @param string $go (option) Si vaut "new", redirige sur le premier message non lu de la discussion
     * @param integer $message (option) Si positionne, redirige sur le message (a la bonne page)
   */
   public function getTopic ()
   {
        $id = _request("id") ? _request("id") : NULL;
        $go = _request("go") ? _request("go") : NULL;
        $message = _request("message") ? _request("message") : NULL;
        $page = _request("page") ? _request("page") : 1;
        $errors = array();

        $countClick = true;	// Todo voir si en session pour pas compter la lecture de chaque page ?

         $dao_topics = CopixDAOFactory::create("forum_topics");
         $dao_messages = CopixDAOFactory::create("forum_messages_topics");
        $forumService = CopixClassesFactory::create("forum|forumService");

        if ($go == "new") {

            $daoTracking = CopixDAOFactory::create("forum|forum_tracking2");
            $unread = $daoTracking->getFirstUnreadMessage($id, _currentUser ()->getId());
            //print_r($unread);
            if ($unread[0]->id) {	// Il est deja passe dans le topic
                $urlReturn = CopixUrl::get ('forum||getTopic', array("message"=>$unread[0]->id))."#".$unread[0]->id;
            } else { // Jamais passe, on le renvoie au debut du topic
                $urlReturn = CopixUrl::get ('forum||getTopic', array("id"=>$id));
            }
            return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
        }


        if ($message) {
            $rMessage = $dao_messages->get($message);
            if ($rMessage) {
                $id = $rMessage->topic_id;
              // On cherche ensuite a quelle page il faut aller pour trouver ce message
                $before = $dao_messages->getListMessagesInTopicBefore($id, $message, $rMessage->date);
                $page = ceil((count($before)+1) / CopixConfig::get ('forum|list_nbmessages'));
                //print_r($before);
            }
        }


        $rTopic = $dao_topics->get($id);
        if (!$rTopic)
            $errors[] = CopixI18N::get ('forum|forum.error.noTopic');
        else {
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
            if (!$forumService->canMakeInForum("READ",$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('forum||')));
        } else {

            if ($countClick) {
                $rTopic->nb_lectures = $rTopic->nb_lectures+1;
                $dao_topics->update($rTopic);
            }

            // On enregistre sa lecture (tracking)
            $user = _currentUser ()->getId();
            $forumService->userReadTopic ($id, $user);

            // Les messages de ce forum
            $offset = ($page-1)*CopixConfig::get ('forum|list_nbmessages');
            $all = $dao_messages->getListMessagesInTopicAll($id);
            $nbPages = ceil(count($all) / CopixConfig::get ('forum|list_nbmessages'));

            $list = $dao_messages->getListMessagesInTopic($id,$offset,CopixConfig::get ('forum|list_nbmessages'));

            // Pour chaque message on cherche les infos de son auteur

            foreach ($list as $k=>$null) {
                $userInfo = Kernel::getUserInfo("ID", $list[$k]->auteur);
                $list[$k]->auteur_infos = $userInfo["prenom"]." ".$userInfo["nom"];

        // Avatar de l'expediteur
              $avatar = Prefs::get('prefs', 'avatar', $list[$k]->auteur);
              $list[$k]->avatar = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
             }

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $rTopic->titre);

      $menu = array();
      if ($forumService->canMakeInForum('MODIFY_TOPIC',$mondroit))
        $menu[] = array('txt' => CopixI18N::get('forum|forum.btn.modify'), 'url' => CopixUrl::get('forum||getTopicForm', array("id"=>$id)), 'type'=>'update');
      if ($forumService->canMakeInForum('DELETE_TOPIC',$mondroit))
        $menu[] = array('txt' => CopixI18N::get('forum|forum.btn.delete'), 'url' => CopixUrl::get('forum||getDeleteTopic', array("id"=>$id)), 'type'=>'delete');
            $tpl->assign ('MENU', $menu);

            $tplForum = new CopixTpl ();
            $tplForum->assign ('topic', $rTopic);
            $tplForum->assign ('list', $list);
            $tplForum->assign ('reglettepages', CopixZone::process ('kernel|reglettepages', array('page'=>$page, 'nbPages'=>$nbPages, 'separator' => '&', 'url'=>CopixUrl::get('forum||getTopic', array("id"=>$id)))));
            $tplForum->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('topic'=>$rTopic)));

            $tplForum->assign ('canModifyMessage', ($forumService->canMakeInForum('MODIFY_MESSAGE',$mondroit)) ? 1 : 0);
            $tplForum->assign ('canDeleteMessage', ($forumService->canMakeInForum('DELETE_MESSAGE',$mondroit)) ? 1 : 0);
            $tplForum->assign ('canAddMessage', ($forumService->canMakeInForum('ADD_MESSAGE',$mondroit)) ? 1 : 0);

            $result = $tplForum->fetch('gettopic.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Affichage du formulaire d'ecriture d'un message
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/09
     * @see doMessageForm()
     * @param integer $topic Id de la discussion (si reponse dans cette discussion)
     * @param integer $id Id d'un message (si edition de ce message)
     * @param integer $quote (option) Numero du message cite
     * @param array $errors (option) Erreurs rencontrees
     * @param string $message Texte du message (si formulaire soumis)
     * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
   */
    public function processGetMessageForm ()
    {
        $criticErrors = array();
        $topic = _request("topic") ? _request("topic") : NULL;
        $id = _request("id") ? _request("id") : NULL;
        $quote = _request("quote") ? _request("quote") : NULL;
        $errors = _request("errors") ? _request("errors") : array();
        $message = _request("message") ? _request("message") : NULL;
        $preview = _request("preview") ? _request("preview") : 0;
        $format = _request("format") ? _request("format") : CopixConfig::get ('forum|default_format');

        $forumService = & CopixClassesFactory::Create ('forum|forumService');
        $dao_messages = CopixDAOFactory::create("forum_messages_topics");
        $dao_topics = CopixDAOFactory::create("forum_topics");

        if ($id) {	// Edition d'un message
            $rMessage = $dao_messages->get($id);
            if (!$rMessage)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noMessage');
            else {
                //print_r($rMessage);
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rMessage->forum );
                if (!$forumService->canMakeInForum('MODIFY_MESSAGE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
                else {
                    $message = ($message) ? $message : $rMessage->message;
                    $format = $rMessage->format;
                    $topic = $rMessage->topic;
                    $forum = $rMessage->topic_forum;
                }
            }
        } elseif ($topic) {		// Reponse dans un topic
            $rTopic = $dao_topics->get($topic);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noTopic');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
                if (!$forumService->canMakeInForum('ADD_MESSAGE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

                if ($quote && !$message) {
                    $rMessage = $dao_messages->get($quote);
                    //print_r($rMessage);
                    if ($rMessage && $rMessage->status==1 && $rMessage->topic_id==$topic) {

                        // Nom en clair de l'auteur qu'on cite
                        $userInfo = Kernel::getUserInfo("ID", $rMessage->auteur);


                        switch ($rMessage->format) {
                            case 'wiki' :
                                $message =  "\n\n\n> ".CopixI18N::get ('forum|forum.quote', array($userInfo["prenom"].' '.$userInfo["nom"]))." :\n> " . str_replace("\n", "\n> ", $rMessage->message);
                                break;
                            case 'dokuwiki' :
                                $message =  "\n\n\n> ".CopixI18N::get ('forum|forum.quote', array($userInfo["prenom"].' '.$userInfo["nom"]))." :\n> " . str_replace("\n", "\n>", $rMessage->message);

                                break;
                            case 'ckeditor' :
                                $message =  "<p></p><blockquote><b>--------- ".CopixI18N::get ('forum|forum.quote', array($userInfo["prenom"].' '.$userInfo["nom"]))." ---------</b><br/>" . $rMessage->message."</blockquote><br/>";
                break;

                        }
                        $format = $rMessage->format;

                        //$message = ">".." : \n> ".str_replace("\n", "\n> ", $rMessage->message)."\n";
                    }
                }

                $forum = $rTopic->forum;

            }
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.impossible');
        }




        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {
            $tpl = new CopixTpl ();
            $titre = ($id) ? CopixI18N::get ('forum|forum.modifMessage') : CopixI18N::get ('forum|forum.newMessage');
            $tpl->assign ('TITLE_PAGE', $titre);

            $tplForm = new CopixTpl ();
            $tplForm->assign ('topic', $topic);
            $tplForm->assign ('message', $message);
            $tplForm->assign ('format', $format);
            $tplForm->assign ("errors", $errors);
            $tplForm->assign ("id", $id);
            $tplForm->assign ("preview", $preview);

            if ($id)
                $tplForm->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('message'=>$rMessage)));
            else
                $tplForm->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('topic'=>$rTopic)));

            //$tplForm->assign ('wikibuttons', CopixZone::process ('kernel|wikibuttons', array('field'=>'message')));
            $tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'object'=>array('type'=>'MOD_FORUM', 'id'=>$forum), 'height'=>200)));

            $result = $tplForm->fetch('getmessageform.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


   /**
   * Soumission du formulaire d'ecriture d'un message
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/09
     * @see getMessageForm()
     * @param integer $topic Id de la discussion (si reponse dans cette discussion)
     * @param integer $id Id d'un message (si edition de ce message)
     * @param string $go Forme de soumission : preview (previsualiser) ou send (enregistrer)
     * @param string $message Champ message saisi
     * @param string $format Format du message
   */
    public function doMessageForm ()
    {
        $errors = $criticErrors = array();
        $topic = _request("topic") ? _request("topic") : NULL;
        $id = _request("id") ? _request("id") : NULL;
        $go = _request("go") ? _request("go") : 'preview';
        $message = _request("message") ? _request("message") : NULL;
        $format = $this->getRequest ('format', null);

        $forumService = CopixClassesFactory::create("forum|forumService");

        if ($id) {	// Edition d'un message
            $dao_messages = CopixDAOFactory::create("forum_messages_forums");
            $rMessage = $dao_messages->get($id);
            if (!$rMessage)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noMessage');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rMessage->forum_id );
                if (!$forumService->canMakeInForum('MODIFY_MESSAGE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
                else
                    $forum = $rMessage->forum_id;
            }

        } elseif ($topic) {		// Reponse dans un topic
            $dao_topics = CopixDAOFactory::create("forum_topics");
            $rTopic = $dao_topics->get($topic);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noTopic');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
                if (!$forumService->canMakeInForum('ADD_MESSAGE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
                else
                    $forum = $rTopic->forum_id;
            }
        } else
            $criticErrors[] = CopixI18N::get ('forum|forum.error.impossible');

        if (!$message)	$errors[] = CopixI18N::get ('forum|forum.error.typeMessage');
        if (!$format)	$errors[] = CopixI18N::get ('forum|forum.error.typeFormat');

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $auteur = _currentUser ()->getId();

            if ($id && !$errors && $go=='save') {	// Modification
                $rMessage->message = $message;
                $dao_messages->update($rMessage);
                $urlReturn = CopixUrl::get ('forum||getTopic', array("message"=>$id)).'#'.$id;
                return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
            } elseif (!$errors && $go=='save') {	// Insertion
                $add = $forumService->addForumMessage ($topic, $forum, $auteur, $message, $format);
                if (!$add)
                    $errors[] = CopixI18N::get ('forum|forum.error.saveMessage');
                $urlReturn = CopixUrl::get ('forum||getTopic', array("message"=>$add)).'#'.$add;
                return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
            }

            // Previsualisation
            return CopixActionGroup::process ('forum|forum::getMessageForm', array ('message'=>$message, 'format'=>$format, 'id'=>$id, 'topic'=>$topic, 'errors'=>$errors, 'preview'=>1));
        }
    }


   /**
   * Affichage du formulaire d'ecriture d'une discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/09
     * @see doTopicForm()
     * @param integer $forum Id du forum (si nouvelle discussion dans ce forum)
     * @param integer $id Id d'une discussion (si edition de cette discussion)
     * @param string $titre Valeur du champ "titre" (si formulaire soumis)
     * @param string $message Valeur du champ "message" (si formulaire soumis)
     * @param array $errors (option) Erreurs rencontrees
     * @param integer $preview (option) Si 1, affichera la preview de la discussion soumise, si 0 validera le formulaire
   */
    public function processGetTopicForm ()
    {
        $criticErrors = array();
        $forum = _request("forum") ? _request("forum") : NULL;
        $id = _request("id") ? _request("id") : NULL;
        $titre = _request("titre") ? _request("titre") : NULL;
        $message = _request("message") ? _request("message") : NULL;
        $errors = _request("errors") ? _request("errors") : array();
        $preview = _request("preview") ? _request("preview") : 0;
        $format = _request("format") ? _request("format") : CopixConfig::get ('forum|default_format');

        $forumService = & CopixClassesFactory::Create ('forum|forumService');

        if ($id) {	// Edition d'une discussion
            $dao_topics = CopixDAOFactory::create("forum_topics");
            $rTopic = $dao_topics->get($id);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noTopic');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
                if (!$forumService->canMakeInForum('MODIFY_TOPIC',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
                elseif ($preview) {
                } else {
                    $titre = $rTopic->titre;
                    //$message = $rTopic->message;
                    $forum = $rTopic->forum_id;
                }
            }
        } elseif ($forum) {		// Nouveau topic dans un forum
            $dao_forums = CopixDAOFactory::create("forum_forums");
            $rForum = $dao_forums->get($forum);
            if (!$rForum)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noForum');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $forum);
                if (!$forumService->canMakeInForum('ADD_TOPIC',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.impossible');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {
            $tpl = new CopixTpl ();
            $title_page = ($id) ? CopixI18N::get ('forum|forum.modifTopic') : CopixI18N::get ('forum|forum.newTopic');
            $tpl->assign ('TITLE_PAGE', $title_page);

            $tplForm = new CopixTpl ();
            $tplForm->assign ('forum', $forum);
            $tplForm->assign ('titre', $titre);
            $tplForm->assign ('message', $message);
            $tplForm->assign ('format', $format);
            $tplForm->assign ('errors', $errors);
            $tplForm->assign ('id', $id);
            $tplForm->assign ('preview', $preview);

            if ($id)
                $tplForm->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('modifyTopic'=>$rTopic)));
            else
                $tplForm->assign ('petitpoucet', CopixZone::process ('forum|petitpoucet', array('forum'=>$rForum)));

            $tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'object'=>array('type'=>'MOD_FORUM', 'id'=>$forum), 'height'=>200)));

            $result = $tplForm->fetch('gettopicform.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Soumission du formulaire d'ecriture d'une discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @see getTopicForm()
     * @param integer $forum Id du forum (si nouvelle discussion dans ce forum)
     * @param integer $id Id d'une discussion (si edition de cette discussion)
     * @param string $titre Valeur saisie pour le champ "titre"
     * @param string $message Valeur saisie pour le champ "message"
     * @param string $format Format du message
     * @param string $go Forme de soumission : preview (previsualiser) ou send (enregistrer)
   */
    public function doTopicForm ()
    {
        $errors = $criticErrors = array();
        $forum = _request("forum") ? _request("forum") : NULL;
        $id = _request("id") ? _request("id") : NULL;
        $titre = _request("titre") ? _request("titre") : NULL;
        $message = _request("message") ? _request("message") : NULL;
        $format = $this->getRequest ('format', null);
        $go = _request("go") ? _request("go") : 'preview';
        //print_r("go=$go");

        $forumService = CopixClassesFactory::create("forum|forumService");

        if ($id) {	// Edition d'une discussion
            $dao_topics = CopixDAOFactory::create("forum_topics");
            $rTopic = $dao_topics->get($id);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noTopic');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
                if (!$forumService->canMakeInForum('MODIFY_TOPIC',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } elseif ($forum) {		// Nouveau topic dans un forum
            $dao_forums = CopixDAOFactory::create("forum_forums");
            $rForum = $dao_forums->get($forum);
            if (!$rForum)
                $criticErrors[] = CopixI18N::get ('forum|forum.error.noForum');
            else {
                $mondroit = Kernel::getLevel( "MOD_FORUM", $forum);
                if (!$forumService->canMakeInForum('ADD_TOPIC',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.impossible');
        }

        if (!$titre)	$errors[] = CopixI18N::get ('forum|forum.error.typeTitle');
        if (!$id && !$message)	$errors[] = CopixI18N::get ('forum|forum.error.typeMessage');
        if (!$id && !$format)	$errors[] = CopixI18N::get ('forum|forum.error.typeFormat');

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $auteur = _currentUser ()->getId();

            if ($id && !$errors && $go=='save') { // Mise a jour
                $rTopic->titre = $titre;
                $dao_topics->update($rTopic);
                $urlReturn = CopixUrl::get ('forum||getTopic', array("id"=>$id));
                return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
            } elseif (!$errors && $go=='save') {	// Insertion
                $add = $forumService->addForumTopic ($forum, $auteur, $titre, $message, $format);
                if (!$add)
                    $errors[] = CopixI18N::get ('forum|forum.error.saveTopic');
                if (!$errors) {
                    $urlReturn = CopixUrl::get ('forum||getTopic', array("id"=>$add));
                    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
                }
            }
            //Kernel::deb($titre);
            return CopixActionGroup::process ('forum|forum::getTopicForm', array ('forum'=>$forum, 'titre'=>$titre, 'message'=>$message, 'format'=>$format, 'id'=>$id, 'errors'=>$errors, 'preview'=>1));

        }

    }


   /**
   * Suppression d'un message. Renvoie sur la page demandant confirmation avant de supprimer.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id Id du message a supprimer
   */
    public function getDeleteMessage ()
    {
        $errors = $criticErrors = array();
        $id = _request("id") ? _request("id") : NULL;

         $dao_messages = CopixDAOFactory::create("forum_messages_forums");
        $forumService = & CopixClassesFactory::Create ('forum|forumService');

        $rMessage = $dao_messages->get($id);

        if ($rMessage && $rMessage->status==1) {
            //print_r($rMessage);
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rMessage->forum_id );
            if (!$forumService->canMakeInForum('DELETE_MESSAGE',$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.noMessage');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $userInfo = Kernel::getUserInfo("ID", $rMessage->auteur);

            return CopixActionGroup::process ('genericTools|Messages::getConfirm',
                array (
                    'title'=>CopixI18N::get ('forum|forum.conf.messageFrom', array($userInfo["prenom"].' '.$userInfo["nom"])),
                    'message'=>CopixI18N::get ('forum|forum.conf.messageAsk'),
                    'confirm'=>CopixUrl::get('forum||doDeleteMessage', array('id'=>$id)),
                    'cancel'=>CopixUrl::get('forum||getTopic', array('message'=>$id))."#".$id,
                )
                );
        }
    }


   /**
   * Suppression d'un message
     *
     * La suppression d'un message change son statut dans la base, afin d'en conserver une trace tout en le rendant inaccessible a la lecture.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id Id du message a supprimer
   */
    public function doDeleteMessage ()
    {
        $criticErrors = array();
        $id = _request("id") ? _request("id") : NULL;

         $dao_messages = CopixDAOFactory::create("forum_messages_forums");
        $forumService = CopixClassesFactory::create("forum|forumService");
        $rMessage = $dao_messages->get($id);

        if ($rMessage && $rMessage->status==1) {
            //print_r($rMessage);
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rMessage->forum_id );
            if (!$forumService->canMakeInForum('DELETE_MESSAGE',$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.noMessage');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $rMessage->status=2;
            $dao_messages->update($rMessage);
            $forumService->updateInfosTopics($rMessage->topic);

            $urlReturn = CopixUrl::get ('forum||getTopic', array("id"=>$rMessage->topic));
            return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
        }

    }


   /**
   * Alerte un message
     *
     * L'alerte sur un message permet de signaler un message comme non conforme ou illicite. L'alerte incremente un compteur (un compteur par message).
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id Id du message a alerter
     * @todo A implementer (en commentaires dans le template)
   */
    public function doAlertMessage ()
    {
        $errors = $criticErrors = array();
        $id = _request("id") ? _request("id") : NULL;

         $dao_messages = CopixDAOFactory::create("forum_messages_forums");
        $rMessage = $dao_messages->get($id);

        if ($rMessage && $rMessage->status==1) {
            //print_r($rMessage);
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rMessage->forum_id );
            if (!$forumService->canMakeInForum("READ",$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else {
            $criticErrors[] = CopixI18N::get ('forum|forum.error.noMessage');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $rMessage->nb_alertes = $rMessage->nb_alertes+1;
            $dao_messages->update($rMessage);

            $urlReturn = CopixUrl::get ('forum||getTopic', array("message"=>$id)).'#'.$id;
            return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
        }

    }


   /**
   * Suppression d'une discussion. Renvoie sur la page demandant confirmation avant de supprimer.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id Id de la discussion a supprimer
   */
    public function getDeleteTopic ()
    {
        $errors = array();

        $id = $this->getRequest ('id', null);

         $dao_topics = CopixDAOFactory::create("forum_topics");
        $forumService = & CopixClassesFactory::Create ('forum|forumService');

        $rTopic = $dao_topics->get($id);
        if (!$rTopic)
            $errors[] = CopixI18N::get ('forum|forum.error.noTopic');
        else {
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
            if (!$forumService->canMakeInForum('DELETE_TOPIC',$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('forum||')));
        } else {

            return CopixActionGroup::process ('genericTools|Messages::getConfirm',
                array (
                    'title'=>$rTopic->titre,
                    'message'=>CopixI18N::get ('forum|forum.conf.topicAsk'),
                    'confirm'=>CopixUrl::get('forum||doDeleteTopic', array('id'=>$id)),
                    'cancel'=>CopixUrl::get('forum||getTopic', array('id'=>$id)),
                )
                );
        }
    }

   /**
   * Suppression effective d'une discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/10
     * @param integer $id Id de la discussion a supprimer
   */
    public function doDeleteTopic ()
    {
        $errors = array();

        $id = _request("id") ? _request("id") : NULL;

         $dao_topics = CopixDAOFactory::create("forum_topics");
        $forumService = CopixClassesFactory::create("forum|forumService");

        $rTopic = $dao_topics->get($id);
        if (!$rTopic)
            $errors[] = CopixI18N::get ('forum|forum.error.noTopic');
        else {
            $mondroit = Kernel::getLevel( "MOD_FORUM", $rTopic->forum_id );
            if (!$forumService->canMakeInForum('DELETE_TOPIC',$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('forum||')));
        } else {

            $del = $forumService->deleteForumTopic ($id);
            if (!$del)
                $errors[] = CopixI18N::get ('forum|forum.error.delTopic');

            if ($errors)
                return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('forum||getTopic', array("id"=>$id))));

            $urlReturn = CopixUrl::get ('forum||getForum', array("id"=>$rTopic->forum_id));
            return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
        }

    }

}

