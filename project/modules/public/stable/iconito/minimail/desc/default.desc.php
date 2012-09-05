<?php

$getListRecv    		= new CopixAction ('Minimail', 'getListRecv');
$getListSend    		= new CopixAction ('Minimail', 'getListSend');
$getNewForm    			= new CopixAction ('Minimail', 'processGetNewForm');
$getMessage    			= new CopixAction ('Minimail', 'getMessage');
$doSend   					= new CopixAction ('Minimail', 'doSend');
$downloadAttachment	= new CopixAction ('Minimail', 'downloadAttachment');
$previewAttachment	= new CopixAction ('Minimail', 'previewAttachment');
$doDelete   				= new CopixAction ('Minimail', 'doDelete');
$attachmentToClasseur   				= new CopixAction ('Minimail', 'attachmentToClasseur');

$default 						= & $getListRecv;

/*
$example    = new CopixAction ('Exemple', 'getExemple');
$default    = & $example;
*/
