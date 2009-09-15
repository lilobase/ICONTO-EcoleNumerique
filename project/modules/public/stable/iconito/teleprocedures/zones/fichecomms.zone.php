<?php

/**
 * Commentaires d'une teleprocedure
 * 
 * @package Iconito
 * @subpackage Teleprocedures
 */

require_once (COPIX_MODULE_PATH.'teleprocedures/'.COPIX_CLASSES_DIR.'teleproceduresservice.class.php');

class ZoneFicheComms extends CopixZone {


	/**
	 * Commentaires d'une procedure
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2008/01/30
	 * @param object $rFiche Recordset de la procedure
	 */

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$rFiche = $this->params['rFiche'];
		$mondroit = $this->params['mondroit'];

	  $dbWidget = & CopixDBFactory::getDbWidget ();
		
		$daoinfo = & CopixDAOFactory::create ('infosupp');
    $sql ='SELECT * FROM module_teleprocedure_infosupp WHERE idinter='.$rFiche->idinter.'';

		$canCheckVisible = TeleproceduresService::canMakeInTelep('CHECK_VISIBLE', $mondroit);
		$canAddComment = TeleproceduresService::canMakeInTelep('ADD_COMMENT', $mondroit);

		if (!$canCheckVisible)
			$sql .= " AND info_message!='' AND info_message IS NOT NULL";

		$sql .= " ORDER BY dateinfo ASC, idinfo ASC";

    $list = $dbWidget->fetchall ($sql);

		// Pour chaque message on cherche les infos de son auteur
		while (list($k,) = each($list)) {
			$userInfo = Kernel::getUserInfo("ID", $list[$k]->iduser);
			//var_dump($userInfo);
			$avatar = Prefs::get('prefs', 'avatar', $list[$k]->iduser);
	  	$userInfo['avatar'] = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';
			
			$list[$k]->user = $userInfo;
		}
		//print_r($rFiche);
		$tpl->assign ('info_message_edition', CopixZone::process ('kernel|edition', array('field'=>'info_message', 'format'=>$rFiche->type_format, 'content'=>'', 'width'=>350, 'height'=>135, 'options'=>array('ToolbarSet'=>'Basic', 'EnterMode'=>'br', 'ToolbarStartExpanded'=>false))));
		$tpl->assign ('info_commentaire_edition', CopixZone::process ('kernel|edition', array('field'=>'info_commentaire', 'format'=>$rFiche->type_format, 'content'=>'', 'width'=>350, 'height'=>135, 'options'=>array('ToolbarSet'=>'Basic', 'EnterMode'=>'br', 'ToolbarStartExpanded'=>false))));
		// TODO : ToolbarSet => IconitoBasic
		
		$tpl->assign ('canCheckVisible', $canCheckVisible);
		$tpl->assign ('canAddComment', $canAddComment);
	  $tpl->assign ('list', $list);
	  $tpl->assign ('rFiche', $rFiche);
		
    $toReturn = $tpl->fetch ('fiche-comms-zone.tpl');
		return true;
	 
	}
}
?>
