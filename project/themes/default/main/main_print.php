<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/theme.css"); ?>" type="text/css"/>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css"/>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css" media="print"/>
    <script type="text/javascript">var urlBase = '<?php echo CopixUrl::getRequestedScriptPath (); ?>'; getRessourcePathImg = urlBase+'<?php echo CopixURL::getResourcePath ('img/'); ?>/';</script>
  <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/iconito/iconito.js"></script>
  <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/prototype-1.6.0.3.js"></script>
  <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/iconito/lang_<?php echo CopixI18N::getLang(); ?>.js"></script>
    <script type="text/javascript" src="<?php echo CopixUrl::get () ?>flvplayer/ufo.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo CopixUrl::get () ?>favicon.ico" />
  <?php echo $HTML_HEAD; ?>
</head>

<body<?php if (isset($BODY_ON_LOAD) && $BODY_ON_LOAD) echo ' onLoad="'.$BODY_ON_LOAD.'"'; if (isset($BODY_ON_UNLOAD) && $BODY_ON_UNLOAD) echo ' onUnLoad="'.$BODY_ON_UNLOAD.'"'; ?>>


<?php $user = _currentUser (); ?>

<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>

<div class="page">

<div class="content">
<div class="title"><?php echo $TITLE_PAGE; ?></div>

<?php if (1 || isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU, 'popup'=>true, 'canClose'=>(isset($CAN_CLOSE)?$CAN_CLOSE:null))); } ?>

<div class="main <?php $module = CopixRequest::get ('module'); if ($module) echo $module; ?>">
    <?php echo $MAIN; ?>
    <br clear="all" /><br clear="all" />
</div> <!-- fin main -->





</div> <!-- fin content -->

</div> <!-- fin page -->

</body>

</html>





