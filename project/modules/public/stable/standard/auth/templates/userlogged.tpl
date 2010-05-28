{if $ppo->user->isConnected ()}
	
	{assign var=login value=$ppo->user->getLogin()}
	{assign var=nom value=$ppo->user->getExtra('nom')}
	{assign var=prenom value=$ppo->user->getExtra('prenom')}
	
	{if $ppo->animateur eq 1}
		<span style="background-color: red; padding-left: 20px; padding-right: 20px; font-weight: bold; margin-right: 10px;">Vous avez pris le contr&ocirc;le du compte de {$login}</span> 
	{else}
		<span class="username">{$prenom} {$nom}</span><br/>
		<span class="userrole">{$ppo->usertype}</span>
	{/if}

{else}

	<form action="{copixurl dest="auth|log|in"}" method="post" id="loginBar">
		{i18n key=auth|auth.login} : <input type="text" name="login" id="login" size="12" value="{$login}" />
		&nbsp;
		{i18n key=auth|auth.password} : <input type="password" name="password" id="password" size="12" />
		{if $showRememberMe}
			{i18n key=auth|auth.rememberMe} : <input type="checkbox" name="rememberMe" id="rememberMe" value="1" />
		{/if}
		<input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />
		<input type="submit" class="submit" value="{i18n key="auth.buttons.login"}" />
	</form>

	{if $showLostPassword}
		<a href="{copixurl dest="auth||lostPasswordAsk"}">{i18n key=auth|auth.lostPassword}</a>
	{/if}

{/if}