<?php if ($ppo->failed) { ?>
<div class="mesgErrors">
  <?php _etag ('ulli', array ('values'=>$ppo->failed)); ?>
</div>
<?php } ?>
<?php if (!$ppo->user || $ppo->noCredential) { ?>
    <?php if ($ppo->createUser) { ?>
    <p><a href="<?php echo _url("auth|usersregister|edit"); ?>">
    <?php echo _i18n('auth|auth.user.create'); ?></a></p>
    <?php } ?>
  <form action="<?php echo _url("auth|log|in", ($ppo->noCredential) ? array('noCredential'=>true):array()); ?>" method="post" id="loginForm">
      <fieldset id="loginForm">
      <table>

       <tr>
        <th><label for="loginBig"><?php echo _i18n('auth|auth.login'); ?></label></th>
        <td><input type="text" name="login" id="loginBig" size="9" autofocus="autofocus"
            value="<?php _etag ('escape', $ppo->login); ?>" /></td>
       </tr>

       <tr>
        <th><label for="passwordBig"><?php echo _i18n('auth|auth.password'); ?></label></th>
        <td><input type="password" name="password" id="passwordBig" size="9" /></td>
       </tr>

       <?php if($ppo->ask_rememberme){ ?>
       <tr>
        <th><label for="rememberme"><?php echo _i18n('auth|auth.rememberme'); ?></label></th>
        <td><input type="checkbox" name="rememberme" id="rememberme" value="yes" /></td>
       </tr>
       <?php } ?>

       <tr>
       <th></th>
       <td>

       <?php if ($ppo->auth_url_return) { ?>
          <input type="hidden" value="<?php echo htmlentities ($ppo->auth_url_return); ?>" name="auth_url_return" />
       <?php } ?>
       <input type="submit" class="button button-confirm" value="<?php echo _i18n ("copix:common.buttons.login"); ?>" />

       </td>
       </tr>
       </table>

       </fieldset>
   </form>
<?php }
      if ($ppo->user) {
?>
    <p><?php echo _i18n('auth.connectedAs', array ('login'=>$ppo->user->getCaption ())); ?><br />
    <a
    href="<?php echo _url("auth|log|out", array ('auth_url_return'=>$ppo->auth_url_return)); ?>">
<img src="<?php echo _resource ("img/tools/logout.png"); ?>"
    alt="<?php echo _i18n('copix:common.buttons.login'); ?>"
    title="<?php echo _i18n ('copix:common.buttons.logout'); ?>" /></a></p>
<?php }