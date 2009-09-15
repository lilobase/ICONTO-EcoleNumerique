<script language="Javascript1.2" src="js/groupe/groupe_admin.js"></script>

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}

  <form name="form" action="{copixurl dest="|doModifyMember"}" method="POST">
	<input type="hidden" name="id" value="{$groupe->id}" ></input>
	<input type="hidden" name="user_type" value="{$user.type}" ></input>
	<input type="hidden" name="user_id" value="{$user.id}" ></input>
  
  
<table border="0" cellspacing="1" cellpadding="1" width="">
	<tr>
		<td class="form_libelle">{i18n key="groupe.adminMembers.list.right"}</td><td class="form_saisie">

{select name="droit" values=$values selected=$his->droit extra='class="form"'}
</td>
	</tr>

<tr><td class="form_libelle">{i18n key="groupe.adminMembers.addDates.debut"}</td><td>{calendar name="debut" value=$his->debut|datei18n} {i18n key="kernel|date.format"}</td></tr>
<tr><td class="form_libelle">{i18n key="groupe.adminMembers.addDates.fin"}</td><td>{calendar name="fin" value=$his->fin|datei18n} {i18n key="kernel|date.format"}</td>


	<tr><td colspan="2" class="form_submit"><br/><input style="" class="form_button" onclick="self.location='{copixurl dest="|getHomeAdminMembers" id=$groupe->id}'" type="button" value="{i18n key="groupe.btn.cancel"}" /> <input style="" class="form_button" type="submit" value="{i18n key="groupe.btn.save"}" /></td></tr>
	
</table>

  </form>
  