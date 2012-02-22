<form action="{copixurl dest="|doFormAdminModules"}" method="post">
<input type="hidden" name="id" value="{$id}" />

{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL>
	</div>
{elseif $done eq 1}
	<div id="dialog-message" title="">
  {i18n key="groupe.ok.saveModules"}
	</div>
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

	
</table>

<div class="center"><a href="{copixurl dest="|getHomeAdmin" id=$id}" class="button button-cancel">{i18n key="groupe.btn.cancel"}</a> <input class="button button-save" type="submit" value="{i18n key="groupe.btn.save"}" /></div>
</form>
