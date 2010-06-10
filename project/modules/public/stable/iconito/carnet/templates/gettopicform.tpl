
<form action="{copixurl dest="carnet||doTopicForm"}" method="post" name="form" id="form">

<input type="hidden" name="classe" value="{$classe}" />
<input type="hidden" name="eleve" value="{$eleve}" />
<input type="hidden" name="go" value="preview" />
<input type="hidden" name="format" value="{$format}" />

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
<H3>{i18n key="carnet.preview"}</H3>
<DIV CLASS="forum_message">
<DIV CLASS="forum_message_infos">{$titre}</DIV>
<DIV CLASS="forum_message_message">{$message|render:$format}</DIV>
</DIV>
</DIV>
{/if}

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.concern"}</td><td CLASS="form_saisie">
	<DIV>
	{if $canWriteClasse}{i18n key="carnet.list.allClasseTxt"}<br clear="all" />{/if}
	{foreach from=$hisEleves item=item}
		{assign var="itemid" value=$item.id}
		<div class="checkEleve">{*<LABEL FOR="eleves_{$item.id}">*}<input type="checkbox" id="eleves_{$item.id}" name="eleves[]" value="{$item.id}"{if !$eleves or $eleves.$itemid === 0 or $eleves.$itemid>0}CHECKED{/if}> {user label=$item.prenom|cat:' '|cat:$item.nom userType=$item.type userId=$item.id linkAttribs='STYLE="text-decoration:none;"' login=$item.login dispMail=0}{*</LABEL>*}
		</div>
	{/foreach}
  
  {if $canWriteClasse}<div class="checkEleve"><A HREF="javascript:checkAllClasse();"><b>{i18n key="carnet.list.allClasse"}</b></A></div>{/if}
  
  
	</DIV>
		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.title"}</td><td CLASS="form_saisie"><input type="text" name="titre" value="{$titre}" maxlength="150" style="width: 500px;" class="form" /></td>
	</tr>
	{*
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.message"}</td><td CLASS="form_saisie"><textarea name="message" id="message" style="width: 500px; height: 180px;" class="form">{$message}</textarea>{$wikibuttons}</td>
	</tr>
	*}
	<tr>
		<td CLASS="form_libelle" VALIGN="TOP">{i18n key="carnet.form.message"}</td><td CLASS="form_saisie">{$message_edition}</td>
	</tr>

	<tr><td colspan="2" CLASS="form_submit"><input style="" class="form_button" onclick="self.location='{copixurl dest="carnet||getCarnet" classe=$classe eleve=$eleve}'" type="button" value="{i18n key="carnet.btn.cancel"}" /> <input style="" class="form_button" type="submit" onClick="submitTopicForm(this.form, 'save');" value="{i18n key="carnet.btn.save"}" /> <input style="" class="form_button" type="submit" onClick="submitTopicForm(this.form, 'preview');" value="{i18n key="carnet.btn.preview"}" /></td></tr>
	
</table>

</form>
