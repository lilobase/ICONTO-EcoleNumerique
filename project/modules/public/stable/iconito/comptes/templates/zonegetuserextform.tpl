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
	<tr><td colspan="2" CLASS="form_submit"><input style="" class="form_button" type="submit" value="{i18n key="comptes.form.submit"}"/></td></tr>
	
</table>

</form>