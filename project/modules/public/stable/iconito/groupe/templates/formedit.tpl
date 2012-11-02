<form action="{copixurl dest="|doEdit"}" method="post">
    <input type="hidden" name="id" value="{$id}" />
    <input type="hidden" name="parentClass" value="{$parentClass}" />
    <input type="hidden" name="parentRef" value="{$parentRef}" />
{if not $errors eq null}
    <div id="dialog-message" title="{if $id}{i18n key=groupe.modify}{else}{i18n key=groupe.new}{/if}">
        <ul>
	{foreach from=$errors item=error}
            <li>{$error}</li>
	{/foreach}
        </ul>
    </div>
{/if}

    <h2>{i18n key="groupe.form.adminTitle"}</h2>

    <div>
        <table border="0" CELLSPACING="1" CELLPADDING="1" width="98%">
            <tr>
                <td CLASS="form_libelle">{i18n key="groupe.form.title"}</td><td CLASS="form_saisie"><input type="text" name="titre" value="{$titre}" class="form" style="width: 400px;" maxlength="100" /></td>
            </tr>
            <tr>
                <td CLASS="form_libelle">{i18n key="groupe.form.tags"}</td><td CLASS="form_saisie"><input type="text" name="tags" value="{$tags}" class="form" style="width: 400px;" maxlength="100" /></td>
            </tr>
            <tr>
                <td CLASS="form_libelle">{i18n key="groupe.form.desc"}</td><td CLASS="form_saisie"><textarea class="form" style="width: 400px; height: 80px;" name="description" />{$description}</textarea></td>
            </tr>
  {if $id eq null}
            <tr>
                <td CLASS="form_libelle">{i18n key="groupe.form.rattachement"}</td><td CLASS="form_saisie">
    {foreach from=$nodes item=node}
      {assign var=value value=$node.type|cat:'|'|cat:$node.id}
                    <input type="radio" id="parent_{$value}" name="parent" value="{$value}"{if $parent eq $value}checked="checked"{/if}><label for="parent_{$value}"> {$node.nom|escape} {if $node.desc}({$node.desc|escape}){/if}</label><br/>
    {/foreach}
                </td>
            </tr>
  {/if}
{if $can_group_createpublic}
            <tr>
                <td CLASS="form_libelle">{i18n key="groupe.form.restriction"}</td><td CLASS="form_saisie">
                    <INPUT TYPE="radio" NAME="is_open" VALUE="1" {if $is_open eq "1"}CHECKED{/if}> <b>{i18n key="groupe.isOpen1"}</b> : {i18n key="groupe.form.isOpen1Info"}<br/>
                    <INPUT TYPE="radio" NAME="is_open" VALUE="0" {if $is_open eq "0"}CHECKED{/if}> <b>{i18n key="groupe.isOpen0"}</b> : {i18n key="groupe.form.isOpen0Info"}<br/>
                </td>
            </tr>
{else}
<INPUT TYPE="hidden" NAME="is_open" VALUE="0" />
{/if}

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
        </table>
    </div>

    <div class="center">
        <a class="button button-cancel" href="{if $id eq null}{copixurl dest="||"}{else}{copixurl dest="|getHomeAdmin" id=$id}{/if}">{i18n key="groupe.btn.cancel"} </a> <input class="button button-save" type="submit" value="{i18n key="groupe.btn.save"}" />
    </div>
</form>
