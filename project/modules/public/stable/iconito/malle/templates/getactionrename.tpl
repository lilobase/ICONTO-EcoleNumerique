<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />
<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_malle.js"></SCRIPT>


{if $folders neq null or $files neq null}
<DIV>

<DIV>{i18n key="malle.rename.intro"}</DIV>

<form action="{copixurl dest="|doActionRename"}" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />


	<DIV CLASS="malle_file_line" STYLE="font-size:70%; font-style:italic;"><DIV CLASS="new_name">{i18n key="malle.rename.newName"}</DIV>{i18n key="malle.rename.oldName"}</DIV>

{if $folders neq null}
	{foreach from=$folders item=item}
	<DIV CLASS="malle_folder_line"><DIV CLASS="new_name"><input type="text" name="newFolders[{$item->id}]" value="{$item->nom}" maxlength="200" /></DIV><IMG CLASS="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" />{$item->nom|escape} 
	</DIV>
	{/foreach}
{else}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	<DIV CLASS="malle_file_line"><DIV CLASS="new_name"><input type="text" name="newFiles[{$item->id}]" value="{$item->nom}" maxlength="200" /></DIV><IMG CLASS="malle_file_line_img" src="{copixresource path="img/malle/`$item->type_icon`"}" />{$item->nom|escape}
	</DIV>
	{/foreach}
{else}
{/if}

<DIV style="text-align: right; width:485px; padding:4px;">
<input type="button" value="{i18n key="malle.btn.cancel"}" class="form_button" onclick="self.location='{copixurl dest="|getMalle" id=$id folder=$folder}'" /> <input type="submit" value="{i18n key="malle.btn.valid"}" class="form_button" />
</DIV>


</form>

</DIV>

{/if}



