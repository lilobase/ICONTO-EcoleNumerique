<?php

$insert 							= & new CopixAction ('default', 'insert');
$fiche 								= & new CopixAction ('default', 'fiche');
$ficheDroits 					= & new CopixAction ('default', 'ficheDroits');
$listTeleprocedures 	= & new CopixAction ('default', 'listTeleprocedures');

$changeStatut 				= & new CopixAction ('default', 'changeStatut');
$changeResponsables		= & new CopixAction ('default', 'changeResponsables');
$sendMails						= & new CopixAction ('default', 'sendMails');

$insertInfoSupp 			= & new CopixAction ('default', 'insertInfoSupp');

$go 									= & new CopixAction ('default', 'go');

$default 							= & $go;

?>
