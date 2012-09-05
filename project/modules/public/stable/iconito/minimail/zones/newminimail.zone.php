<?php
/**
 * Zone NewMinimail module Minimail, qui affiche le nb de messages non lus de l'utilisateur courant
 *
 * @package Iconito
 * @subpackage	Minimail
 */
class ZoneNewMinimail extends CopixZone
{
    /**
     * Affiche le nb de messages non lus de l'utilisateur courant
     *
     * Le nb de messages est cliquable et renvoie vers sa boîte de réception. S'il n'y a pas de messages, la zone n'affiche rien.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/10/17
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $idUser = _currentUser()->getId();

        if ($idUser) {

            $dao = _dao("minimail|minimail_to");

            $messages = $dao->getListRecvUnread($idUser);
            $nbMessages = count($messages);

            $tpl->assign('nbMessages', $nbMessages);

            // retour de la fonction :
        $toReturn = $tpl->fetch ('newminimail.tpl');

        }

    return true;

    }



}
