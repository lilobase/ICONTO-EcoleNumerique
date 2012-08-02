<p class="right"><a href="{copixurl dest="comptes|admins|new"}" class="button button-add">{i18n key="comptes.menu.new_role" noEscape=1}</a></p>

{if $admins}
<form method="post">
	<table width="100%" class="viewItems">
	<tr>
		<th class="liste_th">{i18n key="comptes.roles.table.nom"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.prenom"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.login"}</th>
		<th class="liste_th hidden">{i18n key="comptes.roles.table.idtech"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.role"}</th>
	</tr>
	{foreach from=$admins item=admin name=admin}
		<tr class="{if $smarty.foreach.admin.index % 2 == 0}odd{else}even{/if}">
			<td>{$admin->user_infos.nom}</td>
			<td>{$admin->user_infos.prenom}</td>
			<td>{$admin->user_infos.login}</td>
			<td class="hidden">{$admin->bu_type} / {$admin->bu_id}</td>
			<td>
				<select name="role[{$admin->id_dbuser}]"{if $admin->id_dbuser eq $user_id} disabled="disabled"{/if}{if $admin_fonctionnel && $admin->droit eq 70} disabled="disabled"{/if}>
					{if ! $admin_fonctionnel || $admin->droit eq 70}<option value="70"{if $admin->droit eq 70} selected="selected"{/if}>{i18n key="comptes.roles.table.super_admin"}</option>{/if}
					<option value="60"{if $admin->droit eq 60} selected="selected"{/if}>{i18n key="comptes.roles.table.fonct_admin"}</option>
					<option value="0" {if $admin->droit eq  0} selected="selected"{/if}>Aucun droits d'admin</option>
				</select>
				{if $admin->droit eq  0}A définir !{/if}
			</td>
		</tr>
	{/foreach}
	</table>
	<p class="right"><input class="button button-save" type="submit" class="button_like" value="Enregistrer" /></p>
</form>
{else}
	<i>{i18n key="comptes.roles.table.no_admin"}</i>
{/if}
