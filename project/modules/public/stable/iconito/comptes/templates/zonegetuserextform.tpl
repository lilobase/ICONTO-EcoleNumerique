<form action="{copixurl dest="comptes|default|getUserExtMod" id=$user->ext_id}" method="post">

<input type="hidden" name="mode" value="{$mode}" />

{if $errors}
<div class="mesgErrors">
 {ulli values=$errors}
</div>
{/if}
<table border="0" CELLSPACING="1" CELLPADDING="1" width="" align="center">
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.nom"   } : </td><td CLASS="form_saisie"><input type="text" name="nom"    value="{$user->ext_nom|escape:'html'   }" class="form" style="width: 400px;" />
		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.prenom"} : </td><td CLASS="form_saisie"><input type="text" name="prenom" value="{$user->ext_prenom|escape:'html'}" class="form" style="width: 400px;" /></td>
	</tr>
	
	{if !$user->ext_id}
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.login"} : </td><td CLASS="form_saisie"><input type="text" name="login" value="{$user->ext_login|escape:'htmlall'}" class="form" style="width: 400px;" {if $user->ext_id}disabled="disabled"{/if} />
		</td>
	</tr>
	{/if}
	
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.passwd1"} : </td><td CLASS="form_saisie"><input type="password" name="passwd1" value="" class="form" style="width: 400px;" />
		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="comptes.colonne.passwd2"} : </td><td CLASS="form_saisie"><input type="password" name="passwd2" value="" class="form" style="width: 400px;" />
		</td>
	</tr>
	<tr><td></td><td class="form_submit">
		<a class="button button-cancel" href="{copixurl dest="comptes||getUserExt"}">{i18n key="comptes.form.cancel"}</a>
		<input class="button button-save" type="submit" value="{i18n key="comptes.form.submit"}" />
	</td></tr>
	
</table>

</form>