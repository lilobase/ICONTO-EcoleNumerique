<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />

{literal}
<script type="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function($){

	$('#folder-checkall').click (function() { $('#remote-checker :checkbox[name="folders[]"]').attr('checked', true); });
	$('#folder-checknone').click (function() { $('#remote-checker :checkbox[name="folders[]"]').attr('checked', false); });
	$('#file-checkall').click (function() { $('#remote-checker :checkbox[name="files[]"]').attr('checked', true); });
	$('#file-checknone').click (function() { $('#remote-checker :checkbox[name="files[]"]').attr('checked', false); });
	
});
</script>
{/literal}

{$petitpoucet}

<div style="min-height:275px;">

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL></DIV>
{/if}

{assign var="tailleFolders" value=0}
{assign var="tailleFiles" value=0}

<form id="remote-checker">

{if !$folders|@count and !$files|@count}
	{i18n key="malle.emptyFolder"}
{else}
	
<table id="#form-replicator" class="malle-table">
{if $folders neq null}
	{foreach from=$folders item=item}
	<tr class="malle-table-folder">
		<td class="malle-table-icon">
			<IMG src="{copixresource path="img/malle/icon_folder.png"}" />
		</td>
		<td class="malle-table-name">
			<A HREF="{copixurl dest="|getMalle" id=$id folder=$item->id}">{$item->nom|escape}</A>
		</td>
		<td class="malle-table-edit">
			<A HREF="{copixurl dest="|getMalle" id=$id folder=$item->id}"></A>
		</td>
		<td class="malle-table-content">
			{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
		<td class="malle-table-action">
			{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			<INPUT TYPE="checkbox" NAME="folders[]" VALUE="{$item->id}">
			{/if}
		</td>
	</tr>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	<tr class="malle-table-file">
		<td class="malle-table-icon">
			<img src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" />
		</td>
		<td class="malle-table-name">
			{if $can.file_download}<A HREF="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</A>{else}{$item->nom|escape}{/if}
		</td>
		<td class="malle-table-edit">
			{if $can.file_download}{$item->nom|escape}{/if}
		</td>
		<td class="malle-table-content">
			{$item->type_text}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
		<td class="malle-table-action">
			{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			<INPUT TYPE="checkbox" NAME="files[]" VALUE="{$item->id}">
			{/if}
		</td>
	</tr>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{/if}
</table>

<div class="malle-footer">
	{i18n key="malle.folders" pNb=$folders|@count}
	{if $folders|@count}({$tailleFolders|human_file_size})
		{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			: {i18n key="malle.check"} 
			<input type="button" class="button" id="folder-checkall" value="{i18n key="malle.checkAll"}">
			<input type="button" class="button" id="folder-checknone" value="{i18n key="malle.checkNothing"}">
		{/if}
	{/if}
	 |
	{i18n key="malle.files" pNb=$files|@count}
	{if $files|@count}({$tailleFiles|human_file_size})
		{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}
			: {i18n key="malle.check"}
			<input type="button" class="button" id="file-checkall" value="{i18n key="malle.checkAll"}">
			<input type="button" class="button" id="file-checknone" value="{i18n key="malle.checkNothing"}">
		{/if}
	{/if}
</div>

</form>
{/if}

</div>