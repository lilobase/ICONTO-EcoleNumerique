<?php

$getListRecv    		= & new CopixAction ('Minimail', 'getListRecv');
$getListSend    		= & new CopixAction ('Minimail', 'getListSend');
$getNewForm    			= & new CopixAction ('Minimail', 'getNewForm');
$getMessage    			= & new CopixAction ('Minimail', 'getMessage');
$doSend   					= & new CopixAction ('Minimail', 'doSend');
$downloadAttachment	= & new CopixAction ('Minimail', 'downloadAttachment');
$previewAttachment	= & new CopixAction ('Minimail', 'previewAttachment');
$doDelete   				= & new CopixAction ('Minimail', 'doDelete');

$default 						= & $getListRecv;

/*
$example    = & new CopixAction ('Exemple', 'getExemple');
$default    = & $example;
*/
?>
