
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />
{assign var=id value=$ppo->id}
{assign var=folder value=$ppo->folder}
{assign var=folders value=$ppo->folders}
{assign var=files value=$ppo->files}
{assign var=errors value=$ppo->errors}
{assign var=field value=$ppo->field}
{assign var=format value=$ppo->format}
{assign var=combofolders value=$ppo->combofolders}


<form name="form" id="form">

<div id="popup_actions" class="block">

<h1>{i18n key="malle|malle.popup.options"}</h1>

<div class="bloc">
<h2>{i18n key="malle|malle.popup.mode"}</h2>
<input id="mode-view" type="radio" name="mode" value="view" checked /><label for="mode-view">{i18n key="malle|malle.popup.mode.view"}</label><br/>
<input id="mode-download" type="radio" name="mode" value="download" /><label for="mode-download">{i18n key="malle|malle.popup.mode.download"}</label><br/>
</div>

<div class="bloc">
<h2>{i18n key="malle|malle.popup.multi"}</h2>
<label for="multi-yes">
<input id="multi-yes" type="checkbox" name="multi" value="yes" />
<img src="{copixresource path="img/album/album_popup_multi.gif"}" alt="{i18n key="album|album.popup.multi_yes"}" /></label>
</div>

</form>

<div class="bloc">
<h2>{i18n key="malle|malle.popup.add"}</h2>
<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$uploadMaxSize}">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="format" value="{$format}" />
<INPUT class="form" style="margin: 2px;" TYPE="file" NAME="fichier" ></INPUT> <input style="" class="form_button" type="submit" value="{i18n key="malle.btn.submitAddFile"}" />
</form>

</div>

<br clear="all" />

</div>


<!-- DEBUT PAGE -->

<div class="malle_poucet_combo">

<DIV CLASS="malle_go_folder_form">
{if $combofolders|trim}
<form name="formGo" id="formGo" action="{copixurl dest="malle||getMallePopup"}" method="get">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="format" value="{$format}" />
{$combofolders}
<input type="submit" value="GO" class="form_button" /></form>
{/if}
</DIV>

{copixzone process='malle|petitpoucet' malle=$ppo->id folder=$ppo->folder action=getMallePopup field=$ppo->field format=$ppo->format}

</div>


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
	<DIV CLASS="malle_folder_line" style="width:90%;"><DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}</DIV>
	<IMG CLASS="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" /><A HREF="{copixurl dest="|getMallePopup" id=$id folder=$item->id field=$field format=$format}">{$item->nom|escape}</A>
	</DIV>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{else}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	
	{copixurl assign='copixurl'}
	
	{assign var=file value=$copixurl|cat:"static/malle/"|cat:$item->malle|cat:"_"|cat:$item->malle_cle|cat:"/"|cat:$item->id|cat:"_"|cat:$item->fichier}
	
	{if $format eq "fckeditor" OR $format eq "html" OR $format eq "ckeditor"}
		{*{assign var=htmlDownload value="[["|cat:$abspath|cat:$file|cat:"|download]]"}*}
		{assign var=htmlDownload value="[["|cat:$file|cat:"|download]]"}
		{*{assign var=htmlView value="[["|cat:$abspath|cat:$file|cat:"|view]]"}*}
		{assign var=htmlView value="[["|cat:$file|cat:"|view]]"}
	{/if}
	
	{i18n key="malle|malle.error.unsupportedFormat" format=$format assign=i18n_unsupportedFormat}

	<DIV CLASS="malle_file_line" style="width:90%;"><div style="float:right; margin-right:5px;"><a href="#" onClick="return sendDocument('{$file}', '{$field}', '{$format}', '{$htmlDownload|wiki|urlencode}', '{$htmlView|wiki|urlencode}', '{$i18n_unsupportedFormat|addslashes|escape}');">{i18n key="malle|malle.popup.select"}</a></div><DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{$item->type_text}</DIV>
	<img class="malle_file_line_img" src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" /><a href="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</a>
	</DIV>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
	{/foreach}
{else}
{/if}

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
			<a href="{copixurl dest="|getMalle" id=$id folder=$item->id}">{$item->nom|escape}</a>
		</td>
		<td class="malle-table-content">
			{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
		<td class="malle-table-action">
		</td>
	</tr>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	{copixurl assign='copixurl'}
	
	{assign var=file value=$copixurl|cat:"static/malle/"|cat:$item->malle|cat:"_"|cat:$item->malle_cle|cat:"/"|cat:$item->id|cat:"_"|cat:$item->fichier}
	
	{if $format eq "fckeditor" OR $format eq "html" OR $format eq "ckeditor"}
		{*{assign var=htmlDownload value="[["|cat:$abspath|cat:$file|cat:"|download]]"}*}
		{assign var=htmlDownload value="[["|cat:$file|cat:"|download]]"}
		{*{assign var=htmlView value="[["|cat:$abspath|cat:$file|cat:"|view]]"}*}
		{assign var=htmlView value="[["|cat:$file|cat:"|view]]"}
	{/if}
	
	{i18n key="malle|malle.error.unsupportedFormat" format=$format assign=i18n_unsupportedFormat}
	<tr class="malle-table-file">
		<td class="malle-table-icon">
			<img src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" />
		</td>
		<td class="malle-table-name">
			<a href="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</a>
		</td>
		<td class="malle-table-content">
			{$item->type_text}
		</td>
		<td class="malle-table-size">
			{$item->taille|human_file_size}
		</td>
		<td class="malle-table-action">
<a href="#" onClick="return sendDocument('{$file}', '{$field}', '{$format}', '{$htmlDownload|wiki|urlencode}', '{$htmlView|wiki|urlencode}', '{$i18n_unsupportedFormat|addslashes|escape}');">{i18n key="malle|malle.popup.select"}</a>
		</td>
	</tr>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
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
{/if}
</form>

{if !$folders|@count and !$files|@count}{i18n key="malle.emptyFolder"}{/if}



</DIV>
