{if $user eq null}
<!-- <a href="{copixurl dest="auth||login"}">Connexion</a> -->
<div style="font-size:90%">
<form action="{copixurl dest="auth||in"}" method="post" id="loginBar">

<input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />

{i18n key=auth|auth.login} : <input type="text" name="login" id="login" size="12" value="{$login}" />
&nbsp;
{i18n key=auth|auth.password} : <input type="password" name="password" id="password" size="12" />


{if $showRememberMe}{i18n key=auth|auth.rememberMe} : <input type="checkbox" name="rememberMe" id="rememberMe" value="1" />{/if}

		 <input type="submit" class="submit" value="{i18n key="auth.buttons.login"}" />
   </form>


{if $showLostPassword}
|
<a href="{copixurl dest="auth||lostPasswordAsk"}">{i18n key=auth|auth.lostPassword}</a>
{/if}
</div>
{else}
{i18n key=auth|auth.connected.bonjour login=$login}
(<a href="{copixurl dest="auth||out"}" title="{i18n key=auth|auth.buttons.logout}">{i18n key=auth|auth.buttons.logout}</a>)
 |
<a href="{copixurl dest="prefs||"}" title="{i18n key=auth|auth.nav.prefs}">{i18n key=auth|auth.nav.prefs}</a> 
 |
<a href="{copixurl dest="aide||"}" title="{i18n key=auth|auth.nav.aide}">{i18n key=auth|auth.nav.aide}</a> 
{/if}
