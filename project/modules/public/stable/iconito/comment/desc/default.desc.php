<?php
/**
* @package copix
* @subpackage comment
* @version	$Id: default.desc.php,v 1.0
* @author Bertrand Yan
*/
$plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
$user     = & $plugAuth->getUser ();
if ($user->isConnected()) {
   $add           = & new CopixAction ('Comment','doPrepareAdd');
   $edit          = & new CopixAction ('Comment','getEdit');
   $cancelEdit    = & new CopixAction ('Comment','doCancelEdit');
   $valid         = & new CopixAction ('Comment','doValid');
   $getList       = & new CopixAction ('Comment','getList');
   $delete        = & new CopixAction ('Comment','doDelete');
   $prepareEdit   = & new CopixAction ('Comment','doPrepareEdit');

   $default       = & $getList;
}else{
   $__redirect = array (
   'add',
   'edit',
   'cancelEdit',
   'getList',
   'valid',
   'default',
   'delete',
   'prepareEdit');
   foreach ($__redirect as $action){
      $$action = & new CopixActionRedirect (CopixUrl::get('auth||login'));
   }
}

?>
