<?php

$getMalle   				= & new CopixAction ('malle', 'processGetMalle');
$getMallePopup   		= & new CopixAction ('malle', 'processGetMallePopup');

$doDownloadFile   	= & new CopixAction ('malle', 'doDownloadFile');

//$getUploadFile   		= & new CopixAction ('malle', 'getUploadFile');
$doUploadFile   		= & new CopixAction ('malle', 'doUploadFile');

$getUploadFileZip   = & new CopixAction ('malle', 'processGetUploadFileZip');
$doUploadFileZip   = & new CopixAction ('malle', 'doUploadFileZip');

$doAddFolder   			= & new CopixAction ('malle', 'doAddFolder');

$doAction   				= & new CopixAction ('malle', 'doAction');
$getActionRename    = & new CopixAction ('malle', 'getActionRename');
$doActionRename     = & new CopixAction ('malle', 'processDoActionRename');
$doActionDownloadZip     = & new CopixAction ('malle', 'processDoActionDownloadZip');

$go 								= & new CopixAction ('malle', 'processGetMalle');

$default						= & $go;

?>
