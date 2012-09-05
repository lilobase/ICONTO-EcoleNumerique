<?php

//$getListForums				= new CopixAction ('forum', 'getListForums');
$getForum   					= new CopixAction ('forum', 'getForum');
$getTopic   					= new CopixAction ('forum', 'getTopic');
$getMessageForm   		= new CopixAction ('forum', 'processGetMessageForm');
$doMessageForm   			= new CopixAction ('forum', 'doMessageForm');
$getDeleteMessage   	= new CopixAction ('forum', 'getDeleteMessage');
$doDeleteMessage   		= new CopixAction ('forum', 'doDeleteMessage');
$doAlertMessage   		= new CopixAction ('forum', 'doAlertMessage');
$getTopicForm   			= new CopixAction ('forum', 'processGetTopicForm');
$doTopicForm   				= new CopixAction ('forum', 'doTopicForm');
$getDeleteTopic   		= new CopixAction ('forum', 'getDeleteTopic');
$doDeleteTopic   			= new CopixAction ('forum', 'doDeleteTopic');
$go 									= new CopixAction ('forum', 'getForum');

$default 									= & $go;


/*
$example    = new CopixAction ('Exemple', 'getExemple');
$default    = & $example;
*/
