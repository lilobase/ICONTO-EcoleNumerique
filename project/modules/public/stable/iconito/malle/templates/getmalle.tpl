<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />
<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_malle.js"></SCRIPT>



<DIV CLASS="malle_go_folder_form">
{if $combofolders}
<form name="formGo" id="formGo" action="{copixurl dest="malle||getMalle"}" method="get">
<input type="hidden" name="id" value="{$id}" />
{$combofolders}
<input type="submit" value="GO" class="form_button" /></form>
{/if}
</DIV>

{if $can.file_upload or $can.folder_create or $can.item_delete or $can.item_move or $can.item_copy or $can.item_rename or ($can.item_downloadZip and ($folders or $files))}

<DIV CLASS="malle_actions_bloc">

<div class="lucien"></div>

<DIV CLASS="block malle_actions">


{if $can.file_upload}
<HR />
{i18n key="malle.addFile"} <SPAN STYLE="font-size:85%;">{i18n key="malle.addFileTxt" 1=$uploadMaxSize|human_file_size}</SPAN>

<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$uploadMaxSize}">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />
<input class="form" style="margin: 2px;" type="file" name="fichier" style="width:190px;" size="12"></input>


<input style="" class="form_button" type="submit" value="{i18n key="malle.btn.submitAddFile"}" />
</form>



{/if}

{if $can.folder_create}
<HR />
{i18n key="malle.addFolder"}
<form action="{copixurl dest="|doAddFolder"}" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />
<input type="text" name="new_folder" value="{$new_folder}" maxlength="200" />
<input type="submit" value="{i18n key="malle.btn.submitAddFolder"}" class="form_button" />
</FORM>
{/if}

{if ($can.item_delete or $can.item_move or $can.item_copy or $can.item_rename or $can.item_downloadZip) and ($folders or $files)}
<HR />
{i18n key="malle.doActions"}

<form name="form" id="form" action="{copixurl dest="|doAction"}" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />
{if $can.item_delete}
<input type="submit" name="actionDelete" value="{i18n key="malle.btn.delete"}" class="form_button" onclick="return confirmDelete();" style="margin-top:2px;" />
{/if}
{if $can.item_rename}
<input type="submit" name="actionRename" value="{i18n key="malle.btn.rename"}" class="form_button" onclick="return confirmRename();" style="margin-top:2px;" />
{/if}
{if $can.item_downloadZip}
<input type="submit" name="actionDownloadZip" value="{i18n key="malle.btn.downloadZip"}" class="form_button" onclick="return confirmDownloadZip();" style="margin-top:2px;" />
{/if}

<br/><br/>


{if ($can.item_move or $can.item_copy) and $combofolders neq null}
	{if $can.item_move}<input type="submit" name="actionMove" value="{i18n key="malle.btn.move"}" class="form_button" />{/if} 
	{if $can.item_copy}<input type="submit" name="actionCopy" value="{i18n key="malle.btn.copy"}" class="form_button" />{/if}
	{$combofoldersdest}
{/if}


{/if}

</DIV>
</DIV>

{/if}


{$petitpoucet}

<DIV STYLE="min-height:275px;">


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

{if $folders neq null}
	{foreach from=$folders item=item}
	<DIV CLASS="malle_folder_line">{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}<DIV CLASS="malle_file_line_checked"><INPUT TYPE="checkbox" NAME="folders[]" VALUE="{$item->id}"></DIV>{/if}<DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}</DIV>
	<IMG CLASS="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" /><A HREF="{copixurl dest="|getMalle" id=$id folder=$item->id}">{$item->nom|escape}</A>
	</DIV>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{else}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	<DIV CLASS="malle_file_line">{if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip}<DIV CLASS="malle_file_line_checked"><INPUT TYPE="checkbox" NAME="files[]" VALUE="{$item->id}"></DIV>{/if}<DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{$item->type_text}</DIV>
	<img class="malle_file_line_img" src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" />{if $can.file_download}<A HREF="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</A>{else}{$item->nom|escape}{/if}
	</DIV>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
	{/foreach}
{else}
{/if}

</form>

{if !$folders|@count and !$files|@count}{i18n key="malle.emptyFolder"}{/if}

<DIV CLASS="malle_actions_line">

{i18n key="malle.folders" pNb=$folders|@count} {if $folders|@count}({$tailleFolders|human_file_size}){if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip} : {i18n key="malle.check"} <A HREF="javascript:cocherElements('form', 'folders[]', 1);">{i18n key="malle.checkAll"}</A> - <A HREF="javascript:cocherElements('form', 'folders[]', 0);">{i18n key="malle.checkNothing"}</A>{/if}{/if}
 | 
{i18n key="malle.files" pNb=$files|@count} {if $files|@count}({$tailleFiles|human_file_size}){if $can.item_delete or $can.item_move or $can.item_copy or $can.item_downloadZip} : {i18n key="malle.check"} <A HREF="javascript:cocherElements('form', 'files[]', 1);">{i18n key="malle.checkAll"}</A> - <A HREF="javascript:cocherElements('form', 'files[]', 0);">{i18n key="malle.checkNothing"}</A>{/if}{/if}
 

</DIV>

</DIV>
