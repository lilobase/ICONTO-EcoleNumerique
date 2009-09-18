<?php

$getMalle   				= & new CopixAction ('malle', 'processGetMalle');
$getMallePopup   		= & new CopixAction ('malle', 'getMallePopup');

$doDownloadFile   	= & new CopixAction ('malle', 'doDownloadFile');

//$getUploadFile   		= & new CopixAction ('malle', 'getUploadFile');
$doUploadFile   		= & new CopixAction ('malle', 'doUploadFile');

$getUploadFileZip   = & new CopixAction ('malle', 'getUploadFileZip');
$doUploadFileZip   = & new CopixAction ('malle', 'doUploadFileZip');

$doAddFolder   			= & new CopixAction ('malle', 'doAddFolder');

$doAction   				= & new CopixAction ('malle', 'doAction');
$getActionRename    = & new CopixAction ('malle', 'getActionRename');
$doActionRename     = & new CopixAction ('malle', 'doActionRename');
$doActionDownloadZip     = & new CopixAction ('malle', 'doActionDownloadZip');

$go 								= & new CopixAction ('malle', 'processGetMalle');

$default						= & $go;

?>
