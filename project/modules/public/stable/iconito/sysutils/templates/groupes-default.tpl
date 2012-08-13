{if $new_admin_check}
{foreach from=$new_admin_check item=admin key=login}
	{if ! $admin|@count }
		<p class="mesgError">{i18n key="sysutils|groupes.err.nologin" val=$login noEscape=1}</p>
	{elseif ! $admin[0]->bu_type}
		<p class="mesgError">{i18n key="sysutils|groupes.err.nobu" val=$login noEscape=1}</p>
	{elseif $admin[0]->droit eq 70 }
		<p class="mesgInfo">{i18n key="sysutils|groupes.err.admin" val=$login noEscape=1}</p>
	{elseif $admin[0]->droit }
		<p class="mesgSuccess">{i18n key="sysutils|groupes.err.modright" val=$login noEscape=1}</p>
	{else}
		<p class="mesgSuccess">{i18n key="sysutils|groupes.err.addright" val=$login noEscape=1}</p>
	{/if}
{/foreach}
{/if}

{if $groupes_array|@count}
	<table class="viewItems">
		<tr>
			<th>{i18n key="sysutils|groupes.msg.groupe"}</th>
			<th>{i18n key="sysutils|groupes.msg.owner"}</th>
			<th>{i18n key="sysutils|groupes.col.action"}</th>
		</tr>
		{foreach from=$groupes_array item=groupes_item}
		<tr class="{cycle values="odd,even"}">
			<td><strong>{$groupes_item->groupe_titre}</strong></td>
			<td>
				{if $groupes_item->admins|@count}
					<ul>
					{foreach from=$groupes_item->admins item=admin}
						<li>{$admin->admin_prenom} {$admin->admin_nom} ({$admin->admin_login}) {* <a href="{copixurl dest="sysutils|groupes|del_admin" groupe=$groupes_item->groupe_id login=$admin->admin_id}">DEL</a> *}</li>
					{/foreach}
					</ul>
				{else}
					<em>{i18n key="sysutils|groupes.err.noowner"}</em>
				{/if}
			</td>
			<td>
				<a class="button button-add" href="{copixurl dest="sysutils|groupes|add_admin" groupe=$groupes_item->groupe_id}">{i18n key="sysutils|groupes.msg.addowner"}</a>
			</td>
		</tr>
		{/foreach}
	</table>
{else}
	<em>{i18n key="sysutils|groupes.err.nogroup"}</em>
{/if}