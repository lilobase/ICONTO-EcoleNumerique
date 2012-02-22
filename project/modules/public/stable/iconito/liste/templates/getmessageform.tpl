<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_liste.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_minimail.css"}" />

<form action="{copixurl dest="liste||doMessageForm"}" method="post">

<input type="hidden" name="liste" value="{$liste}" />
<input type="hidden" name="go" value="preview" />

{if not $errors eq null}
	<div class="mesgErrors">
        <ul>
        {foreach from=$errors item=error}
            <li>{$error}</li>
        {/foreach}
        </ul>
	</div>
{/if}

{if $preview and !$errors}
<H3>{i18n key="liste.btn.preview"}</H3>
<DIV CLASS="minimail_message">
<DIV><b>{$titre}</b></DIV>
<HR CLASS="minimail_hr" NOSHADE SIZE="1" />
<DIV>{$message|render:$format}</DIV>
</DIV>
{/if}

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP"></td><td CLASS="form_saisie"><h3>{i18n key="liste.homeWriteMessage"}</h3></td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP"><label for="titre">{i18n key="liste.field.title"}</label></td>
        <td CLASS="form_saisie"><input type="text" name="titre" id="titre" value="{$titre}" maxlength="150" style="width:99%;" class="form" /></td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="liste.field.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>
</table>

<div class="submit">
	<input class="button button-cancel" onclick="self.location='{copixurl dest="liste||getListe" id=$liste}'" type="button" value="{i18n key="liste.btn.cancel"}" /> <input class="button button-view" type="submit" onClick="goListe(this.form, 'preview');" value="{i18n key="liste.btn.preview"}" /> <input class="button button-confirm" type="submit" onClick="goListe(this.form, 'save');" value="{i18n key="liste.btn.save"}" />
</div>
</form>
