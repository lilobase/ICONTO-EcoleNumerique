<?php

/**
 * Actiongroup du module Carnet
 *
 * @package Iconito
 * @subpackage	Carnet
 */
class ActionGroupCarnet extends EnicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

   /**
   * Affiche un cahier de correspondance
     *
     * Affiche un cahier de correspondance, pour une classe et/ou un élève.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $classe Id de la classe
     * @param integer $eleve Id de l'élève
   */
   public function getCarnet ()
   {
         $dao = CopixDAOFactory::create("carnet_topics");
         $daoMessages = CopixDAOFactory::create("carnet_messages");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');

        $classe = $this->getRequest ('classe', null);
        $eleve = $this->getRequest ('eleve', null);

        $mondroit = $carnet_service->getUserDroitInCarnet (array("classe"=>$classe, "eleve"=>$eleve));
        $criticErrors = array();
        if (!$classe && !$eleve)
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.noClasseEleve');
        elseif (!$mondroit)
            $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||getCarnet')));


        $session = Kernel::getSessionBU();

        if ($eleve && $eleve!='CLASSE') { // Un élève précis
            $eleves = array(array("id"=>$eleve));
            $userInfo = $kernel_service->getUserInfo ("USER_ELE", $eleve);
            if (!$classe) {	// On cherche sa classe
                $parentEle = $kernel_service->getNodeParents( "USER_ELE", $eleve );
                $trouve = false;
                foreach ($parentEle as $w) {
                    if ($w["type"]=="BU_CLASSE")
                        $trouve = $w["id"];
                }
                if (!$trouve) { // l'élève n'est dans aucune classe
                } else {
                    $classe = $trouve;
                    //$nb_eleves = count($carnet_service->getUserElevesInClasse($classe));
                }
            } //else
                //$nb_eleves = count($carnet_service->getUserElevesInClasse($classe));



            //$list = $dao->getListCarnetsTopicsForEleve($eleves, $classe);
            $title = $userInfo["prenom"]." ".$userInfo["nom"];
            $list = $dao->getListCarnetsTopicsForElevesInClasse ($eleves, $classe, $session['user_id']);
        } elseif ($classe) { // Tout d'une classe

            $nodeInfo = $kernel_service->getNodeInfo ("BU_CLASSE", $classe, false);
            //print_r($nodeInfo);
            $title = "".$nodeInfo["nom"];

            $eleves = $carnet_service->getUserElevesInClasse($classe);
            //print_r($eleves);
            //$nb_eleves_classe = count($eleves);
            //die();
            $list = $dao->getListCarnetsTopicsForElevesInClasse ($eleves, $classe, $session['user_id']);

        }

        $nb_eleves_classe = $carnet_service->getNbElevesInClasse ($classe);
        $hisEleves = $carnet_service->getUserElevesInClasse($classe);
        $canWriteClasse = $carnet_service->canMakeInCarnet('WRITE_CLASSE', NULL);



        while (list($k,) = each($list)) {
            $userInfo = $kernel_service->getUserInfo("ID", $list[$k]->createur);
            $list[$k]->createur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
            $list[$k]->createur_infos = $userInfo;
            // A partir du dernier message, on cherche son auteur
            if ($list[$k]->last_msg_id) {
                $lastMsg = $daoMessages->get($list[$k]->last_msg_id);
                //print_r($lastMsg);
                if ($lastMsg) {
                    $userInfo = $kernel_service->getUserInfo("ID", $lastMsg->auteur);
                    $list[$k]->last_msg_auteur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
                    $list[$k]->last_msg_auteur_infos = $userInfo;
                }
            }

            $to = $dao->getElevesForTopic ($list[$k]->id);

            if ($eleve == 'CLASSE' && count($to) != $nb_eleves_classe) {	// Seulement les messages adressés à toute la classe
                unset ($list[$k]);
                continue;
            }

            $list[$k]->nb_eleves = count($to);
            if ($list[$k]->nb_eleves==1) {	// Un seul élève, on va chercher son nom
                while (list($j,) = each($to)) {
                    $userInfo = $kernel_service->getUserInfo("USER_ELE", $to[$j]->eleve);
                    $to[$j]->eleve_nom = $userInfo["prenom"]." ".$userInfo["nom"];
                    $to[$j]->eleve_infos = $userInfo;
                }
                $list[$k]->eleves_infos = $to;
            }

            $messages = $dao->getListCarnetsMessagesForTopicAndEleves ($list[$k]->id, $eleves);
            $list[$k]->nb_messages = count($messages);
            //print_r($list[$k]);
        }
        //print_r($list);


        CopixHTMLHeader::addCSSLink (_resource("styles/module_carnet.css"));

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('carnet|carnet.carnet').' - '.$title);

        $tplListe = new CopixTpl ();
        $tplListe->assign ('list', $list);
        $tplListe->assign ('classe', $classe);
        $tplListe->assign ('nb_eleves_classe', $nb_eleves_classe);
        $tplListe->assign ('eleve', $eleve);
        $tplListe->assign ('canWriteClasse', $canWriteClasse);
        $tplListe->assign ('hisEleves', $hisEleves);
        $tplListe->assign ('mondroit', $mondroit);
        $result = $tplListe->fetch("getcarnet.tpl");

        $tpl->assign ("MAIN", $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

   /**
   * Affiche une discussion d'un cahier de correspondance
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id de la discussion
     * @param integer $eleve (option) Id de l'élève
     * @param integer $print (option, 0 par défaut) Si 1, affiche la discussion au format imprimable
     * @param string $go (option) Si vaut "new", redirige sur le premier message non lu de la discussion
   */
   public function getTopic ()
   {
         $dao = CopixDAOFactory::create("carnet_topics");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');

        $id = $this->getRequest ('id', null);
        $eleve = $this->getRequest ('eleve', null);
        $print = $this->getRequest ('print', 0);
        $go = $this->getRequest ('go', null);

        $session = Kernel::getSessionBU();
        $criticErrors = array();

        if ($go == "new") {
            $daoTracking = CopixDAOFactory::create("carnet|carnet_tracking2");

            if ($eleve && $eleve!='CLASSE')
                $idEleves = array($eleve);
            else {
                $to = $dao->getElevesForTopic ($id);
                $idEleves = array();
                foreach ($to as $item) $idEleves[] = $item->eleve;
            }
            //print_r($idEleves);
            //die();

            $unread = $daoTracking->getFirstUnreadMessage($id, $session['user_id'], $idEleves);
      //_dump($unread[0]);
            if ($unread[0]->id) {	// Il est déjà passé dans le topic
                $urlReturn = CopixUrl::get ('|getTopic', array('id'=>$id, 'eleve'=>$eleve)).'#m'.$unread[0]->id;
            } else { // Jamais passé, on le renvoie au début du topic
                $urlReturn = CopixUrl::get ('|getTopic', array('id'=>$id, 'eleve'=>$eleve));
            }
            //die($urlReturn);
            return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
        }

        $topic = $dao->get ($id);
        $classe = $topic->classe;
        $ppo = new CopixPPO ();

        if (!$topic)
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.noTopic');

    $matrix = & enic::get('matrixCache');

    $ppo->canView_USER_RES = $matrix->classe($classe)->_right->USER_RES->voir;
    $ppo->canView_USER_ENS = $matrix->classe($classe)->_right->USER_ENS->voir;
    $ppo->canView_USER_ELE = $matrix->classe($classe)->_right->USER_ELE->voir;
    //_dump($canWrite_USER_RES);

        $mondroit = $carnet_service->getUserDroitInCarnet (array("classe"=>$classe, "eleve"=>$eleve));
        //print_r("mondroit=$mondroit");
        if (!$mondroit)
            $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||getCarnet', array())));


        if (0) {
        } else {

//			print_r2($topic);

            // 1. Infos sur le topic
            $userInfo = $kernel_service->getUserInfo("ID", $topic->createur);
            $topic->createur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
            $topic->createur_infos = $userInfo;
            $to = $dao->getElevesForTopic ($topic->id);
            while (list($k,) = each($to)) {
                $userInfo = $kernel_service->getUserInfo("USER_ELE", $to[$k]->eleve);
                $to[$k]->eleve_infos = $userInfo["prenom"]." ".$userInfo["nom"];
            }
            $topic->eleves = $to;
            $topic->nb_eleves = count($to);
      // Avatar de l'auteur
             $avatar = Prefs::get('prefs', 'avatar', $topic->createur);
          $topic->avatar = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
            //print_r2($topic);

            // 2. Les messages
            if ($eleve && $eleve!='CLASSE') {	// Filtrage sur un élève
                $list = $dao->getListCarnetsMessagesForTopicAndEleve ($id, $eleve);
                $idEleves = array($eleve);
            } else {	// Tous les élèves de la classe
                $eleves = $carnet_service->getUserElevesInClasse($topic->classe);
                $list = $dao->getListCarnetsMessagesForTopicAndEleves ($id, $eleves);
                //print_r($list);
                $idEleves = array();
                foreach ($to as $item) $idEleves[] = $item->eleve;
            }
            //print_r($idEleves);

            while (list($k,) = each($list)) {
                $userInfo = $kernel_service->getUserInfo("ID", $list[$k]->auteur);
                $list[$k]->auteur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
                $list[$k]->auteur_infos = $userInfo;
                $userInfo = $kernel_service->getUserInfo("USER_ELE", $list[$k]->eleve);
                $list[$k]->eleve_nom = $userInfo["prenom"]." ".$userInfo["nom"];
                $list[$k]->eleve_infos = $userInfo;
        // Avatar de l'expéditeur
              $avatar = Prefs::get('prefs', 'avatar', $list[$k]->auteur);
              $list[$k]->avatar = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
            }

            $canWriteClasse = $carnet_service->canMakeInCarnet('WRITE_CLASSE', NULL);
            $canPrintTopic = $carnet_service->canMakeInCarnet('PRINT_TOPIC', NULL);

            // On enregistre sa lecture (tracking)
            $carnet_service->userReadTopic ($id, $session['user_id'], $idEleves);

            CopixHTMLHeader::addCSSLink (_resource("styles/module_carnet.css"));
            if ($print)
                CopixHTMLHeader::addCSSLink (_resource("styles/module_carnet_print.css"), array('media'=>'print'));

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $topic->titre);
            //$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('carnet||getCarnet', array("classe"=>$classe, "eleve"=>$eleve)).'">'.CopixI18N::get ('carnet|carnet.backCarnet').'</a>');

            //print_r($list);
            //print_r($topic);
            $tplListe = new CopixTpl ();
            $tplListe->assign ('ppo', $ppo);
            $tplListe->assign ('topic', $topic);
            $tplListe->assign ('eleve', $eleve);
            $tplListe->assign ('canWriteClasse', $canWriteClasse);
            $tplListe->assign ('canPrintTopic', $canPrintTopic);
            $tplListe->assign ('list', $list);
            $tplListe->assign ('session', Kernel::getSessionBU());
            $tplListe->assign ('linkClasse', CopixUrl::get('carnet||getTopic', array('id'=>$topic->id)));
            if ($print)
                $result = $tplListe->fetch("gettopicprint.tpl");
            else
                $result = $tplListe->fetch("gettopic.tpl");

            $tpl->assign ("MAIN", $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Formulaire d'écriture d'une nouvelle correspondance
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see doTopicForm()
     * @param integer $classe Id de la classe
     * @param integer $eleve Id de l'élève
     * @param integer $eleves (si formulaire soumis) Id des élèves
     * @param string $titre (si formulaire soumis) Titre de la discussion
     * @param string $message (si formulaire soumis) Corps du premier message
     * @param array $errors (option) Erreurs rencontrées
     * @param integer $preview (option) Si 1, affichera la preview de la discussion soumise, si 0 validera le formulaire
   */
    public function processGetTopicForm ()
    {
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');

        $criticErrors = array();
        $id = NULL;
        $classe = $this->getRequest ('classe', null);
        $eleve = $this->getRequest ('eleve', null);
        $eleves = $this->getRequest ('eleves', array());
        $titre = $this->getRequest ('titre', null);
        $message = $this->getRequest ('message', null);
        $errors = $this->getRequest ('errors', array());
        $preview = $this->getRequest ('preview', 0);
        $format = CopixConfig::get ('carnet|default_format');

        $mondroit = $carnet_service->getUserDroitInCarnet (array("classe"=>$classe, "eleve"=>$eleve));
        if (!$mondroit)
            $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($id) {
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        } elseif ($classe) {		// Nouvelle correspondance
            // Droits vérifiés par mondroit
        } else {
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||getCarnet')));
        } else {


//		print_r2($eleves);

            $hisEleves = $carnet_service->getUserElevesInClasse($classe);
            $canWriteClasse = $carnet_service->canMakeInCarnet('WRITE_CLASSE', NULL);

            $tpl = new CopixTpl ();
            $title_page = ($id) ? CopixI18N::get ('carnet|carnet.modifTopic') : CopixI18N::get ('carnet|carnet.newTopic');
            $tpl->assign ('TITLE_PAGE', $title_page);
            //$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('carnet||getCarnet', array("classe"=>$classe, "eleve"=>$eleve)).'">'.CopixI18N::get ('carnet|carnet.backCarnet').'</a>');

            // On coche éventuellement l'élève par défaut à la première arrivée
            if (!$eleves) {
                if ($eleve && $eleve != 'CLASSE') // Un seul élève
                    $eleves[$eleve] = 0;
                else	// Toute la classe, tout les élèves sont cochés directement dans le template
                    $nothing = 1;
            }

            CopixHTMLHeader::addCSSLink (_resource("styles/module_carnet.css"));
            CopixHtmlHeader::addJSLink (CopixUrl::get().'js/iconito/module_carnet.js');

            $tplForm = new CopixTpl ();

            $tplForm->assign ('id', $id);
            $tplForm->assign ('classe', $classe);
            $tplForm->assign ('eleve', $eleve);
            $tplForm->assign ('eleves', $eleves);
            $tplForm->assign ('canWriteClasse', $canWriteClasse);
            $tplForm->assign ('hisEleves', $hisEleves);
            $tplForm->assign ('titre', $titre);
            $tplForm->assign ('message', $message);
            $tplForm->assign ('format', $format);
            $tplForm->assign ('errors', $errors);
            $tplForm->assign ('preview', $preview);
            $tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'height'=>200)));

            $result = $tplForm->fetch('gettopicform.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Soumission du formulaire d'écriture d'une nouvelle discussion
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see getTopicForm()
     * @param integer $classe Id de la classe
     * @param integer $eleve Id de l'élève
     * @param array $eleves Id des élèves concernés (cases cochées)
     * @param string $titre Valeur saisie pour le champ "titre"
     * @param string $message Valeur saisie pour le champ "message"
     * @param string $go Forme de soumission : preview (prévisualiser) ou send (enregistrer)
   */
    public function doTopicForm ()
    {
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');

        $errors = $criticErrors = array();
        $id = NULL;
        $classe = $this->getRequest ('classe', null);
        $eleve = $this->getRequest ('eleve', null);
        $eleves = $this->getRequest ('eleves', array());
        $titre = $this->getRequest ('titre', null);
        $message = $this->getRequest ('message', null);
        $go = $this->getRequest ('go', 'preview');
        $format = $this->getRequest ('format', null);

        $mondroit = $carnet_service->getUserDroitInCarnet (array("classe"=>$classe, "eleve"=>$eleve));
        if (!$mondroit)
            $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($id) {
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        } elseif ($classe) {		// Nouvelle correspondance
            // Droits vérifiés par mondroit
        } else {
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        }


        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||')));
        } else {

            if (!$eleves)	$errors[] = CopixI18N::get ('carnet|carnet.error.selectEleve');
            if (!$titre)	$errors[] = CopixI18N::get ('carnet|carnet.error.typeTitle');
            if (!$id && !$message)	$errors[] = CopixI18N::get ('carnet|carnet.error.typeMessage');
            if (!$format)	$errors[] = CopixI18N::get ('carnet|carnet.error.typeFormat');

            $createur = _currentUser ()->getId();

            if ($id && !$errors && $go=='save') { // Mise à jour
                // Y a pas
            } elseif (!$errors && $go=='save') {	// Insertion
                $add = $carnet_service->addCarnetTopic ($classe, $createur, $titre, $message, $eleves, $format);
                if (!$add)
                    $errors[] = CopixI18N::get ('carnet|carnet.error.saveTopic');
                if (!$errors) {
                    $urlReturn = CopixUrl::get ('carnet||getTopic', array("id"=>$add, "eleve"=>$eleve));
                    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
                }
            }

            //print_r($eleves);

            return CopixActionGroup::process ('carnet|carnet::getTopicForm', array ('classe'=>$classe, 'eleve'=>$eleve, 'eleves'=>array_flip($eleves), 'titre'=>$titre, 'message'=>$message, 'format'=>$format, 'id'=>$id, 'errors'=>$errors, 'preview'=>(($go=='preview')?1:0)));

        }

    }



   /**
   * Affichage du formulaire d'écriture d'un message (réponse à une discussion)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see doMessageForm()
     * @param integer $topic Id de la discussion
     * @param integer $eleve (option) Id de l'élève concerné
     * @param string $message Texte du message (si formulaire soumis)
     * @param array $errors (option) Erreurs rencontrées
     * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
   */
    public function processGetMessageForm ()
    {
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');
        $dao_carnets_to = CopixDAOFactory::create("carnet|carnet_topics_to");
        //$eleves = $carnet_service->getHisCarnetEleves();	// Ses élèves

        $criticErrors = array();
        $id = NULL;
        $topic = $this->getRequest ('topic', null);
        $eleve = $this->getRequest ('eleve', null);
        $message = $this->getRequest ('message', null);
        $errors = $this->getRequest ('errors', array());
        $preview = $this->getRequest ('preview', 0);
        $format = CopixConfig::get ('carnet|default_format');

        if ($id) {	// Edition d'un message
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        } elseif ($topic && $eleve) {		// Réponse dans un topic, sur un élève
            $rTopic = $dao_carnets_to->get($topic, $eleve);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('carnet|carnet.error.noTopic');
            else {
                $mondroit = $carnet_service->getUserDroitInCarnet (array("eleve"=>$eleve));
                if (!$mondroit)
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else {
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||')));
        } else {
            $tpl = new CopixTpl ();
            //$titre = ($id) ? 'Modification du message' : 'Nouveau message';

            CopixHTMLHeader::addCSSLink (_resource("styles/module_carnet.css"));
            CopixHtmlHeader::addJSLink (CopixUrl::get().'js/iconito/module_carnet.js');

            $tpl->assign ('TITLE_PAGE', $rTopic->topic_titre);

            $tplForm = new CopixTpl ();
            $tplForm->assign ('topic', $topic);
            $tplForm->assign ('eleve', $eleve);
            $tplForm->assign ('message', $message);
            $tplForm->assign ('format', $format);
            $tplForm->assign ("errors", $errors);
            $tplForm->assign ("id", $id);
            $tplForm->assign ('preview', $preview);
            $tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'height'=>200)));

            $result = $tplForm->fetch('getmessageform.tpl');
            $tpl->assign ('MAIN', $result);
            //$tpl->assign ('MENU', '<a href="'.CopixUrl::get ('carnet||getTopic', array("id"=>$topic, "eleve"=>$eleve)).'">'.CopixI18N::get ('carnet|carnet.backTopic').'</a> :: <a href="'.CopixUrl::get ('carnet||getCarnet', array("eleve"=>$eleve)).'">'.CopixI18N::get ('carnet|carnet.backCarnet').'</a>');

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


   /**
   * Soumission du formulaire d'écriture d'un message
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/17
     * @see getMessageForm()
     * @param integer $topic Id de la discussion
     * @param integer $eleve (option) Id de l'élève concerné
     * @param string $message Texte du message
     * @param string $go Forme de soumission : preview (prévisualiser) ou send (enregistrer)
   */
    public function doMessageForm ()
    {
        $carnet_service = & CopixClassesFactory::Create ('carnet|CarnetService');
        $dao_carnets_to = CopixDAOFactory::create("carnet|carnet_topics_to");

        $errors = $criticErrors = array();
        $topic = $this->getRequest ('topic', null);
        $eleve = $this->getRequest ('eleve', null);
        $id = NULL;
        $message = $this->getRequest ('message', null);
        $go = $this->getRequest ('go', 'preview');
        $format = $this->getRequest ('format', null);

        if ($id) {	// Edition d'un message
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');
        } elseif ($topic && $eleve) {		// Réponse dans un topic, sur un élève
            $rTopic = $dao_carnets_to->get($topic, $eleve);
            if (!$rTopic)
                $criticErrors[] = CopixI18N::get ('carnet|carnet.error.noTopic');
            else {
                $mondroit = $carnet_service->getUserDroitInCarnet (array("eleve"=>$eleve));
                if (!$mondroit)
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else
            $criticErrors[] = CopixI18N::get ('carnet|carnet.error.impossible');


        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('carnet||')));
        } else {

            if (!$message)	$errors[] = CopixI18N::get ('carnet|carnet.error.typeMessage');
            if (!$format)	$errors[] = CopixI18N::get ('carnet|carnet.error.typeFormat');
            $auteur = _currentUser ()->getId();

            if ($id && !$errors && $go=='save') {	// Modification, y a pas
            } elseif (!$errors && $go=='save') {	// Insertion

                $add = $carnet_service->addCarnetMessage ($topic, $eleve, $auteur, $message, $format);
                if (!$add)
                    $errors[] = CopixI18N::get ('carnet|carnet.error.saveMessage');
                if (!$errors) {
                    $urlReturn = CopixUrl::get ('carnet||getTopic', array("id"=>$topic, "eleve"=>$eleve));
                    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
                }
            }

            return CopixActionGroup::process ('carnet|carnet::getMessageForm', array ('message'=>$message, 'format'=>$format, 'id'=>$id, 'topic'=>$topic, 'eleve'=>$eleve, 'errors'=>$errors, 'preview'=>(($go=='preview')?1:0)));

        }

    }


   /**
   * Redirection vers un carnet
     *
     * $id peut être de type "XX" (Id de carnet), vaut "CLASSE_XX" (carnet d'une classe) ou ELEVE_XX (carnet d'un élève)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param string $id Id d'un carnet
     * @param integer $classe Id d'une classe
     * @param integer $eleve Id d'un élève
   */
    public function go ()
    {
        $id = $this->getRequest ('id', null);
        $classe = $this->getRequest ('classe', null);
        $eleve = $this->getRequest ('eleve', null);

        if( $id ) {
            if( ereg( 'CLASSE_([0-9]+)', $id, $regs ) )
                $classe = $regs[1];
            if( ereg( 'ELEVE_([0-9]+)', $id, $regs ) )
                $eleve = $regs[1];
        }

        if( $classe ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('carnet||getCarnet', array('classe'=>$classe) ));
        } elseif( $eleve ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('carnet||getCarnet', array('eleve'=>$eleve) ));
        }
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
    }


}

