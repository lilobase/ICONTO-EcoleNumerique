<?php
/*
    @file 		styles.php
    @desc		styles loader
    @version 	1.0.0b
    @date 		2010-05-28 09:28:09 +0200 (Fri, 28 May 2010)
    @author 	S.HOLTZ <sholtz@cap-tic.fr>

    Copyright (c) 2010 CAP-TIC <http://www.cap-tic.fr>
*/
?>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_deprecated.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_layout.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_typography.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_buttons.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_zones.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/jquerycss/default/jquery-ui-1.9.0.custom.min.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("js/fancybox/jquery.fancybox-1.3.4.css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/module_kernel.css"); ?>" type="text/css"/>

<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("js/jquery/jquery.tooltip.css"); ?>" type="text/css"/>

<?php if (isset($ENpopup) && $ENpopup) { ?><link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/popup.css"); ?>" type="text/css"/><?php } ?>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css" media="print"/>

<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/module_".$module.".css"); ?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_custom.css"); ?>" type="text/css" />

<?php if (preg_match("/MSIE 6.0/i", $_SERVER["HTTP_USER_AGENT"])) { ?>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_iehacks.css"); ?>" type="text/css"/>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_ie6hacks.css"); ?>" type="text/css"/>
<?php } ?>
<?php if (preg_match("/MSIE 7.0/i", $_SERVER["HTTP_USER_AGENT"])) { ?>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_iehacks.css"); ?>" type="text/css"/>
    <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/core_ie7hacks.css"); ?>" type="text/css"/>
<?php }