
<a href="{copixurl dest="kernel|compte|form"}">{icon action="add"} Ajouter un compte</a>
<p></p>
<table class="list">
	<thead>
		<tr>
			<th>Login</th>
			<th>Type</th>
			<th>Action</th>
		</tr>
</thead>
<tbody>
	{foreach from=$ppo->list item=item}
	<tr class="line{cycle values="0,1"}">
		<td><a title="Modifier" href="{copixurl dest="kernel|compte|form" id_dbuser=$item->id_dbuser}">{$item->login_dbuser|escape}</a></td>
		<td>{$item->type_dbuser|escape}</td>
		<td><a title="Modifier" href="{copixurl dest="kernel|compte|form" id_dbuser=$item->id_dbuser}">{icon action="modify"}</a>{if $item->login_dbuser neq $ppo->login} <a title="Supprimer" href="{copixurl dest="kernel|compte|delete" id_dbuser=$item->id_dbuser}">{icon action="delete"}</a>{/if}</td>
	</tr>
	{/foreach}
</tbody>
</table>

{copixzone process=kernel|legende actions="modify,delete"}



