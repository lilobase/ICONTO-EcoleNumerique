<?php

/**
 * Actiongroup du module Minimail
 *
 * @package Iconito
 * @subpackage	Minimail
 */
require_once (COPIX_UTILS_PATH . 'CopixPager.class.php');

class ActionGroupMinimail extends EnicActionGroup
{
    public function beforeAction()
    {
        _currentUser()->assertCredential('group:[current_user]');
        if (!Kernel::is_connected()) {
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('kernel|kernel.error.nologin'), 'back' => CopixUrl::get('auth|default|login')));
        }
    }

    /**
     * Affiche la liste des messages re�us pour l'utilisateur connect�
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     */
    public function getListRecv()
    {
        $this->addJs('js/iconito/module_minimail.js');

        $tpl = new CopixTpl ();
        $tpl->assign('TITLE_PAGE', CopixI18N::get('minimail.mess_recv'));

        $menu = array();
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_recv'), 'url' => CopixUrl::get('minimail||getListRecv'), 'current' => true);
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_send'), 'url' => CopixUrl::get('minimail||getListSend'));
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_write'), 'url' => CopixUrl::get('minimail||getNewForm'));
        $tpl->assign('MENU', $menu);

        $tplListe = new CopixTpl ();

        $messagesAll = _ioDAO("minimail_to")->getListRecvAll(_currentUser()->getId());

        if (count($messagesAll)) {

            $params = Array(
                'perPage' => intval(CopixConfig::get('minimail|list_nblines')),
                'delta' => 5,
                'recordSet' => $messagesAll,
                'template' => '|pager.tpl'
            );
            $Pager = CopixPager::Load($params);
            $tplListe->assign('pager', $Pager->GetMultipage());

            $list = $Pager->data;
            // Infos des utilisateurs sur les messages a afficher
            foreach ($list as $k => $topic) {

                if ($userInfo = Kernel::getUserInfo("ID", $list[$k]->from_id)) {
                    //print_r($userInfo);
                    $list[$k]->from = $userInfo;
                    $list[$k]->from_id_infos = $userInfo["prenom"] . " " . $userInfo["nom"] . " (" . $userInfo["login"] . ")";
                }
            }
            $tplListe->assign('list', $list);
        }

        $result = $tplListe->fetch("getlistrecv.tpl");

        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Affiche la liste des messages envoy�s pour l'utilisateur connect�
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     */
    public function getListSend()
    {
        $this->addJs('js/iconito/module_minimail.js');

        $tpl = new CopixTpl ();
        $tpl->assign('TITLE_PAGE', CopixI18N::get('minimail.mess_send'));

        $menu = array();
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_recv'), 'url' => CopixUrl::get('minimail||getListRecv'));
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_send'), 'url' => CopixUrl::get('minimail||getListSend'), 'current' => true);
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_write'), 'url' => CopixUrl::get('minimail||getNewForm'));
        $tpl->assign('MENU', $menu);

        $tplListe = new CopixTpl ();

        $messagesAll = _ioDAO("minimail_from")->getListSendAll(_currentUser()->getId());

        if (count($messagesAll)) {
            $params = Array(
                'perPage' => intval(CopixConfig::get('minimail|list_nblines')),
                'delta' => 5,
                'recordSet' => $messagesAll,
                'template' => '|pager.tpl'
            );
            $Pager = CopixPager::Load($params);
            $tplListe->assign('pager', $Pager->GetMultipage());

            $list = $Pager->data;
            // Infos des utilisateurs sur les messages a afficher
            foreach ($list as $k => $null) {
                $dest = _ioDAO("minimail_to")->selectDestFromId($list[$k]->id);
                foreach ($dest as $j => $null) {
                    //print_r($dest[$j]->to_id);
                    $userInfo = Kernel::getUserInfo("ID", $dest[$j]->to_id);
                    $dest[$j]->to = $userInfo;
                    $dest[$j]->to_id_infos = $userInfo["prenom"] . " " . $userInfo["nom"] . " (" . $userInfo["login"] . ")";
                }
                $list[$k]->destin = $dest;
            }
            $tplListe->assign('list', $list);
        }

        $result = $tplListe->fetch("getlistsend.tpl");

        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Affiche un minimail en d�tail
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @param integer $id Id du minimail
     */
    public function getMessage()
    {
        $this->addJs('js/iconito/module_minimail.js');

        $MinimailService = & CopixClassesFactory::Create('minimail|MinimailService');

        // 2 DAO -> 2 assign

        $idUser = _currentUser()->getId();
        $idMessage = _request("id");
        $errors = array();

        $daoFrom = CopixDAOFactory::create("minimail_from");
        $daoTo = CopixDAOFactory::create("minimail_to");

        $message = $daoFrom->getMessage($idMessage);
        $dest = $daoTo->selectDestFromId($idMessage);

        $isRecv = $isSend = false;

        if ($message) {
            $message->prev = NULL;
            $message->next = NULL;
        }
        if ($message && $message->from_id == $idUser) { // Message qu'il a envoy�
            $message->type = "send";
            $prev = $daoFrom->getFromPrevMessage($message->date_send, $idUser);
            if ($prev)
                $message->prev = $prev->id;
            $next = $daoFrom->getFromNextMessage($message->date_send, $idUser);
            if ($next)
                $message->next = $next->id;
            $isSend = true;
        } else { // Il en est peut-�tre destinataire
            $isDest = $daoTo->selectDestFromIdAndToUser($idMessage, $idUser); // Test s'il est dans les destin
            if ($isDest) {
                $serv = CopixClassesFactory::create("MinimailService");
                $serv->markMinimailAsRead($dest, $idUser);
                $message->type = "recv";
                $prev = $daoTo->getToPrevMessage($message->date_send, $idUser);
                if ($prev)
                    $message->prev = $prev->id;
                $next = $daoTo->getToNextMessage($message->date_send, $idUser);
                if ($next)
                    $message->next = $next->id;
                $isRecv = true;
            } else { // Il tente d'afficher un message qu'il n'a pas envoy� ni re�u !
                $errors[] = CopixI18N::get('minimail.error.cantDisplay');
            }
        }

        if ($errors) {
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => implode('<br/>', $errors), 'back' => CopixUrl::get('minimail||')));
        } else {

            $userInfo = Kernel::getUserInfo("ID", $message->from_id);
            $message->from = $userInfo;
            $message->from_id_infos = $userInfo["prenom"] . " " . $userInfo["nom"] . " (" . $userInfo["login"] . ")";
            foreach ($dest as $j => $null) {
                //print_r($dest[$j]->to_id);
                $userInfo = Kernel::getUserInfo("ID", $dest[$j]->to_id);
                $dest[$j]->to = $userInfo;
                $dest[$j]->to_id_infos = $userInfo["prenom"] . " " . $userInfo["nom"] . " (" . $userInfo["login"] . ")";
            }

            // Avatar de l'exp�diteur
            $avatar = Prefs::get('prefs', 'avatar', $message->from_id);
            $message->avatar = ($avatar) ? CopixConfig::get('prefs|avatar_path') . $avatar : '';

            $tpl = new CopixTpl ();
            $tpl->assign('TITLE_PAGE', $message->title);

            $menu = array();
            $menu[] = array('txt' => CopixI18N::get('minimail.mess_recv'), 'url' => CopixUrl::get('minimail||getListRecv'), 'current' => $isRecv);
            $menu[] = array('txt' => CopixI18N::get('minimail.mess_send'), 'url' => CopixUrl::get('minimail||getListSend'), 'current' => $isSend);
            $menu[] = array('txt' => CopixI18N::get('minimail.mess_write'), 'url' => CopixUrl::get('minimail||getNewForm'));
            $tpl->assign('MENU', $menu);

            $message->attachment1IsImage = $MinimailService->isAttachmentImage($message->attachment1);
            $message->attachment2IsImage = $MinimailService->isAttachmentImage($message->attachment2);
            $message->attachment3IsImage = $MinimailService->isAttachmentImage($message->attachment3);
            $message->attachment1Name = $MinimailService->getAttachmentName($message->attachment1);
            $message->attachment2Name = $MinimailService->getAttachmentName($message->attachment2);
            $message->attachment3Name = $MinimailService->getAttachmentName($message->attachment3);
            //print_r($message);

            $tplListe = new CopixTpl ();
            $tplListe->assign('message', $message);
            $tplListe->assign('dest', $dest);
            $result = $tplListe->fetch('getmessage.tpl');
            $tpl->assign('MAIN', $result);

            $plugStats = CopixPluginRegistry::get("stats|stats");
            $plugStats->setParams(array('objet_a' => $idMessage));

            return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
        }
    }

    /**
     * Formulaire d'�criture d'un minimail
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @see doSend()
     * @param integer $reply Id du minimail si c'est une r�ponse � ce minimail
     * @param string $title Titre du minimail (si formulaire soumis)
     * @param string $login Logins du(des) destinataire(s) (si formulaire soumis)
     * @param string $dest Logins du(des) destinataire(s) (si formulaire soumis)
     * @param string $message Corps du minimail (si formulaire soumis)
     * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
     * @param integer $forward Id du minimail si c'est un forward
     */
    public function processGetNewForm()
    {
        $this->addJs('js/iconito/module_minimail.js');

        $tpl = new CopixTpl ();

        $tpl->assign('TITLE_PAGE', CopixI18N::get('minimail.mess_write'));

        $menu = array();
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_recv'), 'url' => CopixUrl::get('minimail||getListRecv'));
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_send'), 'url' => CopixUrl::get('minimail||getListSend'));
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_write'), 'url' => CopixUrl::get('minimail||getNewForm'), 'current' => true);
        $tpl->assign('MENU', $menu);


        $idUser = _currentUser()->getId();

        $title = _request("title") ? _request("title") : NULL;
        $login = _request("login") ? _request("login") : NULL;
        $dest = _request("dest") ? _request("dest") : $login;
        $message = _request("message") ? _request("message") : NULL;
        $format = CopixConfig::get('minimail|default_format');

        $preview = _request("preview") ? _request("preview") : 0;
        $iAll = _request("all");
        $iReply = CopixRequest::getInt('reply');
        $iForward = CopixRequest::getInt('forward');

        $tplForm = new CopixTpl ();

        if ($iReply && !$message) { // Tentative de reponse a un message
            $message = _ioDAO('minimail_from')->getMessage($iReply);
            $destin = _ioDAO('minimail_to')->selectDestFromId($iReply);
            $serv = CopixClassesFactory::create("MinimailService");
            if ($message && $serv->canViewMessage($message, $destin, $idUser)) {
                $format = $message->format;
                $answer = $serv->constructAnswer($message, $destin, $idUser, $format, $iAll);
                $dest = $answer["dest"];
                $title = utf8_decode($answer["title"]);
                $message = $answer["message"];
                $tplForm->assign("reply", $iReply);
            }
        } elseif ($iForward && !$message) { // Tentative de forward
            $message = _ioDAO('minimail_from')->getMessage($iForward);
            $destin = _ioDAO('minimail_to')->selectDestFromId($iForward);
            $serv = CopixClassesFactory::create("MinimailService");
            if ($message && $serv->canViewMessage($message, $destin, $idUser)) {
                $format = $message->format;
                $forward = $serv->constructForward($message, $format);
                $title = $forward["title"];
                $message = $forward["message"];
                $tplForm->assign("forward", $iForward);
            }
        }



        $tplForm->assign("dest", $dest);
        $tplForm->assign("title", $title);
        $tplForm->assign("message", $message);
        $tplForm->assign("format", $format);
        $tplForm->assign("preview", $preview);
        $tplForm->assign("errors", (_request("errors") ? _request("errors") : ""));
        $tplForm->assign('message_edition', CopixZone::process('kernel|edition', array('field' => 'message', 'format' => $format, 'content' => $message, 'height' => 200, 'object' => 'USER', 'options' => array('focus' => 1))));

        $tplForm->assign('linkpopup', CopixZone::process('annuaire|linkpopup', array('field' => 'dest', 'right' => 'communiquer')));
        $tplForm->assign("attachment_size", CopixConfig::get('minimail|attachment_size'));
        $result = $tplForm->fetch("writeform.tpl");

        $tpl->assign("MAIN", $result);

        return new CopixActionReturn(COPIX_AR_DISPLAY, $tpl);
    }

    /**
     * Soumission du formulaire d'�criture d'un minimail (envoie le minimail)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @see getNewForm()
     * @param string $dest Logins du(des) destinataire(s)
     * @param string $title Titre du minimail
     * @param string $message Corps du minimail
     * @param string $go Forme de soumission : preview (pr�visualiser) ou send (enregistrer)
     */
    public function doSend()
    {
        $dest = _request("dest") ? _request("dest") : "";
        $title = _request("title") ? _request("title") : "";
        $message = _request("message") ? _request("message") : "";
        $format = _request("format") ? _request("format") : "";

        $go = _request("go") ? _request("go") : 'preview';

        $iReply = CopixRequest::getInt('reply');
        $iForward = CopixRequest::getInt('forward');

        $destTxt = $dest;
        $destTxt = str_replace(array(" "), "", $destTxt);
        $destTxt = str_replace(array(",", ";"), ",", $destTxt);
        $destin = array_unique(explode(",", $destTxt));

        $fromId = _currentUser()->getId();
        $errors = array();

        if (!$dest)
            $errors[] = CopixI18N::get('minimail.error.typeDest');
        if (!$title)
            $errors[] = CopixI18N::get('minimail.error.typeTitle');
        if (!$message)
            $errors[] = CopixI18N::get('minimail.error.typeMessage');
        if (!$format)
            $errors[] = CopixI18N::get('minimail.error.typeFormat');

        $tabDest = array();
        // On v�rifie que les destinataires existent
        while (list(, $login) = each($destin)) {
            if (!$login)
                continue;
            $userInfo = Kernel::getUserInfo("LOGIN", $login, array('strict' => true));
            //print_r("login=$login");
            //print_r($userInfo);
            if (!$userInfo)
                $errors[] = CopixI18N::get('minimail.error.badDest', array($login));
            elseif ($userInfo["user_id"] == $fromId)
                $errors[] = CopixI18N::get('minimail.error.writeHimself');
            else {
                $droits = Kernel::getUserInfoMatrix($userInfo);
                if ($droits['communiquer']) {
                    $tabDest[$userInfo["user_id"]] = $userInfo["user_id"];
                } else {
                    $errors[] = CopixI18N::get('minimail.error.cannotWrite', array($login));
                }
            }
        }

        // On v�rifie les pi�ces jointes

        CopixConfig::get('minimail|attachment_size');
        //print_r($_FILES);
        for ($i = 1; $i <= 3; $i++) {
            if (isset($_FILES['attachment' . $i]) && !is_uploaded_file($_FILES['attachment' . $i]['tmp_name'])) {
                switch ($_FILES['attachment' . $i]['error']) {
                    case 0: //no error; possible file attack!
                        $errors[] = CopixI18N::get('minimail|minimail.error.upload_default', $i);
                        break;
                    case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
                        $errors[] = CopixI18N::get('minimail|minimail.error.upload_toobig', $i);
                        break;
                    case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
                        $errors[] = CopixI18N::get('minimail|minimail.error.upload_toobig', $i);
                        break;
                    case 3: //uploaded file was only partially uploaded
                        $errors[] = CopixI18N::get('minimail|minimail.error.upload_partial', $i);
                        break;
                    case 4: //no file was uploaded
                        break;
                    default:
                        $errors[] = CopixI18N::get('minimail|minimail.error.upload_default', $i);
                        break;
                }
            }
        }

        if (!$errors) {

            if (!$errors && $go == 'save') {
                $serv = CopixClassesFactory::create("MinimailService");
                $send = $serv->sendMinimail($title, $message, $fromId, $tabDest, $format);
                if (!$send)
                    $errors[] = CopixI18N::get('minimail.error.send');
            }


            if (!$errors && $go == 'save') {

                // Reponse ou forward ?
                if ($iReply) {
                    // On verifie qu'on est destinataire
                    if (($inDest = _ioDAO('minimail_to')->selectDestFromIdAndToUser($iReply, $fromId))) {
                        _doQuery("UPDATE module_minimail_to SET is_replied=1 WHERE id=:id", array(':id' => $inDest->id2));
                    }
                } elseif ($iForward) {
                    $message = _ioDAO('minimail_from')->get($iForward);
                    // Si on etait l'expediteur
                    if ($message && $message->from_id == $fromId) {
                        _doQuery("UPDATE module_minimail_from SET is_forwarded=1 WHERE id=:id", array(':id' => $iForward));
                        // Si on etait destinataire
                    } elseif ($message && ($inDest = _ioDAO('minimail_to')->selectDestFromIdAndToUser($iForward, $fromId))) {
                        _doQuery("UPDATE module_minimail_to SET is_forwarded=1 WHERE id=:id", array(':id' => $inDest->id2));
                    }
                }

                // Ajout des pieces jointes
                $attachments = array();
                $dataPath = realpath("../var/data");

                for ($i = 1; $i <= 3; $i++) {
                    if (isset($_FILES["attachment" . $i]) && isset($_FILES["attachment" . $i]["name"]) && $_FILES["attachment" . $i]["name"]) {
                        $name = $send . "_" . $_FILES["attachment" . $i]["name"];
                        $uploadFrom = $_FILES["attachment" . $i]["tmp_name"];
                        $uploadTo = $dataPath . "/minimail/" . ($name);
                        if (move_uploaded_file($uploadFrom, $uploadTo))
                            $attachments[] = ($name);
                        else
                            $errors[] = CopixI18N::get('minimail.error.send', array($i));
                    }
                }
                if (count($attachments) > 0) {
                    $DAOminimail_from = CopixDAOFactory::create("minimail_from");
                    $mp = $DAOminimail_from->get($send);
                    $mp->attachment1 = (isset($attachments[0])) ? $attachments[0] : NULL;
                    $mp->attachment2 = isset($attachments[1]) ? $attachments[1] : NULL;
                    $mp->attachment3 = isset($attachments[2]) ? $attachments[2] : NULL;
                    $DAOminimail_from->update($mp);
                }
                //    update_message_pj ($res, $pj[0], $pj[1], $pj[2]);
                if (!$errors) {
                    $urlReturn = CopixUrl::get('|getListSend');
                    return new CopixActionReturn(COPIX_AR_REDIRECT, $urlReturn);
                }
            }
        }

        //_dump($message);

        return CopixActionGroup::process('minimail|minimail::getNewForm', array('dest' => $dest, 'title' => $title, 'message' => $message, 'format' => $format, 'errors' => $errors, 'preview' => (($go == 'save') ? 0 : 1), 'reply' => $iReply, 'forward' => $iForward));

        //$url_return = CopixConfig::get('minimail|afterMsgSend');
        //$url_return = CopixUrl::get('minimail||getListSend');
    }

    /**
     * Suppression de minimails
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @param array $messages Tableau avec les Ids des minimails � supprimer (les Ids doivent �tre en valeurs du tableau)
     * @param string $mode Mode d'affichage des messages ("recv" si on supprime des messages re�us, "send" si c'est des messages envoy�s)
     * @todo En cas de suppression, voir pour supprimer les pi�ces jointes
     */
    public function doDelete()
    {
        $messages = _request("messages") ? _request("messages") : NULL;
        $mode = _request("mode") ? _request("mode") : NULL;
        //print_r2($messages);
        $daoMinimailFrom = CopixDAOFactory::create("minimail_from");
        $daoMinimailTo = CopixDAOFactory::create("minimail_to");
        foreach ($messages as $msg) {
            // TODO quid pi�ces jointes ?
            if ($mode == "recv") {    // Message re�u
                $mp = $daoMinimailTo->get($msg);
                $mp->is_deleted = 1;
                $daoMinimailTo->update($mp);
            } elseif ($mode == "send") {    // Message envoy�
                $mp = $daoMinimailFrom->get($msg);
                $mp->is_deleted = 1;
                $daoMinimailFrom->update($mp);
            }
        }
        $actionNext = ($mode == 'recv') ? 'getListRecv' : 'getListSend';
        $urlReturn = CopixUrl::get('minimail||' . $actionNext);
        return new CopixActionReturn(COPIX_AR_REDIRECT, $urlReturn);
    }

    /**
     * T�l�chargement d'une pi�ce jointe (download)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @param string $file Nom du fichier � t�l�charger
     * @todo V�rifier les droits par rapport au minimail contenant cette pi�ce jointe
     */
    public function downloadAttachment()
    {
        $minimailService = & CopixClassesFactory::Create('minimail|minimailService');
        $malleService = & CopixClassesFactory::Create('malle|malleService');

        $file = _request("file") ? _request("file") : NULL;
        $fullFile = realpath("../var/data") . "/minimail/" . ($file);
        $errors = array();
        if (!$file || !file_exists($fullFile))
            $errors[] = CopixI18N::get('minimail.error.noFile');
        if ($errors) {
            $urlReturn = CopixUrl::get('minimail||getListRecv');
            return new CopixActionReturn(COPIX_AR_REDIRECT, $urlReturn);
        }
        $fileDl = $minimailService->getAttachmentName($file);

        return _arFile($fullFile, array('filename' => $fileDl, 'content-type' => $malleService->getMimeType($fileDl)));
    }

    /**
     * Affichage de la pr�visualisation d'une pi�ce jointe sous forme de vignette (si c'est une image)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/18
     * @param string $file Nom du fichier � t�l�charger
     * @todo Tester que la pi�ce jointe est bien attach�e � un message dont l'utilisateur est destinataire ou exp�diteur
     */
    public function previewAttachment()
    {
        $file = _request("file") ? _request("file") : "";
        $fullFile = realpath("../var/data") . "/minimail/" . ($file);
        $errors = array();

        if (!$file || !file_exists($fullFile))
            $errors[] = CopixI18N::get('minimail.error.noFile');

        if (!$errors) {
            if ($size = getimagesize($fullFile)) {
                readfile($fullFile);
            }
        }

        return new CopixActionReturn(COPIX_AR_NONE, 0);
    }

    /**
     * Téléchargement d'une pièce jointe dans un classeur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/06/21
     * @param integer $id Id du minimail de départ
     */
    public function attachmentToClasseur()
    {
        //_dump($_POST);

        $this->addJs('js/iconito/module_classeur.js');
        $this->addJs('js/iconito/module_minimail.js');
        $this->addCss('styles/module_classeur.css');

        _classInclude('classeur|classeurService');
        _classInclude('kernel|Request');

        $idUser = _currentUser()->getId();
        $idMessage = _request("id");
        $files = _request('files', array());
        $destination = _request('destination');
        $errors = array();

        $daoFrom = _ioDAO("minimail|minimail_from");
        $daoTo = CopixDAOFactory::create("minimail_to");

        $message = $daoFrom->getMessage($idMessage);

        $canMake = $isRecv = $isSend = false;

        if ($message && $message->from_id == $idUser) { // Message qu'il a envoyé
            $canMake = $isSend = true;
        } else { // Il en est peut-être destinataire
            $canMake = $isRecv = $daoTo->selectDestFromIdAndToUser($idMessage, $idUser); // Test s'il est dans les destin
        }

        if (!$canMake) {
            return CopixActionGroup::process('genericTools|Messages::getError', array('message' => CopixI18N::get('minimail.error.cantDisplay'), 'back' => CopixUrl::get('minimail||')));
        }

        $menu = array();
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_recv'), 'url' => CopixUrl::get('minimail||getListRecv'), 'current' => $isRecv);
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_send'), 'url' => CopixUrl::get('minimail||getListSend'), 'current' => $isSend);
        $menu[] = array('txt' => CopixI18N::get('minimail.mess_write'), 'url' => CopixUrl::get('minimail||getNewForm'));

        $ppo = new CopixPPO ();

        $ppo->TITLE_PAGE = $message;
        $ppo->MENU = $menu;
        $ppo->message = $message;

        //_dump(Request::isXmlHttpRequest());

        if (Request::isPostMethod()) {

            $error = $success = array();

            if (!$files) {
                $error[] = CopixI18N::get('minimail.attachmentToClasseur.error.noFiles');
            }

            if ($destination) {
                list($ppo->destinationType, $ppo->destinationId) = explode('-', $destination);
                if ('classeur' == $ppo->destinationType) {
                    $rClasseur = _ioDAO('classeur|classeur')->get($ppo->destinationId);
                }
                if ('dossier' == $ppo->destinationType) {
                    if ($rDossier = _ioDAO('classeur|classeurdossier')->get($ppo->destinationId)) {
                        $rClasseur = _ioDAO('classeur|classeur')->get($rDossier->classeur_id);
                    }
                }
            }

            if (!$destination || !$rClasseur) {
                $error[] = CopixI18N::get('classeur|classeur.error.noDestination');
            }


            if ($error) {
                $ppo->error = $error;
                return _arPPO ($ppo, array ('template'=>'attachmentToClasseur.tpl', 'mainTemplate'=>'main|main_popup.php'));
            }

            //_dump($destination);
            //_dump($rClasseur);

            $dir = realpath('./static/classeur').'/'.$rClasseur->id.'-'.$rClasseur->cle.'/';
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            foreach ($files as $file) {

                $fichierPhysique = realpath("../var/data") . "/minimail/" . $file;
                $nomFichierPhysique = $file;

                $fichier = _record('classeur|classeurfichier');

                $fichier->classeur_id   = $rClasseur->id;
                $fichier->dossier_id    = (isset($rDossier) && $rDossier) ? $rDossier->id : 0;
                $fichier->titre         = MinimailService::getAttachmentName($file);
                $fichier->fichier       = $nomFichierPhysique;
                $fichier->taille        = filesize($fichierPhysique);
                $fichier->type          = strtoupper(substr(strrchr($nomFichierPhysique, '.'), 1));
                $fichier->cle           = classeurService::createKey();
                $fichier->date_upload   = date('Y-m-d H:i:s');
                $fichier->user_type     = _currentUser()->getExtra('type');
                $fichier->user_id       = _currentUser()->getExtra('id');

                _ioDAO('classeur|classeurfichier')->insert($fichier);

                if ($fichier->id > 0) {
                    $nomClasseur  = $rClasseur->id.'-'.$rClasseur->cle;
                    $nomFichier   = $fichier->id.'-'.$fichier->cle;
                    $extension    = strtolower(strrchr($nomFichierPhysique, '.'));

                    if (copy($fichierPhysique, $dir.$fichier->id.'-'.$fichier->cle.$extension)) {
                        $success[] = MinimailService::getAttachmentName($file);
                    } else {
                        $error[] = CopixI18N::get('minimail.attachmentToClasseur.error.moved', array(MinimailService::getAttachmentName($file)));
                    }
                } else {
                    $error[] = CopixI18N::get('minimail.attachmentToClasseur.error.creation', array(MinimailService::getAttachmentName($file)));
                }

            }

            if (count($success) > 0) {
                $dest = $rClasseur;
                if (isset($rDossier) && $rDossier) {
                    $dest .= ' / '.$rDossier;
                }
                if (1 == count($success)) {
                    Kernel::setFlashMessage('success', CopixI18N::get('minimail.attachmentToClasseur.moved_1', array(
                        implode(', ', $success),
                        $dest,
                    )));
                } else {
                    Kernel::setFlashMessage('success', CopixI18N::get('minimail.attachmentToClasseur.moved_N', array(
                        implode(', ', $success),
                        $dest,
                    )));
                }
            }
            if ($error) {
                Kernel::setFlashMessage('error', implode('<br />', $error));
            }

            $ppo->ok = 1;
            //echo 'OK';
            //return _arNone();

            //return new CopixActionReturn(COPIX_AR_REDIRECT, CopixUrl::get('minimail||getMessage', array('id' => $idMessage)));


        }


        return _arPPO ($ppo, array ('template'=>'attachmentToClasseur.tpl', 'mainTemplate'=>'main|main_popup.php'));







    }

}

