<?php



$getSso			= new CopixAction ('sso', 'getSso');
$getServiceNewForm			= new CopixAction ('sso', 'processGetServiceNewForm');
$doServiceNewForm			= new CopixAction ('sso', 'doServiceNewForm');

$doActivateService			= new CopixAction ('sso', 'doActivateService');
$doDeleteService			= new CopixAction ('sso', 'doDeleteService');

$go				= & $getSso;

$default		= & $getSso;


$doSso			= new CopixAction ('sso', 'doSso');


