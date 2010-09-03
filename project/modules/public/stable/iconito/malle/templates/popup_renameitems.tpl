<form name="renameitems" id="renameitems" action="{copixurl dest="|doActionRename"}" method="post">
	<h1 style="width: 600px;">{i18n key="malle.rename.txt"}</h1>
	<input type="hidden" name="id" value="{$ppo->id}" />
	<input type="hidden" name="folder" value="{$ppo->folder}" />

	<table id="#form-replicator">
	<tr class="malle-tableheader">
		<td>{i18n key="malle.rename.newName"}</td>
		<td>{i18n key="malle.rename.oldName"}</td>
	</tr>
	</table>

{if $folders neq null}
	{foreach from=$folders item=item}
	<DIV class="malle_folder_line"><DIV CLASS="new_name"><input type="text" name="newFolders[{$item->id}]" value="{$item->nom}" maxlength="200" /></DIV><IMG CLASS="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" />{$item->nom|escape} 
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

{literal}
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){
	var folders = $('#remote-checker :checked[name="folders[]"]').serializeArray();
	var folderCount = 0;
	jQuery.each(folders, function(i, folder){
		if (folder.name) $('#form-replicator').append('<tr><td><img class="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" /></td>' + folder.value + '</tr>');
		folderCount++;
	});

	var files = $('#remote-checker :checked[name="files[]"]').serializeArray();
	var fileCount = 0;
	jQuery.each(files, function(i, file){
		if (file.name) $('#form-replicator').append('<input type="text" name="files[]" value="' + file.value + '"/>');
		fileCount++;
	});
	
	if (fileCount==0 && folderCount==0) $('form#movefile').submit();
	else $('#movefile').show();
	
});
</script>
{/literal}
