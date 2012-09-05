<?php

$getList			= new CopixAction ('ressource', 'getList');
$getRessource		= new CopixAction ('ressource', 'getRessource');
$doRessourceSave	= new CopixAction ('ressource', 'doRessourceSave');

$getSearch			= new CopixAction ('ressource', 'getSearch');
$getSearchAdvanced			= new CopixAction ('ressource', 'getSearchAdvanced');

$getTag			= new CopixAction ('ressource', 'getTag');

// $add			= new CopixAction ('ressource', 'getAdd');
// $debug			= new CopixAction ('ressource', 'getDebug');

$go				= new CopixAction ('ressource', 'go');

$default		= & $go;


