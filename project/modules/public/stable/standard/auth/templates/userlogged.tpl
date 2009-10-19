

{if $ppo->user->isConnected ()}


{assign var=login value=$ppo->user->getLogin()}
{assign var=nom value=$ppo->user->getExtra('nom')}
{assign var=prenom value=$ppo->user->getExtra('prenom')}

{if $ppo->animateur eq 1}
<span style="background-color: red; padding-left: 20px; padding-right: 20px; font-weight: bold; margin-right: 10px;">Vous avez pris le contr&ocirc;le du compte de {$login}</span> 
{else}
	{i18n key=auth|auth.connected.bonjour login=$prenom|cat:' '|cat:$nom}
{/if}

({if $ppo->animateur eq 1}<a href="{copixurl dest="assistance||switch"}" style="color: red;">Stopper la prise de contr&ocirc;le</a>{else}<a href="{copixurl dest="auth|log|out"}" title="{i18n key=auth|auth.buttons.logout}">{i18n key=auth|auth.buttons.logout}</a>{/if})
 |
<a href="{copixurl dest="prefs||"}" title="{i18n key=auth|auth.nav.prefs}">{i18n key=auth|auth.nav.prefs}</a> 
 |
<a href="{copixurl dest="aide||"}" title="{i18n key=auth|auth.nav.aide}">{i18n key=auth|auth.nav.aide}</a> 

		
{else}


		<div style="font-size:90%">
<form action="{copixurl dest="auth|log|in"}" method="post" id="loginBar">

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

{/if}