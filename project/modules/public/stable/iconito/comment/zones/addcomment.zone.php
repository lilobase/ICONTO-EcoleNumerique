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
* @version	$Id: addcomment.zone.php,v 1.0
* @author Bertrand Yan
* zone pour l'edition d'untype de document
*/

class ZoneAddComment extends CopixZone {
	function _createContent (&$toReturn) {
      $tpl = & new CopixTpl ();
      $plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
      $user     = & $plugAuth->getUser ();
      if ($user->isConnected()) {
         $tpl->assign ('showErrors',$this->params['e']);
         //dao error or something else
         if (isset($this->params['toEdit']->errors)) {
            $tpl->assign ('errors' ,$this->params['toEdit']->errors);
         }else{
            $tpl->assign ('errors' ,$this->params['toEdit']->check ());
         }

         $tpl->assign ('toEdit'    ,$this->params['toEdit']);

         $services = & CopixClassesFactory::create ('comment|commentservices');
         $tpl->assign ('formatList',$services->getFormatList ());

         $toReturn = $tpl->fetch ('comment.add.tpl');
      }else{
         $toReturn = CopixI18N::get('comment|comment.messages.needLogin');
      }
      return true;
	}

}
?>
