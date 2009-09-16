<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/theme.css"); ?>" type="text/css"/>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css" media="print"/>
  <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/iconito/iconito.js"></script>
  <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/prototype-1.6.0.3.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo CopixUrl::get () ?>favicon.ico" />
  <?php echo $HTML_HEAD; ?>
</head>

<body<?php if (isset($BODY_ON_LOAD) && $BODY_ON_LOAD) echo ' onLoad="'.$BODY_ON_LOAD.'"'; if (isset($BODY_ON_UNLOAD) && $BODY_ON_UNLOAD) echo ' onUnLoad="'.$BODY_ON_UNLOAD.'"'; ?>>



<div id="divUserProfil" onclick="hideUser();"></div>
<div id="divHelp" onclick="hideHelp();"></div>
<div id="ajaxDiv"></div>


<div class="page">

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
<td rowspan="2" class="ecole-logo"><a href="<?php echo CopixUrl::get () ?>"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" alt="<?php echo _i18n('public|public.nav.accueil') ?>" title="<?php echo _i18n('public|public.nav.accueil') ?>" border="0" width="175" height="80" vspace="0" /></a></td>
<td class="ecole-left"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td class="ecole-center"><div style="margin-top:4px;"><?php if (isset($HOME_TITLE1) && $HOME_TITLE1) { ?><a href="<?php echo CopixUrl::get ('kernel|getNodes') ?>"><font style="font-size: 103%;"><?php echo $HOME_TITLE1; ?></font></a><?php if (isset($HOME_TITLE2) && $HOME_TITLE2) { ?><br /><font style="font-size: 80%;"><?php echo $HOME_TITLE2; ?></font><?php } } else { ?><font style="font-size: 103%;"><?php echo _i18n('public|public.default.homeTitle1') ?></font><br /><font style="font-size: 80%;"><?php echo _i18n('public|public.default.homeTitle2') ?></font><?php } ?>
</div></td>



<?php $user = _currentUser (); ?>

<?php /* if ($user->isConnected()) { */ ?>
<?php if (isset($IS_LOGGED) && $IS_LOGGED) { ?>

<td class="ecole-right"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td width="100%" align="right"><?php echo $MINIMAIL_UNREAD; ?></td>
</tr>
<tr>
<td colspan="4" class="ecole-login-bar"><?php echo $LOGIN_BAR; ?><?php if ($HEADER_MODE == "compact") { ?> | <a href="<?php echo CopixUrl::get ('kernel||getHome') ?>"><?php echo _i18n('kernel|kernel.message.moniconito') ?></a><?php } ?></td>
</tr>

<?php } else { ?>


<td class="ecole-right"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td width="100%" align="right"><div class="annu_blog"><img src="<?php echo CopixUrl::getResource ("img/welcome/annu_blog.gif"); ?>" height="34" hspace="4" alt="<?php echo _i18n('public|public.blog.annuaire') ?>" border="0" align="right"/><a title="<?php echo _i18n('public|public.blog.annuaire') ?>" href="<?php echo CopixUrl::get ('public||') ?>"><?php echo _i18n('public|public.blog.annuaire') ?></a></div></td>
</tr>
<tr>
<td colspan="4" class="ecole-login-bar"><?php if (isset($LOGIN_BAR) && $LOGIN_BAR) echo $LOGIN_BAR; ?></td>
</tr>
<?php } ?>
</table>



<?php if (1 || isset($IS_LOGGED) && $IS_LOGGED && $HEADER_MODE != "compact") { ?>

<div class="icons">
<p style="text-align: right; border:0; padding:0; margin:0; padding-right: 20px;">

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('kernel||getHome') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_ecole.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.menu.accueil') ?>" title="<?php echo _i18n('kernel|kernel.menu.accueil') ?>"><br/><?php echo _i18n('kernel|kernel.menu.accueil') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('groupe||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_groupes.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.menu.groupes') ?>" title="<?php echo _i18n('kernel|kernel.menu.groupes') ?>"><br/><?php echo _i18n('kernel|kernel.menu.groupes') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('ressource||', array('id'=>2)) ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_ressources.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_ressource') ?>{i18n key=kernel|}" title="<?php echo _i18n('kernel|kernel.codes.mod_ressource') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_ressource') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('annuaire||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_carnet.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_annuaire') ?>" title="<?php echo _i18n('kernel|kernel.codes.mod_annuaire') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_annuaire') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('minimail||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_minimail.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_minimail') ?>" title="<?php echo _i18n('kernel|kernel.codes.mod_minimail') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_minimail') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('malle||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_malle.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_malle') ?>" title="<?php echo _i18n('kernel|kernel.codes.mod_malle') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_malle') ?></a></div>

<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('agenda||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_agenda.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_agenda') ?>" title="<?php echo _i18n('kernel|kernel.codes.mod_agenda') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_agenda') ?></a></div>

</p>
</div>
<?php } else { ?>
<div class="icons_no"><img src="img/spacer.gif" height="1" width="1" alt="" /></div>
<?php } ?>


<div class="content">
<div class="title"><?php echo $TITLE_PAGE; ?></div>

<?php if (isset($MENU) && $MENU) { ?>
	<div class="options"><?php echo $MENU; ?></div>
<?php } ?>


<div class="main<?php if (isset($CONTENT_CLASS) && $CONTENT_CLASS) echo $CONTENT_CLASS; ?>">
	<?php echo $MAIN; ?>
	<br clear="all" /><br clear="all" />
</div> <!-- fin main -->





</div> <!-- fin content -->



</div> <!-- fin page -->


<div id="footer">
<?php echo _i18n('public|public.nav.copyright') ?> | <a href="<?php echo CopixUrl::get ('aide||') ?>" title="<?php echo _i18n('public|public.aide') ?>"><b><?php echo _i18n('public|public.aide') ?></b></a> | <a href="<?php echo CopixUrl::get ('public||aPropos') ?>" title="<?php echo _i18n('public|public.apropos') ?>"><?php echo _i18n('public|public.apropos') ?></a>

<?php if (isset($FOOTER) && $FOOTER) echo $FOOTER; ?>
</div>



<?php echo CopixZone::process ('auth|userLogged') ?>

</body>

</html>





