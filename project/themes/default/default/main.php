<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <title><?php echo isset ($TITLE_BAR) ? $TITLE_BAR : ''; ?></title>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/theme.css"); ?>" type="text/css"/>
  <link rel="stylesheet" href="<?php echo CopixUrl::getResource ("styles/print.css"); ?>" type="text/css" media="print"/>

    <?php if (_request ('module') != 'admin'): ?>
      <script type="text/javascript">var urlBase = '<?php echo CopixUrl::getRequestedScriptPath (); ?>'; getRessourcePathImg = urlBase+'<?php echo CopixURL::getResourcePath ('img/'); ?>/';</script>
      <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/iconito/iconito.js"></script>
      <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/iconito/lang_<?php echo CopixI18N::getLang(); ?>.js"></script>
      <script type="text/javascript" src="<?php echo CopixUrl::get () ?>flvplayer/ufo.js"></script>
      <script type="text/javascript" src="<?php echo CopixUrl::get () ?>js/prototype-1.6.0.3.js"></script>
  <?php endif ?>
  <link rel="shortcut icon" type="image/x-icon" href="<?php echo CopixUrl::get () ?>favicon.ico" />
  <?php echo $HTML_HEAD; ?>
</head>

<body<?php if (isset($BODY_ON_LOAD) && $BODY_ON_LOAD) echo ' onLoad="'.$BODY_ON_LOAD.'"'; if (isset($BODY_ON_UNLOAD) && $BODY_ON_UNLOAD) echo ' onUnLoad="'.$BODY_ON_UNLOAD.'"'; ?>>

<div id="divUserProfil" onclick="hideUser();"></div>
<div id="ajaxDiv"></div>


<div class="page">

<table class="head" border="0" cellspacing="0" cellpadding="0">
<tr>
<td rowspan="2" class="ecole-logo"><a href="<?php echo CopixUrl::get () ?>"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" alt="<?php echo _i18n('public|public.nav.accueil') ?>" title="<?php echo _i18n('public|public.nav.accueil') ?>" border="0" width="175" height="80" vspace="0" /></a></td>
<td class="ecole-left"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td class="ecole-center"><div style="margin-top:4px;"><?php if (isset($HOME_TITLE1) && $HOME_TITLE1) { ?><a href="<?php echo CopixUrl::get ('kernel|getNodes') ?>"><font style="font-size: 103%;"><?php echo $HOME_TITLE1; ?></font></a><?php if (isset($HOME_TITLE2) && $HOME_TITLE2) { ?><br /><font style="font-size: 80%;"><?php echo $HOME_TITLE2; ?></font><?php } } else { ?><font style="font-size: 103%;"><?php echo _i18n('public|public.default.homeTitle1') ?></font><br /><font style="font-size: 80%;"><?php echo _i18n('public|public.default.homeTitle2') ?></font><?php } ?>
</div></td>



<?php $user = _currentUser (); ?>

<?php if ($user->isConnected()) { ?>

<td class="ecole-right"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td width="100%" align="right"><?php echo CopixZone::process ('minimail|NewMinimail'); ?></td>
</tr>
<tr>
<td colspan="4" class="ecole-login-bar"><?php echo CopixZone::process ('auth|userlogged') ?><?php if (isset($HEADER_MODE) && $HEADER_MODE == "compact") { ?> | <a href="<?php echo CopixUrl::get ('kernel||getHome') ?>"><?php echo _i18n('kernel|kernel.message.moniconito') ?></a><?php } ?></td>
</tr>

<?php } else { ?>


<td class="ecole-right"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" border="0" width="17" height="17" alt="" /></td>
<td width="100%" align="right"><div class="annu_blog"><img src="<?php echo CopixUrl::getResource ("img/welcome/annu_blog.gif"); ?>" height="34" hspace="4" alt="<?php echo _i18n('public|public.blog.annuaire') ?>" border="0" align="right"/><a title="<?php echo _i18n('public|public.blog.annuaire') ?>" href="<?php echo CopixUrl::get ('public||') ?>"><?php echo _i18n('public|public.blog.annuaire') ?></a></div></td>
</tr>
<tr>
<td colspan="4" class="ecole-login-bar"><?php echo CopixZone::process ('auth|userlogged') ?></td>
</tr>
<?php } ?>
</table>



<?php if (!$user->isConnected() && CopixConfig::get ('default|isDemo')) { ?>
<div class="welcome_demo">

<table border="0" width="" cellspacing="1" cellpadding="1" align="center">
<tr><td rowspan="2" align="center"><img src="<?php echo CopixUrl::getResource ("img/welcome/icon_demo.gif"); ?>" align="left" alt="Logo Iconito" /></td><td colspan="5" align="center"><b>Bienvenue sur la d&eacute;mo d'Iconito, le portail num&eacute;rique scolaire libre. Pour vous connecter, cliquez ci-dessous sur un profil.</b></td></tr>
<tr>

<td class="account">Directeur/enseignant<br/><a href="javascript:login('pfranc','123456');">Pierre Franc</a></td>
<td class="account">Enseignante<br/><a href="javascript:login('mmeyer','123456');">Martine Meyer</a></td>
<td class="account">El&egrave;ve<br/><a href="javascript:login('jean','123456');">Jean Lenaick</a></td>
<td class="account">Parents de Jean<br/><a href="javascript:login('alenaick','123456');">M. Lenaick</a> et <a href="javascript: login('mlenaick','123456');">Mme Lenaick</a></td>
<td class="account">Agent de ville<br/><a href="javascript:login('mbraton','123456');">Marc Braton</a></td>


</tr>
</table>


<script type="text/javascript">
function login( nom, pass )
{
    monform = getRef('loginBar');
    monform.login.value = nom;
    monform.password.value = pass;
    monform.submit();
}
</script>
</div>
<?php } ?>





<?php if ($user->isConnected() && (!isset($HEADER_MODE) || $HEADER_MODE != "compact")) { ?>

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

<?php if ($user->hasAssistance()) { ?>
<div class="icobloc"><a class="icoa" href="<?php echo CopixUrl::get ('assistance||') ?>">
<img width="58" height="45" border="0" class="icoimg" src="<?php echo CopixUrl::getResource ("img/ico_assistance.gif"); ?>" alt="<?php echo _i18n('kernel|kernel.codes.mod_assistance') ?>" title="<?php echo _i18n('kernel|kernel.codes.mod_assistance') ?>"><br/><?php echo _i18n('kernel|kernel.codes.mod_assistance') ?></a></div>
<?php } ?>

</p>
</div>
<?php } else { ?>
<div class="icons_no"><img src="<?php echo CopixUrl::getResource ("img/spacer.gif"); ?>" height="1" width="1" alt="" /></div>
<?php } ?>


<div class="content">
<div class="title"><?php echo $TITLE_PAGE; ?></div>

<?php if (isset($MENU) && $MENU) { echo CopixZone::process ('kernel|menu', array('MENU'=>$MENU)); } ?>

<div class="main <?php $module = CopixRequest::get ('module'); if ($module) echo $module; ?>">
    <?php echo $MAIN; ?>
    <br clear="all" /><br clear="all" />
</div> <!-- fin main -->





</div> <!-- fin content -->



</div> <!-- fin page -->


<div id="footer">
<?php echo _i18n('public|public.nav.copyright') ?> | <a href="<?php echo CopixUrl::get ('aide||') ?>" title="<?php echo _i18n('public|public.aide') ?>"><b><?php echo _i18n('public|public.aide') ?></b></a> | <a href="<?php echo CopixUrl::get ('public||aPropos') ?>" title="<?php echo _i18n('public|public.apropos') ?>"><?php echo _i18n('public|public.apropos') ?></a>

<?php echo CopixZone::process ('kernel|footer') ?>
</div>

</body>

</html>





