<?php

$getAssistance = new CopixAction ('assistance', 'getAssistance');
$getUsers      = new CopixAction ('assistance', 'getUsers');
$getSwitchUser = new CopixAction ('assistance', 'getSwitchUser');




$go            = & $getAssistance;
$default       = & $getAssistance;

$users         = & $getUsers;
$switch        = & $getSwitchUser;

