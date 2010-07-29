{assign var=id value=$ppo->id}
{assign var=folder value=$ppo->folder}
{assign var=folders value=$ppo->folders}
{assign var=files value=$ppo->files}
{assign var=errors value=$ppo->errors}
{assign var=field value=$ppo->field}
{assign var=format value=$ppo->format}
{assign var=combofolders value=$ppo->combofolders}

<div class="popup-title">{i18n key="malle|malle.popup.file.title"}</div>
	
	<div class="popup-subtitle">{i18n key="malle|malle.popup.file.upload"}</div>
	<div id="popup-actions" class="content-panel">
		<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
			<input type="hidden" name="MAX_FILE_SIZE" value="{$uploadMaxSize}">
			<input type="hidden" name="id" value="{$id}" />
			<input type="hidden" name="folder" value="{$folder}" />
			<input type="hidden" name="field" value="{$field}" />
			<input type="hidden" name="format" value="{$format}" />
			<input class="file" type="file" name="fichier" />
			<input class="button button-continue" type="submit" value="{i18n key="malle|malle.popup.file.load"}" />
		</form>
	</div>

	<div class="popup-subtitle">{i18n key="malle|malle.popup.file.available"}</div>
	<div class="malle-breadcrumb">
		<div class="malle_go_folder_form">
			{if $combofolders|trim}
			<form name="formGo" id="formGo" action="{copixurl dest="malle||getMallePopup"}" method="get">
				<input type="hidden" name="id" value="{$id}" />
				<input type="hidden" name="field" value="{$field}" />
				<input type="hidden" name="format" value="{$format}" />
				{$combofolders}
				<input type="submit" value="GO" class="form_button" />
			</form>
			{/if}
		</div>
		{copixzone process='malle|petitpoucet' malle=$ppo->id folder=$ppo->folder action=getMallePopup field=$ppo->field format=$ppo->format}
	</div>

	<div class="content-panel">
		{if not $errors eq null}
		<div class="message_erreur">
			<ul>
			{foreach from=$errors item=error}
			<li>{$error}</li><br/>
			{/foreach}
			</ul>
		</div>
		{/if}

		{assign var="tailleFolders" value=0}
		{assign var="tailleFiles" value=0}

		{if $folders neq null}
			{foreach from=$folders item=item}
			<div class="malle_folder_line" style="width:90%;">
				<div class="malle_file_line_size">{$item->taille|human_file_size}</div>
				<div class="malle_file_line_type">{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}</div>
				<img class="malle_folder_line_img" src="{copixresource path="img/malle/icon_folder.png"}" />
				<a href="{copixurl dest="|getMallePopup" id=$id folder=$item->id field=$field format=$format}">{$item->nom|escape}</a>
			</div>
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
			<div class="malle_file_line" style="width:90%;">
				<div style="float:right; margin-right:5px;">
<!--
<a href="#" onClick="return sendDocument('{$file}', '{$field}', '{$format}', '{$htmlDownload|wiki|urlencode}', '{$htmlView|wiki|urlencode}', '{$i18n_unsupportedFormat|addslashes|escape}');">
-->
					<a href="#" onClick="parent.add_text('{$field}', '{$file}'); parent.jQuery.fancybox.close();">
					{i18n key="malle|malle.popup.file.select"}
					</a>
				</div>
				<div class="malle_file_line_size">{$item->taille|human_file_size}</div>
				<div class="malle_file_line_type">{$item->type_text}</div>
				<img class="malle_file_line_img" src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|escape}" title="{$item->type_text|escape}" />
				<a href="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|escape}</a>
			</div>
			{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
			{/foreach}
		{else}
		{/if}
		
		{if !$folders|@count and !$files|@count}{i18n key="malle.emptyFolder"}{/if}
	</div>
</div>