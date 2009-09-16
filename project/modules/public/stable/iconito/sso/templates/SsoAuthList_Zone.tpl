{literal}<link rel="stylesheet" type="text/css" href="styles/module_forum.css" />{/literal}

<div style="float:right;"><a href="{copixurl dest="|getServiceNewForm" id=$id}" class="button_like">{i18n key="sso.button.addService"}</a></div>

<p>{i18n key="sso.liste.titre"}</p>

{if $auth_list neq null}
<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th" width="1"><nobr>{i18n key="sso.liste.type"}</nobr></th>
		<th CLASS="liste_th">{i18n key="sso.liste.url"}</th>

	</tr>

		{counter assign="i" name="i"}
		
		{foreach from=$auth_list item=auth_item}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td align="right">{$auth_item->sso_auth_type|capitalize}</td>
				<td align="left"><div style="float:right; font-size:85%;"><a href="{copixurl dest="|doDeleteService" id=$auth_item->sso_auth_id}">supprimer</a></div><a target="_blank" href="{copixurl dest="|doSso" id=$auth_item->sso_auth_id}">{$auth_item->sso_auth_url}</a> (<i>{$auth_item->sso_auth_login_distant}</i>)</td>
			</tr>
		{/foreach}
</table>

{else}
<p>{i18n key="sso.liste.vide"}</p>
{/if}
