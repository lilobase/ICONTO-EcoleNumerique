
<form action="{copixurl dest="carnet||doMessageForm"}" method="post">

<input type="hidden" name="topic" value="{$topic}" />
<input type="hidden" name="eleve" value="{$eleve}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="format" value="{$format}" />


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
<DIV CLASS="forum_message_preview">
<H3>{i18n key="carnet.preview"}</H3>
<DIV CLASS="forum_message">
<DIV CLASS="forum_message_infos">{$titre}</DIV>
<DIV CLASS="forum_message_message">{$message|render:$format}</DIV>
</DIV>
</DIV>
{/if}

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	{*
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.message"}</td><td CLASS="form_saisie"><textarea style="width: 500px; height: 180px;" class="form" name="message" id="message">{$message}</textarea>{$wikibuttons}</td>
	</tr>
	*}
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>
	
	<tr><td colspan="2" CLASS="form_submit"><input class="button button-cancel" onclick="self.location='{copixurl dest="carnet||getTopic" id=$topic eleve=$eleve}'" type="button" value="{i18n key="carnet.btn.cancel"}" /> <input class="button button-save" type="submit" onClick="submitTopicForm(this.form, 'save');" value="{i18n key="carnet.btn.save"}" /> <input class="button button-view" type="submit" onClick="submitTopicForm(this.form, 'preview');" value="{i18n key="carnet.btn.preview"}" /></td></tr>
	
</table>

</form>
