<?php
/**
 * Zone NewMinimail module Minimail, qui affiche le nb de messages non lus de l'utilisateur courant
 * 
 * @package Iconito
 * @subpackage	Minimail
 */
class ZoneNewMinimail extends CopixZone {

	/**
	 * Affiche le nb de messages non lus de l'utilisateur courant
	 *
	 * Le nb de messages est cliquable et renvoie vers sa bo�te de r�ception. S'il n'y a pas de messages, la zone n'affiche rien.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/10/17
	 * @param integer idUser Id de l'utilisateur
	 */
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$idUser = $this->params['idUser'];
		
		if ($idUser) {
		
		$dao = CopixDAOFactory::create("minimail|minimail_to");

		$messages = $dao->getListRecvUnread($idUser);	// Todo mettre son ID
		$nbMessages = count($messages);

		$tpl->assign('nbMessages', $nbMessages);
		
		// retour de la fonction :
    $toReturn = $tpl->fetch ('newminimail.tpl');
		
		if( !$nbMessages ) $toReturn="";
		}
		
    return true;

	}



}
?>