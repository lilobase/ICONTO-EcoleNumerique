{literal}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>

<head>
<title>{/literal}{$TITLE_PAGE}{literal}</title>
<link href="styles/iconito.css" rel="stylesheet" type="text/css" media="screen" />
<link href="styles/module_annuaire.css" rel="stylesheet" type="text/css" media="screen" />
<link rel="SHORTCUT ICON" href="favicon.ico">
<script type="text/javascript" language="Javascript1.2" src="js/iconito.js"></script>
<script type="text/javascript" language="Javascript1.2" src="js/lang_{/literal}{$LANGUE}{literal}.js"></script>
<script type="text/javascript" language="Javascript1.2" src="js/prototype-1.4.0.js"></script>
</head>

<body>

<div id="divUserProfil" onclick="hideUser();"></div>

<div class="page" style="width:680px;border:0;margin:0;margin-left: auto;	margin-right: auto;">

<div class="content">
{/literal}
<div class="title">{$TITLE_PAGE}</div>

<div class="options"><a href="javascript:self.close();">{i18n key="kernel|kernel.popup.close"}</a></div>

<div class="main malle kernel">





<div id="popup_actions">
<form name="form" id="form">

{* <h1>{i18n key="malle|malle.popup.options"}</h1> *}

<div class="bloc">
<h2>{i18n key="malle|malle.popup.mode"}</h2>
<input id="mode-view" type="radio" name="mode" value="view" checked />
<label for="mode-view">{i18n key="malle|malle.popup.mode.view"}<br/></label>
<input id="mode-download" type="radio" name="mode" value="download" />
<label for="mode-download">{i18n key="malle|malle.popup.mode.download"}<br/>
</label>
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


<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />
<SCRIPT LANGUAGE="Javascript1.2" SRC="js/malle/malle.js"></SCRIPT>



<DIV CLASS="malle_go_folder_form">
{if $combofolders|trim}
<form name="formGo" id="formGo" action="" method="get">
<input type="hidden" name="module" value="malle" />
<input type="hidden" name="action" value="getMallePopup" />
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="field" value="{$field}" />
<input type="hidden" name="format" value="{$format}" />
{$combofolders}
<input type="submit" value="GO" class="form_button" /></form>
{/if}
</DIV>

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
	<DIV CLASS="malle_folder_line" style="width:90%;"><DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{i18n key="malle.files" pNb=$item->nb_files}, {i18n key="malle.folders" pNb=$item->nb_folders}</DIV>
	<IMG CLASS="malle_folder_line_img" SRC="img/malle/icon_folder.png" /><A HREF="{copixurl dest="|getMallePopup" id=$id folder=$item->id field=$field format=$format}">{$item->nom|htmlentities}</A>
	</DIV>
	{math equation="x+y" x=$tailleFolders y=$item->taille assign="tailleFolders"}
	{/foreach}
{else}
{/if}

{if $files neq null}
	{foreach from=$files item=item}
	
	{assign var=file value="{copixurl}static/malle/"|cat:$item->malle|cat:"_"|cat:$item->malle_cle|cat:"/"|cat:$item->id|cat:"_"|cat:$item->fichier}
	
	{if $format eq "fckeditor" OR $format eq "html"}
		{*{assign var=htmlDownload value="[["|cat:$abspath|cat:$file|cat:"|download]]"}*}
		{assign var=htmlDownload value="[["|cat:$file|cat:"|download]]"}
		{assign var=htmlView value="[["|cat:$abspath|cat:$file|cat:"|view]]"}
	{/if}
	
	{i18n key="malle|malle.error.unsupportedFormat" format=$format assign=i18n_unsupportedFormat}

	<DIV CLASS="malle_file_line" style="width:90%;"><div style="float:right; margin-right:5px;"><a href="#" onClick="return sendDocument('{$file}', '{$field}', '{$format}', '{$htmlDownload|wiki|urlencode}', '{$htmlView|wiki|urlencode}', '{$i18n_unsupportedFormat|addslashes|escape}');">{i18n key="malle|malle.popup.select"}</a></div><DIV CLASS="malle_file_line_size">{$item->taille|human_file_size}</DIV><DIV CLASS="malle_file_line_type">{$item->type_text}</DIV>
	<img class="malle_file_line_img" src="{copixresource path="img/malle/`$item->type_icon`"}" alt="{$item->type_text|htmlentities}" title="{$item->type_text|htmlentities}" /><a href="{copixurl dest="|doDownloadFile" id=$id file=$item->id}">{$item->nom|htmlentities}</a>
	</DIV>
	{math equation="x+y" x=$tailleFiles y=$item->taille assign="tailleFiles"}
	{/foreach}
{else}
{/if}

</form>

{if !$folders|@count and !$files|@count}{i18n key="malle.emptyFolder"}{/if}



</DIV>



<!-- FIN PAGE -->
{literal}

<br clear="all"/>
</div>

</div><!-- content -->
</div><!-- page -->
</body>
</html>
{/literal}