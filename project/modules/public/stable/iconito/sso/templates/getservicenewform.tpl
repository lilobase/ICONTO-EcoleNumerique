


<form action="{copixurl dest="sso||doServiceNewForm"}" method="post">

<input type="hidden" name="id" value="{$id}" />

{if not $errors eq null}
	<div class="mesgErrors">
	<ul>
	{foreach from=$errors item=error}
		<li>{$error}</li>
	{/foreach}
	</ul>
	</div>
{/if}

Pour ajouter un nouveau service externe ("SSO"), vous avez besoin de connaître l'adresse d'enregistrement du service (URL) et être en possession d'un compte utilisateur valide sur ce site distant.
<p></p>
Après validation du formulaire ci-desssous, vous serez redirigé vers le site distant, sur lequel vous devrez vous authentifier. Une fois l'authentification effectuée, vous serez redirigé sur Iconito et votre accès distant sera enregistré. Vous pourrez ensuite accéder au service distant d'un simple clic, sans re-authentification.
<p></p>
<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">URL</td><td CLASS="form_saisie"><input type="text" name="url" value="{$url}" maxlength="255" style="width: 350px;" class="form" /></td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">Type</td><td CLASS="form_saisie"><input type="text" name="type" value="{$type}" maxlength="10" style="width: 120px;" class="form" /></td>
	</tr>
	<tr><td colspan="2" CLASS="form_submit"><input class="form_button" onclick="self.location='{copixurl dest="sso||getSso" id=$id}'" type="button" value="Annuler" /> <input class="form_button" type="submit" value="Enregistrer" /></td></tr>
	
</table>
<p><p></p></p>


</form>
