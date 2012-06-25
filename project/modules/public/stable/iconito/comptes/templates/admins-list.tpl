<p class="right"><a href="{copixurl dest="comptes|animateurs|new"}" class="button button-add">{i18n key="comptes.menu.new_animateur" noEscape=1}</a></p>

{if $admins}
<form method="post">
	<table width="100%" class="liste comptes_animateurs comptes_animateurs_list">
	<tr>
		<th class="liste_th">{i18n key="comptes.roles.table.login"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.nom"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.prenom"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.idtech"}</th>
		<th class="liste_th">{i18n key="comptes.roles.table.role"}</th>
	</tr>
	{foreach from=$admins item=admin name=admin}
		<tr class="{if $smarty.foreach.animateurs.first}first{/if}{if $smarty.foreach.animateurs.last} last{/if}">
			<td>{$admin->user_infos.login}</td>
			<td>{$admin->user_infos.nom}</td>
			<td>{$admin->user_infos.prenom}</td>
			<td>{$admin->user_type} / {$admin->user_id}</td>
			<td>
				<select name="role[{$admin->id_dbuser}]"{if $admin->id_dbuser eq $user_id} disabled="disabled"{/if}>
					<option value="70"{if $admin->droit eq 70} selected="selected"{/if}>{i18n key="comptes.roles.table.super_admin"}</option>
					<option value="60"{if $admin->droit eq 60} selected="selected"{/if}>{i18n key="comptes.roles.table.fonct_admin"}</option>
					<option value="0">Supprimer les droits d'admin</option>
				</select>
			</td>
		</tr>
	{/foreach}
	</table>
	<p class="right"><input class="button button-save" type="submit" class="button_like" value="Enregistrer" /></p>
</form>
{else}
	<i>{i18n key="comptes.roles.table.no_admin"}</i>
{/if}
