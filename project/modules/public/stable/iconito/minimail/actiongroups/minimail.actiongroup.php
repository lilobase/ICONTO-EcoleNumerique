<?php
/**
 * Actiongroup du module Minimail
 * 
 * @package Iconito
 * @subpackage	Minimail
 */
class ActionGroupMinimail extends CopixActionGroup {
		
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}

	
   /**
   * Affiche la liste des messages reçus pour l'utilisateur connecté
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
   */
   function getListRecv () {
	 
		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

	 	$dao = _dao("minimail_to");
		
		//$userId = _currentUser ()->getId();
		$userId = _currentUser ()->getId();
		
		$page = _request("page") ? _request("page") : 1;
		$offset = ($page-1)*CopixConfig::get ('minimail|list_nblines');
		$messagesAll = $dao->getListRecvAll($userId);
		$nbPages = ceil(count($messagesAll) / CopixConfig::get ('minimail|list_nblines'));
		
		//die("a");
		$messages = $dao->getListRecv($userId,$offset,CopixConfig::get ('minimail|list_nblines'));	

		// Infos des utilisateurs
		foreach ($messages as $k=>$topic) {
			if ($userInfo = Kernel::getUserInfo("ID", $messages[$k]->from_id)) {
				//print_r($userInfo);
				$messages[$k]->from = $userInfo;
				$messages[$k]->from_id_infos = $userInfo["prenom"]." ".$userInfo["nom"]." (".$userInfo["login"].")";
			}
		}

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('minimail.mess_recv'));
		$menu = '';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListRecv').'">'.CopixI18N::get ('minimail.mess_recv').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListSend').'">'.CopixI18N::get ('minimail.mess_send').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getNewForm').'">'.CopixI18N::get ('minimail.mess_write').'</a>';
		$tpl->assign ('MENU', $menu );

		$tplListe = & new CopixTpl ();
		$tplListe->assign ('list', $messages);
		$tplListe->assign ('reglettepages', CopixZone::process ('kernel|reglettepages', array('page'=>$page, 'nbPages'=>$nbPages, 'url'=>CopixUrl::get('minimail||getListRecv'))));
		$result = $tplListe->fetch("getlistrecv.tpl");

		$tpl->assign ("MAIN", $result);
		
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
	
   /**
   * Affiche la liste des messages envoyés pour l'utilisateur connecté
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
   */
	function getListSend () {
	 
		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

	 	$daoFrom = CopixDAOFactory::create("minimail_from");
    $daoTo = CopixDAOFactory::create("minimail_to");
		$userId = _currentUser ()->getId();
		
		$page = _request("page") ? _request("page") : 1;
		$offset = ($page-1)*CopixConfig::get ('minimail|list_nblines');
		$messagesAll = $daoFrom->getListSendAll($userId);
		$nbPages = ceil(count($messagesAll) / CopixConfig::get ('minimail|list_nblines'));

		$messages = $daoFrom->getListSend($userId,$offset,CopixConfig::get ('minimail|list_nblines'));


		// Infos des utilisateurs
		foreach ($messages as $k=>$null) {
      $dest = $daoTo->selectDestFromId ($messages[$k]->id);
			foreach ($dest as $j=>$null) {
				//print_r($dest[$j]->to_id);
				$userInfo = Kernel::getUserInfo("ID", $dest[$j]->to_id);
				$dest[$j]->to = $userInfo;
				$dest[$j]->to_id_infos = $userInfo["prenom"]." ".$userInfo["nom"]." (".$userInfo["login"].")";
			}
			$messages[$k]->destin = $dest;
		}

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('minimail.mess_send'));
		$menu = '';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListRecv').'">'.CopixI18N::get ('minimail.mess_recv').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListSend').'">'.CopixI18N::get ('minimail.mess_send').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getNewForm').'">'.CopixI18N::get ('minimail.mess_write').'</a>';
		$tpl->assign ('MENU', $menu );
		
		$tplListe = & new CopixTpl ();
		$tplListe->assign ('list', $messages);
		$tplListe->assign ('reglettepages', CopixZone::process ('kernel|reglettepages', array('page'=>$page, 'nbPages'=>$nbPages, 'url'=>CopixUrl::get('minimail||getListSend'))));
		
		$result = $tplListe->fetch("getlistsend.tpl");

		$tpl->assign ("MAIN", $result);
		
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
	
   /**
   * Affiche un minimail en détail
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param integer $id Id du minimail
   */
	function getMessage () {
	 	
		$MinimailService = & CopixClassesFactory::Create ('minimail|MinimailService');
		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

    // 2 DAO -> 2 assign
		
		$idUser = _currentUser ()->getId();
		$idMessage = _request("id");
		$errors = array();
		
		$daoFrom = CopixDAOFactory::create("minimail_from");
    $daoTo = CopixDAOFactory::create("minimail_to");
		
		$message = $daoFrom->getMessage($idMessage);
		$dest = $daoTo->selectDestFromId ($idMessage);
		
		//print_r2($dest);
		
		//print_r($message[0]);
		
		$message[0]->prev = NULL;
		$message[0]->next = NULL;
		if ($message[0]->from_id == $idUser) {	// Message qu'il a envoyé
			$message[0]->type="send";
			$prev = $daoFrom->getFromPrevMessage($message[0]->date_send,$idUser);
			if ($prev)
				$message[0]->prev = $prev->id;
			$next = $daoFrom->getFromNextMessage($message[0]->date_send,$idUser);
			if ($next)
				$message[0]->next = $next->id;
		} else {	// Il en est peut-être destinataire
			$isDest = $daoTo->selectDestFromIdAndToUser ($idMessage, $idUser);	// Test s'il est dans les destin
			if ($isDest) {
				$serv = CopixClassesFactory::create("MinimailService");
				$serv->markMinimailAsRead ($dest, $idUser);
				$message[0]->type="recv";
				$prev = $daoTo->getToPrevMessage($message[0]->date_send,$idUser);
				if ($prev)
					$message[0]->prev = $prev->id;
				$next = $daoTo->getToNextMessage($message[0]->date_send,$idUser);
				if ($next)
					$message[0]->next = $next->id;
			} else {	// Il tente d'afficher un message qu'il n'a pas envoyé ni reçu !
				$errors[] = CopixI18N::get ('minimail.error.cantDisplay');
			}
		}
		
		if ($errors) {
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('minimail||')));
		} else {
			
			$userInfo = Kernel::getUserInfo("ID", $message[0]->from_id);
			$message[0]->from = $userInfo;
			$message[0]->from_id_infos = $userInfo["prenom"]." ".$userInfo["nom"]." (".$userInfo["login"].")";
			foreach ($dest as $j=>$null) {
				//print_r($dest[$j]->to_id);
				$userInfo = Kernel::getUserInfo("ID", $dest[$j]->to_id);
				$dest[$j]->to = $userInfo; 
				$dest[$j]->to_id_infos = $userInfo["prenom"]." ".$userInfo["nom"]." (".$userInfo["login"].")";
			}
      
      // Avatar de l'expéditeur
			$avatar = Prefs::get('prefs', 'avatar', $message[0]->from_id);
			$message[0]->avatar = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
      
			$tpl = & new CopixTpl ();
			$tpl->assign ('TITLE_PAGE', $message[0]->title);
		$menu = '';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListRecv').'">'.CopixI18N::get ('minimail.mess_recv').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListSend').'">'.CopixI18N::get ('minimail.mess_send').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getNewForm').'">'.CopixI18N::get ('minimail.mess_write').'</a>';
		$tpl->assign ('MENU', $menu );
			
			$message[0]->attachment1IsImage = $MinimailService->isAttachmentImage ($message[0]->attachment1);
			$message[0]->attachment2IsImage = $MinimailService->isAttachmentImage ($message[0]->attachment2);
			$message[0]->attachment3IsImage = $MinimailService->isAttachmentImage ($message[0]->attachment3);
			$message[0]->attachment1Name = $MinimailService->getAttachmentName ($message[0]->attachment1);
			$message[0]->attachment2Name = $MinimailService->getAttachmentName ($message[0]->attachment2);
			$message[0]->attachment3Name = $MinimailService->getAttachmentName ($message[0]->attachment3);
			//print_r($message);

			$tplListe = & new CopixTpl ();
			$tplListe->assign ('message', $message[0]);
			$tplListe->assign ('dest', $dest);
			$result = $tplListe->fetch('getmessage.tpl');
			$tpl->assign ('MAIN', $result);
			
			$plugStats = CopixPluginRegistry::get ("stats|stats");
			$plugStats->setParams(array('objet_a'=>$idMessage));

			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		}
			
	}
	
	
   /**
   * Formulaire d'écriture d'un minimail
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @see doSend()
	 * @param integer $id Id du minimail si c'est une réponse à ce minimail
	 * @param string $title Titre du minimail (si formulaire soumis)
	 * @param string $login Logins du(des) destinataire(s) (si formulaire soumis)
	 * @param string $dest Logins du(des) destinataire(s) (si formulaire soumis)
	 * @param string $message Corps du minimail (si formulaire soumis)
	 * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
   */
	function processGetNewForm () {

		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

		$tpl = & new CopixTpl ();
		
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('minimail.mess_write'));
		$menu = '';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListRecv').'">'.CopixI18N::get ('minimail.mess_recv').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getListSend').'">'.CopixI18N::get ('minimail.mess_send').'</a> :: ';
		$menu.= '<a href="'.CopixUrl::get ('minimail||getNewForm').'">'.CopixI18N::get ('minimail.mess_write').'</a>';
		$tpl->assign ('MENU', $menu );


		$idUser = _currentUser ()->getId();
		$idMessage = _request("id") ? _request("id") : NULL;
		
		$title = _request("title") ? _request("title") : NULL;
		$login = _request("login") ? _request("login") : NULL;
		$dest = _request("dest") ? _request("dest") : $login;
		$message = _request("message") ? _request("message") : NULL;
		$format = CopixConfig::get ('minimail|default_format');
		
		$preview = _request("preview") ? _request("preview") : 0;
		
		if ($idMessage) {	// Tentative de réponse à un message
			$daoFrom = CopixDAOFactory::create("minimail_from");
    	$daoTo = CopixDAOFactory::create("minimail_to");
		
			$message = $daoFrom->getMessage($idMessage);
			$destin = $daoTo->selectDestFromId ($idMessage);
			
			$serv = CopixClassesFactory::create("MinimailService");
			if ($serv->canViewMessage ($message, $destin, $idUser)) {
				$format = $message[0]->format;
				$answer = $serv->constructAnswer ($message, $destin, $idUser, $format);
				$dest = $answer["dest"];
				$title = $answer["title"];
				$message = $answer["message"];
			}
		}

		$tplForm = & new CopixTpl ();
		$tplForm->assign ("dest", $dest);
		$tplForm->assign ("title", $title);
		$tplForm->assign ("message", $message);
		$tplForm->assign ("format", $format);
		$tplForm->assign ("preview", $preview);
		$tplForm->assign ("errors", (_request("errors") ? _request("errors") : ""));
		$tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'height'=>200)));
		
		$tplForm->assign ('linkpopup', CopixZone::process ('annuaire|linkpopup', array('field'=>'dest')));
		$tplForm->assign ("attachment_size", CopixConfig::get ('minimail|attachment_size') );
		$result = $tplForm->fetch("writeform.tpl");

		$tpl->assign ("MAIN", $result);
	
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}
	
	
   /**
   * Soumission du formulaire d'écriture d'un minimail (envoie le minimail)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @see getNewForm()
	 * @param string $dest Logins du(des) destinataire(s)
	 * @param string $title Titre du minimail
	 * @param string $message Corps du minimail
	 * @param string $go Forme de soumission : preview (prévisualiser) ou send (enregistrer)
   */
	function doSend () {
		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

		$dest = _request("dest") ? _request("dest") : "";
		$title = _request("title") ? _request("title") : "";
		$message = _request("message") ? _request("message") : "";
		$format = _request("format") ? _request("format") : "";

		$go = _request("go") ? _request("go") : 'preview';

		$destTxt = $dest;
		$destTxt = str_replace(array(" "), "", $destTxt);
		$destTxt = str_replace(array(",",";"), ",", $destTxt);
		$destin = array_unique(explode (",", $destTxt));
		
		$fromId = _currentUser ()->getId();
		$errors = array();
		
		if (!$dest)
			$errors[] = CopixI18N::get ('minimail.error.typeDest');
		if (!$title)
			$errors[] = CopixI18N::get ('minimail.error.typeTitle');
		if (!$message)
			$errors[] = CopixI18N::get ('minimail.error.typeMessage');
		if (!$format)
			$errors[] = CopixI18N::get ('minimail.error.typeFormat');

		$tabDest = array();
		// On vérifie que les destinataires existent
		while (list(,$login) = each ($destin)) {
			if (!$login) continue;
			$userInfo = Kernel::getUserInfo("LOGIN", $login);
			//print_r("login=$login");
			//print_r($userInfo);
			if (!$userInfo)
				$errors[] = CopixI18N::get ('minimail.error.badDest', array($login));
			elseif ($userInfo["user_id"] == $fromId)
				$errors[] = CopixI18N::get ('minimail.error.writeHimself');
			else {
				$droits = Kernel::getUserInfoMatrix ($userInfo);
				if (!$droits['communiquer'])
					$errors[] = CopixI18N::get ('minimail.error.cannotWrite', array($login));
				else
					$tabDest[$userInfo["user_id"]] = $userInfo["user_id"];
			}
		}
		
		// On vérifie les pièces jointes
		
		CopixConfig::get ('minimail|attachment_size');
		//print_r($_FILES);
		for ($i=1 ; $i<=3 ; $i++) {
			if( isset($_FILES['attachment'.$i]) && ! is_uploaded_file( $_FILES['attachment'.$i]['tmp_name'] ) ) {
				switch( $_FILES['attachment'.$i]['error'] ) {
					case 0: //no error; possible file attack!
						$errors[] = CopixI18N::get ('minimail|minimail.error.upload_default', $i);
						break;
					case 1: //uploaded file exceeds the upload_max_filesize directive in php.ini
						$errors[] = CopixI18N::get ('minimail|minimail.error.upload_toobig', $i);
						break;
					case 2: //uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form
						$errors[] = CopixI18N::get ('minimail|minimail.error.upload_toobig', $i);
						break;
					case 3: //uploaded file was only partially uploaded
						$errors[] = CopixI18N::get ('minimail|minimail.error.upload_partial', $i);
						break;
					case 4: //no file was uploaded
						break;
					default:
						$errors[] = CopixI18N::get ('minimail|minimail.error.upload_default', $i);
						break;
				}
			}
		}
		
		if (!$errors) {
		
			//die();
			
			if (!$errors && $go=='save') {
				$serv = CopixClassesFactory::create("MinimailService");
				$send = $serv->sendMinimail ($title, $message, $fromId, $tabDest, $format);
				if (!$send)
					$errors[] = CopixI18N::get ('minimail.error.send');
			}

      // Ajout des pièces jointes
			if (!$errors && $go=='save') {
				$attachments = array();
				$dataPath = realpath("../var/data");
				
        for ($i=1 ; $i<=3 ; $i++) {
	        if ($_FILES["attachment".$i]["name"]) {
	          $name = $send."_".$_FILES["attachment".$i]["name"];  
            $uploadFrom = $_FILES["attachment".$i]["tmp_name"];
            $uploadTo = $dataPath."/minimail/".($name); 
            if(move_uploaded_file($uploadFrom, $uploadTo))
							$attachments[] = ($name);
            else
							$errors[] = CopixI18N::get ('minimail.error.send', array($i));
          }
        }
        if (count($attachments)>0) {
							$DAOminimail_from = CopixDAOFactory::create("minimail_from");
							$mp = $DAOminimail_from->get($send);
							$mp->attachment1 = (isset($attachments[0])) ? $attachments[0] : NULL;
							$mp->attachment2 = isset($attachments[1]) ? $attachments[1] : NULL;
							$mp->attachment3 = isset($attachments[2]) ? $attachments[2] : NULL;
							$DAOminimail_from->update($mp);
				}
        //    update_message_pj ($res, $pj[0], $pj[1], $pj[2]);
				if (!$errors) {
					$urlReturn = CopixUrl::get ('|getListSend');
					return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
				}
			}
			
		}
		
		return CopixActionGroup::process ('minimail|minimail::getNewForm', array ('dest'=>$dest, 'title'=>$title, 'message'=>$message, 'format'=>$format, 'errors'=>$errors, 'preview'=>(($go=='save')?0:1)));
		
		//$url_return = CopixConfig::get('minimail|afterMsgSend');
		//$url_return = CopixUrl::get('minimail||getListSend');

		
	}
	
	
   /**
   * Suppression de minimails
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param array $messages Tableau avec les Ids des minimails à supprimer (les Ids doivent être en valeurs du tableau)
	 * @param string $mode Mode d'affichage des messages ("recv" si on supprime des messages reçus, "send" si c'est des messages envoyés)
	 * @todo En cas de suppression, voir pour supprimer les pièces jointes
   */
	function doDelete () {

		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

		$messages = _request("messages") ? _request("messages") : NULL;
		$mode = _request("mode") ? _request("mode") : NULL;
		//print_r2($messages);
		$daoMinimailFrom 	= CopixDAOFactory::create("minimail_from");
   	$daoMinimailTo 		= CopixDAOFactory::create("minimail_to");
		foreach($messages as $msg) {
			// TODO quid pièces jointes ?
	  	if ($mode == "recv") {    // Message reçu
				$mp = $daoMinimailTo->get($msg);
				$mp->is_deleted = 1;
				$daoMinimailTo->update($mp);
  		} elseif ($mode == "send") {    // Message envoyé
				$mp = $daoMinimailFrom->get($msg);
				$mp->is_deleted = 1;
				$daoMinimailFrom->update($mp);
	  	}
		}
		$actionNext = ($mode=='recv') ? 'getListRecv' : 'getListSend';
		$urlReturn = CopixUrl::get ('minimail||'.$actionNext);
		return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		
	}
	

   /**
   * Téléchargement d'une pièce jointe (download)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param string $file Nom du fichier à télécharger
	 * @todo Vérifier les droits par rapport au minimail contenant cette pièce jointe
   */
	function downloadAttachment () {
		$minimailService = & CopixClassesFactory::Create ('minimail|minimailService');
		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

		$file = _request("file") ? _request("file") : NULL;
		$fullFile = realpath("../var/data")."/minimail/".($file);
		$errors = array();
		if (!$file || !file_exists($fullFile))
			$errors[] = CopixI18N::get ('minimail.error.noFile');
		if ($errors) {
			$urlReturn = CopixUrl::get ('minimail||getListRecv');
			return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
		}
		$fileDl = $minimailService->getAttachmentName ($file);

		return _arFile ($fullFile, array ('filename'=>$fileDl, 'content-type'=>CopixMIMETypes::getFromExtension ($fileDl)));
		
	}
	
   /**
   * Affichage de la prévisualisation d'une pièce jointe sous forme de vignette (si c'est une image)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param string $file Nom du fichier à télécharger
	 * @todo Tester que la pièce jointe est bien attachée à un message dont l'utilisateur est destinataire ou expéditeur
   */
	function previewAttachment () {

		if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

		$file = _request("file") ? _request("file") : "";
		$fullFile = realpath("../var/data")."/minimail/".($file);
		$errors = array();
		
		if (!$file || !file_exists($fullFile))
			$errors[] = CopixI18N::get ('minimail.error.noFile');
			
		if (!$errors) {
			if ($size = getimagesize ($fullFile)) {
				readfile($fullFile);
			}
		}
		
		return new CopixActionReturn (COPIX_AR_NONE, 0);
	}
	

}

?>
