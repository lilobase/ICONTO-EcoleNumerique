<?php

$getListPublic    		= new CopixAction ('Groupe', 'getListPublic');
$getListMy    				= new CopixAction ('Groupe', 'getListMy');
$getEdit    					= new CopixAction ('Groupe', 'processGetEdit');
$doEdit   						= new CopixAction ('Groupe', 'doEdit');
$getHome    					= new CopixAction ('Groupe', 'getHome');
$getHomeMembers				= new CopixAction ('Groupe', 'processGetHomeMembers');
$getHomeAdmin					= new CopixAction ('Groupe', 'getHomeAdmin');
$getHomeAdminMembers	= new CopixAction ('Groupe', 'processGetHomeAdminMembers');
$getHomeAdminMember 	= new CopixAction ('Groupe', 'processGetHomeAdminMember');
$getHomeAdminModules	= new CopixAction ('Groupe', 'getHomeAdminModules');
$getHomeAdminMagicmail	= new CopixAction ('Groupe', 'getHomeAdminMagicmail');

$getDelete   					= new CopixAction ('Groupe', 'getDelete');
$doDelete   					= new CopixAction ('Groupe', 'doDelete');
$doUnsubscribe   			= new CopixAction ('Groupe', 'doUnsubscribe');
$doSubscribe   				= new CopixAction ('Groupe', 'doSubscribe');
$doSubscribeWaiting		= new CopixAction ('Groupe', 'doSubscribeWaiting');
$doJoin   						= new CopixAction ('Groupe', 'doJoin');
$doModifyMember  	    = new CopixAction ('Groupe', 'doModifyMember');
$go 									= new CopixAction ('Groupe', 'getHome');
$doFormAdminModules 	= new CopixAction ('Groupe', 'doFormAdminModules');
$doUnsubscribeHimself = new CopixAction ('Groupe', 'doUnsubscribeHimself');

$default 							= & $getListMy;

/*
$example    = new CopixAction ('Exemple', 'getExemple');
$default    = & $example;
*/
