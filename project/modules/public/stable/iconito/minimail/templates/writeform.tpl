<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_minimail.js"></SCRIPT>

<form action="{copixurl dest="minimail||doSend"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$attachment_size}">
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="format" value="{$format}" />

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL></DIV>
{/if}

{if $preview and !$errors}
<H3>{i18n key="minimail.preview"}</H3>
<DIV CLASS="minimail_message">
<DIV><b>{$title}</b></DIV>
<HR CLASS="minimail_hr" NOSHADE SIZE="1" />
<DIV>{$message|render:$format}</DIV>
</DIV>
{/if}

<br/>
<table border="0" CELLSPACING="1" CELLPADDING="1" width="700">
	<tr>
		<td CLASS="form_libelle"><NOBR>{i18n key="minimail.form.dest"}{help mode="bulle" text="minimail|minimail.help.dest"}</NOBR></td><td CLASS="form_saisie"><input type="text" name="dest" id="dest" value="{$dest|escape:'htmlall'}" class="form" style="width: 400px;" maxlength="200"/>{$linkpopup}<br/>{i18n key="minimail.form.destInfo"}</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.title"}</td><td CLASS="form_saisie"><input type="text" name="title" value="{$title|escape:'htmlall'}" class="form" style="width: 400px;" maxlength="80" /></td>
	</tr>
	{*
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.message"}</td><td CLASS="form_saisie"><textarea class="form" style="width: 600px; height: 200px;" name="message" id="message">{$message|escape:'htmlall'}</textarea>{$wikibuttons}
	</td>
	</tr>
	*}
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>
	
	<tr>
		<td CLASS="form_libelle">{i18n key="minimail.form.attachments"}</td><td CLASS="form_saisie">
		
		{i18n key="minimail.form.attachmentsInfo" 1=$attachment_size|human_file_size} 
	<br/>1. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment1" ></INPUT>
	<br/>2. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment2" ></INPUT>
	<br/>3. <INPUT class="form" style="margin: 2px;" TYPE="file" NAME="attachment3" ></INPUT>
		
		</td>
	</tr>
	<tr><td colspan="2" CLASS="form_submit"><input style="" class="form_button" onclick="self.location='{copixurl dest="minimail||getListRecv"}'" type="button" value="{i18n key="minimail.btn.cancel"}" /> <input style="" class="form_button" type="submit" onClick="goMinimail(this.form, 'save');" value="{i18n key="minimail.btn.send"}" /> <input style="" class="form_button" type="submit" onClick="goMinimail(this.form, 'preview');" value="{i18n key="minimail.btn.preview"}" /></td></tr>
	
</table>
<p><p></p></p>


</form>
