<?php

$publish				= new CopixAction ('Magicmail', 'doMailPublish');

$doCreateMail				= new CopixAction ('Magicmail', 'doCreateMail');
$doDeleteMail				= new CopixAction ('Magicmail', 'doDeleteMail');

$go							= new CopixAction ('Magicmail', 'getMagicMail');
$default				= & $go;

