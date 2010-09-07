<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />
{assign var=id value=$ppo->id}
{assign var=folder value=$ppo->folder}
{assign var=folders value=$ppo->folders}
{assign var=files value=$ppo->files}
{assign var=errors value=$ppo->errors}
{assign var=field value=$ppo->field}
{assign var=format value=$ppo->format}
{assign var=combofolders value=$ppo->combofolders}

{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
	var dofile;
	var dofield;
	var doformat;
	var dodurl;
	var dovurl;
	var doerr;
	$('#doinsert').click (function() {
		var domode = $('#options input[name="mode"]:checked').val();
		$('#malle :checked').each( function() {
			dofile = $(this).parent('.malle-table-action').children('input[name="item-file"]').val();
			dofield = $(this).parent('.malle-table-action').children('input[name="item-field"]').val();
			doformat = $(this).parent('.malle-table-action').children('input[name="item-format"]').val();
			dodurl = $(this).parent('.malle-table-action').children('input[name="item-durl"]').val();
			dovurl = $(this).parent('.malle-table-action').children('input[name="item-vurl"]').val();
			doerr = $(this).parent('.malle-table-action').children('input[name="item-err"]').val();
//			console.log("---" + dofile + "," + dofield + "," + doformat + "," + dodurl + "," + dovurl + "," + doerr);
			insertDocument (domode, dofile, dofield, doformat, dodurl, dovurl, doerr);
		});
		parent.jQuery.fancybox.close();
	});

	$('.addfile').click (function() {
		$('.addfile').toggle();
		$('.addfile-form').toggle();
	});
	$('.addfile-form .button-cancel').click (function() {
		$('.addfile').toggle();
		$('.addfile-form').toggle();
	});
	$('#docancel').click (function() {
		parent.jQuery.fancybox.close();
	});
});
</script>
{/literal}
<div id="results"></div>
<h1>{i18n key="malle.popup.title"}</h1>

<div class="malle_poucet_combo">
{copixzone process='malle|petitpoucet' malle=$ppo->id folder=$ppo->folder action=getMallePopup field=$ppo->field format=$ppo->format}
</div>

<div class="popup-panel">

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

{if !$folders|@count and !$files|@count}
	{i18n key="malle.emptyFolder"}
{else}
	
<form>
<table id="malle" class="malle-table">
{if $folders neq null}
	{foreach from=$folders item=item}
	<tr class="malle-table-folder">
		<td class="malle-table-icon">
			<IMG src="{copixresource path="img/malle/icon_folder.png"}" />
		</td>
		<td class="malle-table-name">
			<a href="{copixurl dest="|getMallePopup" id=$id folder=$item->id}">{$item->nom|escape}</a>
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
		{assign var=htmlDownload value="[["|cat:$file|cat:"|download]]"}
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
			<input type="hidden" name="item-file" value="{$file}"/>
			<input type="hidden" name="item-field" value="{$field}"/>
			<input type="hidden" name="item-format" value="{$format}"/>
			<input type="hidden" name="item-durl" value="{$htmlDownload|wiki|urlencode}"/>
			<input type="hidden" name="item-vurl" value="{$htmlView|wiki|urlencode}"/>
			<input type="hidden" name="item-err" value="{$i18n_unsupportedFormat|addslashes}"/>
			<input type="checkbox" name="item-check" class="" value=""/>
		</td>
	</tr>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
	{/foreach}
{/if}
</table>
</form>
{/if}

<table class="malle-table">
	<tr class="malle-table-add">
		<td class="malle-table-icon">
			<IMG src="{copixresource path="images/action_add.png"}" />
		</td>
		<td class="malle-table-name">
			<a class="addfile" style="cursor: hand;">{i18n key="malle|malle.popup.add"}</a>
			<div class="addfile-form" style="display: none;">
				<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
					<input type="hidden" name="MAX_FILE_SIZE" value="{$uploadMaxSize}">
					<input type="hidden" name="id" value="{$id}" />
					<input type="hidden" name="folder" value="{$folder}" />
					<input type="hidden" name="field" value="{$field}" />
					<input type="hidden" name="format" value="{$format}" />
					<input class="form" style="margin: 2px;" TYPE="file" NAME="fichier" ></input>
					<input class="button button-confirm" type="submit" value="{i18n key="malle.popup.add.ok"}" />
					<input class="button button-cancel" type="button" value="{i18n key="malle.popup.add.cancel"}" />
				</form>
			</div>
		</td>
		<td class="malle-table-content"></td>
		<td class="malle-table-size"></td>
		<td class="malle-table-action"></td>
	</tr>
</table>

</div>

<div id="popup_actions" class="content-panel">
	<div class="floatright">
		<input id="doinsert" class="button button-confirm" type="button" value="{i18n key="malle.popup.doinsert"}" />
		<input id="docancel" class="button button-cancel" type="button" value="{i18n key="malle.popup.cancel"}" />
	</div>
	<div class="">
		<form name="form" id="options">
			{i18n key="malle|malle.popup.mode"}
			<input id="mode-view" type="radio" name="mode" value="view" checked /><label for="mode-view">{i18n key="malle|malle.popup.mode.view"}</label>
			<input id="mode-download" type="radio" name="mode" value="download" /><label for="mode-download">{i18n key="malle|malle.popup.mode.download"}</label>
		</form>
	</div>
</div>


