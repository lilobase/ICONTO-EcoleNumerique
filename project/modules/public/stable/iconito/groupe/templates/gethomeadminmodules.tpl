<form action="{copixurl dest="|doFormAdminModules"}" method="post">
<input type="hidden" name="id" value="{$id}" />

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{elseif $done eq 1}
	<DIV CLASS="message_ok">
  {i18n key="groupe.ok.saveModules"}
	</DIV>
{/if}


<table border="0" CELLSPACING="1" CELLPADDING="1" width="700">
	<tr>
		<td CLASS="form_libelle"></td><td CLASS="form_saisie">{i18n key="groupe.adminModules.info"}<br/>
{if $modules neq null}
	{foreach from=$modules item=val_modules key=key_modules}
		{assign var="module_type_array" value="_"|split:$val_modules->module_type|lower}
		
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

	<tr><td colspan="2" CLASS="form_submit"><input style="width: 55px;" class="form_button" onclick="self.location='{copixurl dest="|getHomeAdmin" id=$id}'" type="button" value="{i18n key="groupe.btn.cancel"}" /> <input style="width: 75px;" class="form_button" type="submit" value="{i18n key="groupe.btn.save"}" /></td></tr>
	
</table>


</form>