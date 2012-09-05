<?php

/**
 * Commentaires d'une teleprocedure
 *
 * @package Iconito
 * @subpackage Teleprocedures
 */

_classInclude('teleprocedures|teleproceduresservice');

class ZoneFicheComms extends CopixZone
{
    /**
     * Commentaires d'une procedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param object $rFiche Recordset de la procedure
     */

    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $rFiche = $this->getParam('rFiche');
        $mondroit = $this->getParam('mondroit');

        $daoinfo = & _dao ('infosupp');
    $sql ='SELECT * FROM module_teleprocedure_infosupp WHERE idinter='.$rFiche->idinter.'';

        $canCheckVisible = TeleproceduresService::canMakeInTelep('CHECK_VISIBLE', $mondroit);
        $canAddComment = TeleproceduresService::canMakeInTelep('ADD_COMMENT', $mondroit);

        if (!$canCheckVisible)
            $sql .= " AND info_message!='' AND info_message IS NOT NULL";

        $sql .= " ORDER BY dateinfo ASC, idinfo ASC";

    $results = _doQuery ($sql);

        // Pour chaque message on cherche les infos de son auteur
        $list = array();
        foreach ($results as $r) {
            $userInfo = Kernel::getUserInfo("ID", $r->iduser);
            //var_dump($userInfo);
            $avatar = Prefs::get('prefs', 'avatar', $r->iduser);
          $userInfo['avatar'] = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
            $r->user = $userInfo;
            $list[] = $r;
        }
        //print_r($rFiche);
        $tpl->assign ('info_message_edition', CopixZone::process ('kernel|edition', array('field'=>'info_message', 'format'=>$rFiche->type_format, 'content'=>'', 'width'=>350, 'height'=>135, 'options'=>array('toolbarSet'=>'IconitoBasic', 'enterMode'=>'br', 'toolbarStartupExpanded'=>'false'))));
        $tpl->assign ('info_commentaire_edition', CopixZone::process ('kernel|edition', array('field'=>'info_commentaire', 'format'=>$rFiche->type_format, 'content'=>'', 'width'=>350, 'height'=>135, 'options'=>array('toolbarSet'=>'IconitoBasic', 'enterMode'=>'br', 'toolbarStartupExpanded'=>'false'))));

        $tpl->assign ('canCheckVisible', $canCheckVisible);
        $tpl->assign ('canAddComment', $canAddComment);
      $tpl->assign ('list', $list);
      $tpl->assign ('rFiche', $rFiche);

    $toReturn = $tpl->fetch ('fiche-comms-zone.tpl');
        return true;

    }
}
