<?php

/**
 * Fonctions diverses du module Minimail
 * 
 * @package Iconito
 * @subpackage	Minimail
 */
 
require_once (COPIX_UTILS_PATH.'CopixEMailer.class.php');

class MinimailService {

	/**
	 * Envoie un minimail
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param string title Titre du minimail
	 * @param string message Corps du minimail
	 * @param integer from_id Id utilisateur de l'expéditeur
	 * @param array destin Id tableau avec les destinataires (clé = id user)
	 * @return mixed Id du message créé ou NULL si erreur
	 */
	function sendMinimail ($title, $message, $from_id, $destin, $format) {
	
		$res = NULL;
		
		if (1) {
			
			$DAOminimail_from = _dao("minimail|minimail_from");
			$DAOminimail_to = _dao("minimail|minimail_to");
			
			
			$newMp = _record("minimail|minimail_from");
			$newMp->title = $title;
			$newMp->message = $message;
			$newMp->format = $format;
			$newMp->date_send = date("Y-m-d H:i:s");
			$newMp->from_id = $from_id;
			$newMp->is_deleted = 0;
			$DAOminimail_from->insert ($newMp);
			
			if ($newMp->id!==NULL) {
				//print_r($newMp);
				// On parcourt chaque destinataire
				while (list($to_id,) = each ($destin)) {
					// print_r("to_id=$to_id / to_login=$to_login");
					$newDest = _record("minimail|minimail_to");
					$newDest->id_message = $newMp->id;
					$newDest->to_id = $to_id;
					$newDest->date_read = 0;
					$newDest->is_read = 0;
					$newDest->is_replied = 0;
					$newDest->is_deleted = 0;
					$DAOminimail_to->insert ($newDest);

					// ======= Alerte mail ===============
          // On vérifie que l'envoi de mails est activé, qu'un serveur SMTP est configuré, que le destinataire a coché l'option "etre prévenu par mail" et qu'il a renseigné un mail
					if ($newDest->id2 && CopixConfig::get('|mailEnabled')==1 && CopixConfig::get('|mailSmtpHost')) {
  					$prefs = Prefs::getPrefs ($to_id);
            if (isset($prefs['prefs']['alerte_mail_email']) && isset($prefs['minimail']['alerte_minimail']) && $prefs['prefs']['alerte_mail_email'] && $prefs['minimail']['alerte_minimail']==1) {
  						$userInfoFrom = Kernel::getUserInfo("ID", $from_id);
	  					//print_r($userInfoFrom);
		  				$to = $prefs['prefs']['alerte_mail_email'];
			  			$auteur = $userInfoFrom['prenom'].' '.$userInfoFrom['nom'].' ('.$userInfoFrom['login'].')';
						  $subject = CopixI18N::get ('minimail|minimail.mail.alert.subject', array($auteur));
						  $message = str_replace('<br />', "\n", CopixI18N::get ('minimail|minimail.mail.alert.body', array($auteur, CopixUrl::get ('minimail||getMessage', array('id'=>$newMp->id)), CopixUrl::get ())));
						  $from = CopixConfig::get ('default|mailFrom');
						  $fromName = CopixConfig::get ('default|mailFromName');
							$cc = $cci = '';
						  $monMail = & new CopixTextEMail ($to, $cc, $cci, $subject, $message);
						  $send = $monMail->send ($from, $fromName);
            }
					}
					// ======= Fin alerte mail ===============
				}
				$res = $newMp->id;
				if ($res) {
					$plugStats = CopixPluginRegistry::get ("stats|stats");
					$plugStats->setParams(array('module'=>'minimail', 'action'=>'sendMinimail', 'objet_a'=>$res));
				}
			}
		}
		return $res;
	}
	
	/**
	 * Marque un minimail comme lu (quand il est ouvert par le destinataire)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param mixed dest Les destinataires (issus du DAO minimail_to)
	 * @param integer id_user Id utilisateur de celui qui lit le minimail
	 */
	function markMinimailAsRead ($dest, $id_user) {
		foreach ($dest as $d) {
			if ($d->to_id==$id_user && $d->is_read==0) {	// L'usager figure bien dans les destinataires
				$DAOminimail_from = _dao("minimail|minimail_to");
				$mp = $DAOminimail_from->get($d->id2);
				$mp->is_read = 1;
				$mp->date_read = date("Y-m-d H:i:s");
				$DAOminimail_from->update($mp);
			}
		}
	}
	

	/**
	 * Teste si un utilisateur peut afficher un message
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/18
	 * @param object Record du message
	 * @param mixed destin DAO des destinataires
	 * @param integer idUser Id utilisateur de celui qui souhaite lire le minimail
	 * @return bool true s'il a le droit (il en l'expéditeur ou le destinataire), false sinon
	 */
	function canViewMessage ($message, $destin, $idUser) {
		$res = false;
		if ($message->from_id == $idUser) {	// Message qu'il a envoyé
			$res = true;
		} else {
			foreach($destin as $d) {
				$res = ($d->to_id==$idUser);
				if($res) break;
			}
		}
		return $res;
	}
	

	/**
	 * Construit la réponse à un message
	 *
	 * A partir d'un message, construit le titre, le message et les destinataires si un usager fait "Répondre".
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/19
	 * @param object $message Record du message original
	 * @param mixed $destin DAO des destinataires
	 * @param integer $idUser Id utilisateur de celui qui souhaite répondre
	 * @param string $format Format souhaite
	 * @param integer $iAll Si on veut repondre a tous. Sinon, on ne repond qu'a l'expediteur
	 * @return array tableau : array(title=>"Re : le titre", dest=>"toto, titi", message=>"re salut...")
   */
	function constructAnswer ($message, $destin, $idUser, $format, $iAll) {
		$dest = array();
		if ($message->from_id != $idUser) {
			$userInfoFrom = Kernel::getUserInfo("ID", $message->from_id);
			$dest[] = $userInfoFrom["login"];
		}
    if ($iAll) {
  		foreach ($destin as $d) {
  			if ($d->to_id != $idUser) {
  				$userInfo = Kernel::getUserInfo("ID", $d->to_id);
  				$dest[] = $userInfo["login"];
  			}
  		}
    }
		
		$dest = implode(", ",$dest);
		$title = "Re : ".$message->title;
		
		switch ($format) {
			case 'wiki' :
				$message =  "\n\n\n> ----- ".CopixI18N::get ('minimail.msg.origin')." -----\n> ".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :\n> " . str_replace("\n", "\n> ", $message->message);
				break;
			case 'dokuwiki' :
				$message =  "\n\n\n> --------- ".CopixI18N::get ('minimail.msg.origin')." ---------\n> ".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :\n> " . str_replace("\n", "\n>", $message->message);
			
				break;
			case 'ckeditor' :
				$message =  "<p></p><blockquote><b>--------- ".CopixI18N::get ('minimail.msg.origin')." ---------</b><br/><i>".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :</i><br/>" . str_replace("\n", "<br/>", $message->message)."</blockquote><br/>";
				break;
		}
		return array("dest"=>$dest, "title"=>$title, "message"=>$message);
	}
	


	/**
	 * Construit le forward à un message. A partir d'un message, construit le titre, le message si un usager fait "Transferer".
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2010/11/16
	 * @param object $message Record du message original
	 * @param string $format Format souhaite
	 * @return array tableau : array(title=>"Tr : le titre", message=>"re salut...")
   */
	function constructForward ($message, $format) {
    
    $userInfoFrom = Kernel::getUserInfo("ID", $message->from_id);
    
		$title = "Tr : ".$message->title;
		
		switch ($format) {
			case 'wiki' :
				$message =  "\n\n\n> ----- ".CopixI18N::get ('minimail.msg.origin')." -----\n> ".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :\n> " . str_replace("\n", "\n> ", $message->message);
				break;
			case 'dokuwiki' :
				$message =  "\n\n\n> --------- ".CopixI18N::get ('minimail.msg.origin')." ---------\n> ".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :\n> " . str_replace("\n", "\n>", $message->message);
			
				break;
			case 'ckeditor' :
				$message =  "<p></p><blockquote><b>--------- ".CopixI18N::get ('minimail.msg.origin')." ---------</b><br/><i>".CopixI18N::get ('minimail.msg.originWho', array($userInfoFrom["login"], Kernel::ymdhis2dmyhi($message->date_send)))." :</i><br/>" . str_replace("\n", "<br/>", $message->message)."</blockquote><br/>";
				break;
		}
		return array("title"=>$title, "message"=>$message);
	}
	















	/**
	 * Détermine si la prévisualisation d'une pièce jointe est possible (cas des images)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/02/21
	 * @param string $file Nom du fichier
	 * @return boolean true si on affiche la vignette
   */
	function isAttachmentImage ($file) {
		$res = false;
		$fullFile = realpath("../var/data")."/minimail/".($file);
		if ($file && file_exists($fullFile) && getimagesize ($fullFile))
			$res = true;
		return $res;

	}

	/**
	 * Renvoie le nom "propre" d'une pièce jointe, sans l'ID du message devant
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/02/21
	 * @param string $file Nom du fichier
	 * @return string Nom épuré
   */
	function getAttachmentName ($file) {
		if ($file && ereg("^([0-9]+)_(.*)", $file, $regs))	$res = $regs[2];
		elseif ($file)																			$res = $file;
		else	$res = '';
		return $res;
	}


}













?>
