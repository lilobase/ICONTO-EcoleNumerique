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

<head profile="http://www.w3.org/2005/10/profile">
    <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
    <title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
    <link rel="icon" type="image/x-icon" href="<?php echo CopixUrl::getRequestedScriptPath(); ?>favicon.ico" />

    <?php include_once COPIX_PROJECT_PATH."themes/default/styles.php"; ?>
        <?php include_once COPIX_PROJECT_PATH."themes/default/scripts.php"; ?>
        <?php echo $HTML_HEAD; ?>


</head>

<body class="thm nodebug"<?php if (isset($BODY_ON_LOAD) && $BODY_ON_LOAD) echo ' onLoad="'.$BODY_ON_LOAD.'"'; if (isset($BODY_ON_UNLOAD) && $BODY_ON_UNLOAD) echo ' onUnLoad="'.$BODY_ON_UNLOAD.'"'; ?>>

<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>

<div id="main-wrapper" class="wrapper" style="">

<div id="absolute"></div>
<div id="popup"><?php getZones("popup"); ?></div>
<div id="header"><?php getZones("header"); ?></div>
<div id="page">
    <div id="page-header">
        <div class="thm-HL"><div class="thm-HR"><div class="thm-HM">
            <div class="thm-logo padder"><h1><a class="logo" href="<?php echo CopixUrl::get() ?>"><span class="hiddenClean">ICONITO &Eacute;cole Num&eacute;rique</span></a></h1>
            <div id="top"><?php getZones("top"); ?></div>
            <div id="menu">
                <div id="menucenter"><?php getZones("menucenter"); ?></div>
                <div id="menuleft"><?php getZones("menuleft", false); ?></div>
                <div id="menuright"><?php getZones("menuright"); ?></div>
            </div>
            </div>
        </div></div></div>
        <?php if ($module=="welcome") { ?><div id="welcome_bienvenue"></div><?php } ?>
    </div>
    <div id="page-middle">
        <div class="thm-ML"><div class="thm-MR"><div class="thm-MTL"><div class="thm-MTR">
        <div class="marger">
            <div id="breadcrumb"><?php getZones("breadcrumb"); ?></div>
            <div class="wrapper-expander">
                <div id="left"><?php getZones("left"); ?></div>
                <div class="wrapper-shifter">
                    <div id="content">
                        <div id="contenttop"><?php getZones("contenttop"); ?></div>
                        <div id="contentmain">
                            <?php $title = (isset($TITLE_PAGE)) ? $TITLE_PAGE : '';
                            $titleContext = (isset($TITLE_CONTEXT)) ? $TITLE_CONTEXT : ''; ?>
                            <?php if (inDashContext()) { moduleContext('open', $title, $titleContext); } ?>
                            <div id="<?php echo $module; ?>" class="<?php echo $module; ?>">
                            <?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:false))); } ?>
                            <?php echo $MAIN; ?>
                            </div>
                            <?php if (inDashContext()) { moduleContext('close'); } ?>
                        </div>
                        <div id="contentbottom"><?php getZones("contentbottom"); ?></div>
                    </div>
                </div><!-- wrapper-shifter -->
                <div id="right"><?php getZones("right"); ?></div>
            </div><!-- wrapper-expander -->
        </div>
        </div></div></div></div>
    </div>
    <div id="page-bottom">
        <div class="thm-BL"><div class="thm-BR"><div class="thm-BM">
        <div id="bottom"><?php getZones("bottom"); ?></div>
        </div></div></div>
    </div>
</div><!-- page -->
<div id="footer"><?php getZones("footer"); ?></div>
<div id="debug"><?php getZones("debug"); ?></div>

</div><!-- wrapper -->

<?php echo CopixZone::process ('kernel|footer') ?>

</body>
</html>