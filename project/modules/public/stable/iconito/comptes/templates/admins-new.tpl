<form enctype="multipart/form-data" method="post" action="{copixurl dest="comptes|admins|"}">

<p>{i18n key="comptes.roles.message.new"}</p>
<textarea id="new_admins" class="form" rows="2" cols="78" name="new_admins"></textarea>
{$linkpopup}
<p class="center">
<a class="button button-cancel" href="{copixurl dest="comptes|admins|"}">Annuler</a>
<input class="button button-confirm" type="submit" value="Valider">
</p>

</form>
