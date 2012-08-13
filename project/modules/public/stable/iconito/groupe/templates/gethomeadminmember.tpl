<script language="Javascript1.2" src="{copixurl}js/iconito/module_groupe_admin.js"></script>

{if not $errors eq null}
	<div class="mesgErrors">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</div>
{/if}

<h2>{i18n key="groupe.adminMembers.updateRights" prenom=$user.prenom nom=$user.nom}</h2>
<form name="form" action="{copixurl dest="|doModifyMember"}" method="POST">
	<input type="hidden" name="id" value="{$groupe->id}" ></input>
	<input type="hidden" name="user_type" value="{$user.type}" ></input>
	<input type="hidden" name="user_id" value="{$user.id}" ></input>

<table class="editItems">
    <tr>
		<th>{i18n key="groupe.adminMembers.list.right"}</th>
        <td class="form_saisie">{select name="droit" values=$values selected=$his->droit extra='class="form"'}</td>
	</tr>
    <tr>
        <th>{i18n key="groupe.adminMembers.addDates.debut"}</th>
        <td>{inputtext class="datepicker" name="debut" value=$his->debut|datei18n}</td>
    </tr>
    <tr>
        <th>{i18n key="groupe.adminMembers.addDates.fin"}</th>
        <td>{inputtext class="datepicker" name="fin" value=$his->fin|datei18n}</td>
    </tr>
    <tr class="submit">
        <th></th>
        <td><input class="button button-cancel" onclick="self.location='{copixurl dest="|getHomeAdminMembers" id=$groupe->id}'" type="button" value="{i18n key="groupe.btn.cancel"}" /><input class="button button-confirm" type="submit" value="{i18n key="groupe.btn.save"}" /></td>
    </tr>
</table>


  </form>
  