<form method="post" action="{copixurl dest="comptes||setUserPasswd" node_type=$node.type node_id=$node.id login=$user.login}">
	<input type="hidden" name="from" id="from" value="{$from}" />
	<fieldset>
		<legend>{i18n key="comptes|comptes.strings.modpasswd" 1=$user.prenom 2=$user.nom 3=$user.login}</legend>
		<table border="0">
			<tr>
				<th><label for="passwd1">{i18n key="comptes|comptes.strings.modpasswd_new"}</label></th>
				<td><input type="password" name="passwd1" id="passwd1" /></td>
			</tr>
			<tr>
				<th><label for="passwd2">{i18n key="comptes|comptes.strings.modpasswd_new2"}</label></th>
				<td><input type="password" name="passwd2" id="passwd2" /></td>
			</tr>
		</table>
{if $error eq "tooshortpassword"}
<div class="error">{i18n key="comptes|comptes.strings.modpasswd_tooshort"}</div>
{/if}

{if $error eq "notsamepassword"}
<div class="error">{i18n key="comptes|comptes.strings.modpasswd_different"}</div>
{/if}
		<p><input type="submit" value="{i18n key="comptes|comptes.form.submit"}" /></p>
	</fieldset>
</form>