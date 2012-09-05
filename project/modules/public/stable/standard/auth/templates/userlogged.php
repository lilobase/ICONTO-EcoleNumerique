<div id="user_logged">

    <?php if ($ppo->user->isConnected ()): ?>

        <p>
            Utilisateur : <strong><?php echo $ppo->user->getLogin ();

             ?></strong> <?php if ($ppo->user->getIdPersonnel()) echo '('.trim($ppo->user->getExtra('prenom').' '.$ppo->user->getExtra('nom')).')'; ?> - <a href="<?php echo CopixUrl::get ('auth|log|out') ?>">Se d&eacute;connecter</a>
        </p>

    <?php else: ?>

        <div style="font-size:90%">
<form action="{copixurl dest="auth||in"}" method="post" id="loginBar">

<input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />

{i18n key=auth|auth.login} : <input type="text" name="login" id="login" size="12" value="{$login}" />
&nbsp;
{i18n key=auth|auth.password} : <input type="password" name="password" id="password" size="12" />


{if $showRememberMe}{i18n key=auth|auth.rememberMe} : <input type="checkbox" name="rememberMe" id="rememberMe" value="1" />{/if}

         <input type="submit" class="submit button" value="{i18n key="auth.buttons.login"}" />
   </form>


{if $showLostPassword}
|
<a href="{copixurl dest="auth||lostPasswordAsk"}">{i18n key=auth|auth.lostPassword}</a>
{/if}
</div>

    <?php endif ?>

</div>