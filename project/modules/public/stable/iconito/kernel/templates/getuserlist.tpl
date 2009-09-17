{if $userlist neq null}
	<ol>
	{foreach from=$userlist item=user}
		<li>
		{$user->pers_civilite} <b>{$user->pers_nom} {$user->pers_prenom1}</b> ({$user->pers_numero})<br />
		</li>
	{/foreach}
	</ol>
{else}
	{i18n key="kernel|kernel.getuserlist.erreur"}
{/if}