<?xml version="1.0" encoding="iso-8859-1"?>
<comptes>
{if $logins neq null}
{foreach from=$logins item=login}
	<compte>
		<nom>{$login.nom}</nom>
		<prenom>{$login.prenom}</prenom>
		<login>{$login.login}</login>
		<passwd>{$login.passwd}</passwd>
		<type>{$login.type_nom}</type>
		<localisation>{$login.node_nom}</localisation>
	</compte>
{/foreach}
{/if}
</comptes>
