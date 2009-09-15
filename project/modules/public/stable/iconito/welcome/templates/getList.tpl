<p>{i18n key="welcome.text.intro"}</p>

<table><tr><td>URL</td><td>Action</td></tr>

{if $url_list neq null}
	{foreach from=$url_list item=url_item}
	<form method="post" action="{copixurl dest="welcome||doUrlDel"}">
	<input type="hidden" name="homepage" value="{$homepage}" />
	<input type="hidden" name="url" value="{$url_item->url}" />
	<tr>
		<td>{$url_item->url}</td>
		<td><input type="submit" value="Suppr."/></td>
	</tr>
	</form>
	{/foreach}
{/if}
	
	<form method="post" action="{copixurl dest="welcome||doUrlAdd"}">
	<input type="hidden" name="homepage" value="{$homepage}" />
	<tr>
		<td><input type="text" name="url"/></td>
		<td><input type="submit" value="Ajouter"/></td>
	</tr>
	</form>

</table>
