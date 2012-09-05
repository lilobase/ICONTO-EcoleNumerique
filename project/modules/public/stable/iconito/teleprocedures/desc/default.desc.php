<?php

$insert 							= new CopixAction ('default', 'insert');
$fiche 								= new CopixAction ('default', 'processFiche');
$ficheDroits 					= new CopixAction ('default', 'processFicheDroits');
$listTeleprocedures 	= new CopixAction ('default', 'processListTeleprocedures');

$changeStatut 				= new CopixAction ('default', 'changeStatut');
$changeResponsables		= new CopixAction ('default', 'changeResponsables');
$sendMails						= new CopixAction ('default', 'sendMails');

$insertInfoSupp 			= new CopixAction ('default', 'insertInfoSupp');

$go 									= new CopixAction ('default', 'go');

$default 							= & $go;

