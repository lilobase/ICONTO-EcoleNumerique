<?php
/*
    @file 		main.php
    @desc		Main layout constructor
    @version 	1.0.0b
    @date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
    @author 	S.HOLTZ <sholtz@cap-tic.fr>

    Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<?php
include_once COPIX_PROJECT_PATH."themes/default/helper.php";

$module = CopixRequest::get ('module');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo CopixUrl::get () ?>favicon.ico" />

    <?php include_once COPIX_PROJECT_PATH."themes/default/styles.php"; ?>
    <?php include_once COPIX_PROJECT_PATH."themes/default/scripts.php"; ?>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/popup.css"); ?>" type="text/css"/>
    <?php echo $HTML_HEAD; ?>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_custom.css"); ?>" type="text/css"/>

</head>

<body>

<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>

<div id="content-popup" class="ink_blue">
    <?php echo $MAIN; ?>
</div>

</body>
</html>