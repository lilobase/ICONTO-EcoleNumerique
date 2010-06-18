<form action="{copixurl dest="comptes|default|getUserExtMod" id=$user->ext_id}" method="post">

<input type="hidden" name="mode" value="{$mode}" />

<table border="0" CELLSPACING="1" CELLPADDING="1" width="" align="center">
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.nom"   } : </td><td CLASS="form_saisie"><input type="text" name="nom"    value="{$user->ext_nom|escape:'htmlall'   }" class="form" style="width: 400px;" />
		{if $errors.ext_nom}<br />{i18n key="comptes.error.text"} {$errors.ext_nom}{/if}
		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.prenom"} : </td><td CLASS="form_saisie"><input type="text" name="prenom" value="{$user->ext_prenom|escape:'htmlall'}" class="form" style="width: 400px;" /></td>
	</tr>
	
	{if !$user->ext_id}
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.login"} : </td><td CLASS="form_saisie"><input type="text" name="login" value="{$user->ext_login|escape:'htmlall'}" class="form" style="width: 400px;" {if $user->ext_id}disabled="disabled"{/if} />
		{if $errors.login}<br />{i18n key="comptes.error.text"} {$errors.login}{/if}
		</td>
	</tr>
	{/if}
	
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.passwd1"} : </td><td CLASS="form_saisie"><input type="text" name="passwd1" value="" class="form" style="width: 400px;" />
		{if $errors.passwd1}<br />{i18n key="comptes.error.text"} {$errors.passwd1}{/if}
		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.passwd2"} : </td><td CLASS="form_saisie"><input type="text" name="passwd2" value="" class="form" style="width: 400px;" />
		{if $errors.passwd2}<br />{i18n key="comptes.error.text"} {$errors.passwd2}{/if}
		</td>
	</tr>
	
	<tr><td colspan="2" CLASS="form_submit"><input style="" class="form_button" type="submit" value="{i18n key="comptes.form.submit"}"/></td></tr>
	
</table>

</form>