<div>
<!-- A remplacer par un annuaire interfacé GAEL -->
Vous pouvez consulter <a href="http://demo-gael.dev.iconito.fr">GAEL</a> pour obtenir les listes d'élèves, de classes, d'écoles, etc. GAEL signifie Gestion administrative des élèves, mais gère en réalité toutes les données relatives aux parents, écoles, classes, enseignants, services vie scolaire, etc.<p>
</div>
{literal}<SCRIPT LANGUAGE="Javascript1.2" SRC="js/carnet/carnet.js"></SCRIPT>{/literal}

<form action="{copixurl dest="comptes||doListForm"}" method="post">
<input type="hidden" name="classe" value="{$classe}" />
<input type="hidden" name="go" value="preview" />

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}


{if $preview and !$errors}
<DIV CLASS="forum_message_preview">
<H3>Prévisualisation</H3>
<DIV CLASS="forum_message">
<DIV CLASS="forum_message_infos">{$titre}</DIV>
		<DIV CLASS="forum_message_message">{$message|wiki}</DIV>
</DIV>
{/if}


<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">Concerne</td><td CLASS="form_saisie">

	<select name="eleve" CLASS="form">
	{if $canWriteClasse}
	<option value="" {if !$classe}SELECTED{/if}>Toute la classe</option>
	{/if}
	{foreach from=$hisEleves item=item}
	<option value="{$item.id}" {if $eleve eq $item.id}SELECTED{/if}>{$item.prenom} {$item.nom}</option>
	{/foreach}
	</select>

		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">Titre</td><td CLASS="form_saisie"><input type="text" name="titre" value="{$titre}" maxlength="150" style="width: 500px;" class="form" /></td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">Premier message</td><td CLASS="form_saisie"><textarea name="message" style="width: 500px; height: 180px;" class="form">{$message}</textarea></td>
	</tr>
	<tr><td colspan="2" CLASS="form_submit"><input style="width: 55px;" class="form_button" onclick="self.location='{copixurl dest="carnet||getCarnet" classe=$classe eleve=$eleve}'" type="button" value="Annuler" /> <input style="width: 75px;" class="form_button" type="submit" onClick="submitTopicForm(this.form, 'save');" value="Enregistrer" /> <input style="width: 75px;" class="form_button" type="submit" onClick="submitTopicForm(this.form, 'preview');" value="Aperçu" /></td></tr>
	
</table>
<p><p></p></p>


</form>
