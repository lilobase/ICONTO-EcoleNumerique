<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_forum.js"></SCRIPT>
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_forum.css"}" />

{$petitpoucet}

<form action="{copixurl dest="forum||doTopicForm"}" method="post">

<input type="hidden" name="forum" value="{$forum}" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="format" value="{$format}" />

{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL>
	</div>
{/if}

{if $preview and !$errors}
<div class="forum_message_preview">
<H3>{i18n key="forum.preview"}</H3>
<DIV CLASS="forum_message">
<DIV CLASS="forum_message_infos">{$titre}</DIV>
<DIV CLASS="forum_message_message">{$message|render:$format}</DIV>
</DIV>
</div>
{/if}

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="forum.form.title"}</td><td CLASS="form_saisie"><input type="text" name="titre" value="{$titre}" maxlength="150" style="width: 500px;" class="form" /></td>
	</tr>
	{if $id eq null}
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="forum.form.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>
	{/if}
	<tr><td colspan="2" CLASS="form_submit">{if $id eq null} <input class="button button-view" type="submit" onClick="goForum(this.form, 'preview');" value="{i18n key="forum.btn.preview"}" />{/if}<input class="button button-cancel" onclick="self.location='{copixurl dest="forum||getForum" id=$forum}'" type="button" value="{i18n key="forum.btn.cancel"}" /> <input class="button button-confirm" type="submit" onClick="goForum(this.form, 'save');" value="{i18n key="forum.btn.save"}" /></td></tr>

</table>

</form>
