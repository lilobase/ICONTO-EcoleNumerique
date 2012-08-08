<h3>{i18n key="sysutils|groupes.msg.bilan"}</h3>

{if $new_admin_check}
<table>
	<tr>
		<th>{i18n key="sysutils|groupes.col.login"}</th>
		<th>{i18n key="sysutils|groupes.col.action"}</th>
	</tr>
{foreach from=$new_admin_check item=admin key=login}
	<tr>
		<td>{$login}</td>
		<td>
			{if ! $admin|@count }
				{i18n key="sysutils|groupes.err.nologin"}
			{elseif ! $admin[0]->bu_type}
				{i18n key="sysutils|groupes.err.nobu"}
			{elseif $admin[0]->droit eq 70 }
				{i18n key="sysutils|groupes.err.admin"}
			{elseif $admin[0]->droit }
				{i18n key="sysutils|groupes.err.modright"}
			{else}
				{i18n key="sysutils|groupes.err.addright"}
			{/if}
		</td>
	</tr>
{/foreach}
</table>
{else}
<div>{i18n key="sysutils|groupes.err.nomod"}</div>
{/if}

<div>
<a class="button button-continue" href="{copixurl dest="sysutils|groupes|"}">{i18n key="sysutils|groupes.msg.backtogroupes"}</a>
</div>
