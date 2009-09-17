{if $userlist neq null}
	<ol>
	{foreach from=$userlist item=user}
		<li>
		<b>{$user->user_nom} {$user->user_prenom}</b> ({$user->user_login})<br />
		{i18n key="kernel|kernel.getlist.nele"} {$user->user_ddn|datei18n:"date_short"}
		</li>
	{/foreach}
	</ol>
{else}
	{i18n key="kernel|kernel.getlist.aucunutilisateur"}
{/if}
