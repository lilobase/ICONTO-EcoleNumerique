<?php
/**
* @filesource
* @package :Copix
* @subpackage : comment
* @author : Bertrand Yan
*/

/**
* @package intranet
* @subpackage koyo_project
* @version	$Id: CommentList.zone.php,v 1.0
* @author Bertrand Yan
* zone pour l'edition d'untype de document
*/
require_once (COPIX_UTILS_PATH.'CopixPager.class.php');

class ZoneCommentList extends CopixZone {
	function _createContent (&$toReturn) {
      $tpl = & new CopixTpl ();

      $dao = & _dao('Comment');
      $sp  = & _daoSearchConditions();
      $sp->addCondition ('id_cmt','=',$this->getParam('id'));
      $sp->addCondition ('type_cmt','=',$this->getParam('type'));
      $sp->addItemOrder ('position_cmt', 'desc');
      
      $arData = $dao->findBy($sp);
      if (count($arData)>0) {
         $perPage = isset($this->getParam('perPage')) ? intval($this->getParam('perPage')) : intval(CopixConfig::get('comment|perPage'));
          $params = Array(
            'perPage'    => $perPage,
            'delta'      => 5,
            'recordSet'  => $arData,
            'template'   => '|pager.tpl'
         );
         $Pager = CopixPager::Load($params);
         $tpl->assign ('pager'    , $Pager->GetMultipage());
         $tpl->assign ('comments' , $Pager->data);
      }
      $tpl->assign ('back'  ,$this->getParam('back'));
      $tpl->assign ('id'    ,$this->getParam('id'));
      $tpl->assign ('type'  ,$this->getParam('type'));

      $adminEnabled = CopixUserProfile::valueOf ('site', 'siteAdmin') >= PROFILE_CCV_MODERATE;
      $tpl->assign ('adminEnabled'  ,$adminEnabled);

      $plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
      $user     = & $plugAuth->getUser ();
      $tpl->assign ('login'  ,$user->login);

      $toReturn = $tpl->fetch ('comment.list.tpl');
      return true;
	}

}
?>
