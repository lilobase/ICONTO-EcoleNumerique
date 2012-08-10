{if $groupes_array|@count}
	<table class="viewItems">
		<tr>
			<th>Nom du groupe</th>
			<th>Propriétaires</th>
			<th>Actions</th>
		</tr>
		{foreach from=$groupes_array item=groupes_item}
		<tr class="{cycle values="odd,even"}">
			<td><strong>{$groupes_item->groupe_titre}</strong></td>
			<td>
				{if $groupes_item->admins|@count}
					<ul>
					{foreach from=$groupes_item->admins item=admin}
						<li>{$admin->admin_nom} {$admin->admin_prenom} ({$admin->admin_login}) {* <a href="{copixurl dest="sysutils|groupes|del_admin" groupe=$groupes_item->groupe_id login=$admin->admin_id}">DEL</a> *}</li>
					{/foreach}
					</ul>
				{else}
					<em>Aucun propriétaire</em>
				{/if}
			</td>
			<td>
				<a class="button button-add" href="{copixurl dest="sysutils|groupes|add_admin" groupe=$groupes_item->groupe_id}">Ajouter un propriétaire</a>
			</td>
		</tr>
		{/foreach}
	</table>
{else}
	<em>Aucun groupe</em>
{/if}