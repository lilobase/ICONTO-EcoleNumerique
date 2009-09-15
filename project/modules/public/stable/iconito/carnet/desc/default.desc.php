<?php


$getCarnet   					= & new CopixAction ('Carnet', 'getCarnet');
$getTopic   					= & new CopixAction ('Carnet', 'getTopic');
$getTopicForm   			= & new CopixAction ('Carnet', 'getTopicForm');
$doTopicForm   				= & new CopixAction ('Carnet', 'doTopicForm');
$getMessageForm   		= & new CopixAction ('Carnet', 'getMessageForm');
$doMessageForm   			= & new CopixAction ('Carnet', 'doMessageForm');

$go 									= & new CopixAction ('Carnet', 'go');

$default 							= & $getCarnet;

/*
$example    = & new CopixAction ('Exemple', 'getExemple');
$default    = & $example;
*/
?>
