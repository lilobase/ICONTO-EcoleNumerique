{if $ppo->user->isConnected ()}
	
	{assign var=login value=$ppo->user->getLogin()}
	{assign var=nom value=$ppo->user->getExtra('nom')}
	{assign var=prenom value=$ppo->user->getExtra('prenom')}
	
	{if $ppo->animateur eq 1}
		<div class="prise_controle">
			<div class="message">Vous avez pris le contr&ocirc;le<br/>du compte de {$login}</div>
			<a class="stopper" href="{copixurl dest="assistance||switch"}">Stopper la prise de contr&ocirc;le</a>
		</div> 
	{else}
        <div class="userprofile">
            <span class="username">{$prenom} {$nom}</span><br/>
            <span class="userrole">{$ppo->usertype}</span>
        </div>
	{/if}

{else}
    <div class="userlogon">
        <form action="{copixurl dest="auth|log|in"}" method="post" id="loginBar">
            <input type="hidden" name="auth_url_return" id="auth_url_return" value="{$url}" />
            <div class="loginPrompt">
                <span class="loginMsg">{i18n key=auth|auth.text.logon}<br />{if (false || $canNewAccount) }{i18n key=auth|auth.text.newAccount}<br/>{/if}</span>
                <input id="login" type="text" name="login" class="login default-value label-overlayed" value="{i18n key=auth|auth.login}"
                 /><input id="password-password" class="login" type="password" name="password" value=""
                 /><input id="password-clear" class="login label-overlayed" type="text" value="{i18n key=auth|auth.password}"
                 /><input type="submit" class="button button-confirm" value="" />
            </div>
        {if (false || $canNewAccount) } // TODO: lire conf pour savoir si on autorise la demande de compte, sur cette ligne et 4 lignes plus haut
            <div class="loginNew">
                <a class="usr-newaccount" alt="{i18n key=auth|auth.newAccount}" title="{i18n key=auth|auth.newAccount}" href="{copixurl dest="public|default|getreq"}"></a>
            </div>
        {/if}
        </form>
    </div>
{/if}