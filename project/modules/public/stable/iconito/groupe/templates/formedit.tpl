<form action="{copixurl dest="|doEdit"}" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="parentClass" value="{$parentClass}" />
<input type="hidden" name="parentRef" value="{$parentRef}" />
{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}


<table border="0" CELLSPACING="1" CELLPADDING="1" width="98%">
	<tr>
		<td CLASS="form_libelle">{i18n key="groupe.form.title"}</td><td CLASS="form_saisie"><input type="text" name="titre" value="{$titre}" class="form" style="width: 400px;" maxlength="100" /></td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="groupe.form.desc"}</td><td CLASS="form_saisie"><textarea class="form" style="width: 400px; height: 80px;" name="description" />{$description}</textarea></td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="groupe.form.restriction"}</td><td CLASS="form_saisie">
		<INPUT TYPE="radio" NAME="is_open" VALUE="1" {if $is_open eq "1"}CHECKED{/if}> <b>{i18n key="groupe.isOpen1"}</b> : {i18n key="groupe.form.isOpen1Info"}<br/>
		<INPUT TYPE="radio" NAME="is_open" VALUE="0" {if $is_open eq "0"}CHECKED{/if}> <b>{i18n key="groupe.isOpen0"}</b> : {i18n key="groupe.form.isOpen0Info"}<br/>
		</td>
	</tr>
	{if $id eq null}
	<tr>
		<td CLASS="form_libelle">{i18n key="groupe.form.modules"}</td><td CLASS="form_saisie">{i18n key="groupe.form.modulesInfo"}<br/>
{if $modules neq null}
	{foreach from=$modules item=val_modules key=key_modules}
		{assign var="module_type_array" value="_"|split:$val_modules->module_type|lower}
		{if $id neq null}{assign var="disabled" value="disabled"}{else}{assign var="disabled" value=""}{/if}
		{ assign var="a" value=$val_modules->module_type }
		{if $his_modules.$a eq 1}{assign var="checked" value="checked"}{else}{assign var="checked" value=""}{/if}
    <div style="margin-top:3px;">
		<INPUT TYPE="CHECKBOX" id="id_mod_{$val_modules->module_type}" NAME="his_modules[{$val_modules->module_type}]" {$disabled} {$checked} VALUE="1" /><label for="id_mod_{$val_modules->module_type}"> <b>{$val_modules->module_name}</b> : {$val_modules->module_desc}</label>
    </div>
	{/foreach}
{else}
	{i18n key="groupe.noModule"}
{/if}



		</td>
	</tr>
	<tr>
		<td CLASS="form_libelle">{i18n key="groupe.form.members"}</td><td CLASS="form_saisie">{i18n key="groupe.form.membersInfo"}<br/>
<textarea class="form" style="width: 400px; height: 50px;" name="membres" id="membres">{$membres}</textarea><br/>{$linkpopup}</td>
	</tr>
	{/if}
	<tr><td colspan="2" CLASS="form_submit"><input style="width: 55px;" class="form_button" onclick="self.location='{if $id eq null}{copixurl dest="|getListMy"}{else}{copixurl dest="|getHomeAdmin" id=$id}{/if}'" type="button" value="{i18n key="groupe.btn.cancel"}" /> <input style="width: 75px;" class="form_button" type="submit" value="{i18n key="groupe.btn.save"}" /></td></tr>
	
</table>

</form>