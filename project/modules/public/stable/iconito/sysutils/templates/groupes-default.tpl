{if $groupes_array|@count}
	<table width="100%">
		<tr>
			<th>Id</th>
			<th>Nom du groupe</th>
			<th>Admins</th>
			<th>Actions</th>
		</tr>
		{foreach from=$groupes_array item=groupes_item}
		<tr>
			<td>{$groupes_item->groupe_id}</td>
			<td><b>{$groupes_item->groupe_titre}</b></td>
			<td>
				{if $groupes_item->admins|@count}
					<ul>
					{foreach from=$groupes_item->admins item=admin}
						<li>{$admin->admin_nom} {$admin->admin_prenom} ({$admin->admin_login}) {* <a href="{copixurl dest="sysutils|groupes|del_admin" groupe=$groupes_item->groupe_id login=$admin->admin_id}">DEL</a> *}</li>
					{/foreach}
					</ul>
				{else}
					<i>Aucun admin</i>
				{/if}
			</td>
			<td>
				<a href="{copixurl dest="sysutils|groupes|add_admin" groupe=$groupes_item->groupe_id}">Ajouter un administrateur</a>
			</td>
		</tr>
		{/foreach}
	</table>
{else}
	<i>Aucun groupe...</i>
{/if}