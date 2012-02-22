
{* {i18n key="album.folder.title"} *}

{if $dossiermenu neq null}
{assign var=level value=0}
{foreach from=$dossiermenu item=valeur}
	{* {if $level > 0} :: {/if} *}
	<a class="button{if $valeur.type} button-{$valeur.type}{/if}" href="{$valeur.url}"{if $valeur.onclick} onclick="{$valeur.onclick}"{/if}>{$valeur.txt}</a>
	{assign var=level value=`$level+1`}
{/foreach}
{/if}

{if $dossiermenu neq null}

<script type="text/javascript">
{literal}
<!--
function openbox( name ) {
	if( name != 'folder_new' ) $('#folder_new').hide();
	if( name != 'folder_move' ) $('#folder_move').hide();
	if( name != 'folder_rename' ) $('#folder_rename').hide();
	if( name != 'folder_delete' ) $('#folder_delete').hide();
	$('#'+name).toggle();
}
//-->
{/literal}
</script>

<div class="album">
<div id="folder_new" class="folder_action displayNone">

	<form name="folder_new" action="{copixurl dest="album||dofolder"}" method="get">
	<input type="hidden" name="subaction" value="new" />
	<input type="hidden" name="album_id" value="{$album_id}" />
	<input type="hidden" name="dossier_id" value="{$dossier_id}" />

	{i18n key="album.folder.action.newfolder"}
	<input name="folder_new" value="" />
	<input class="button button-confirm" type="submit" value="{i18n key="album.folder.action.newfolder.submit"}" />
	</form>

</div>

<div id="folder_move" class="folder_action displayNone">

	<form name="folder_move" action="{copixurl dest="album||dofolder"}" method="get">
	<input type="hidden" name="subaction" value="move" />
	<input type="hidden" name="album_id" value="{$album_id}" />
	<input type="hidden" name="dossier_id" value="{$dossier_id}" />

	{i18n key="album.folder.action.move"}
	{assign var=level value=0}
	{assign var=forbidden value=0}
	<select NAME="folder_move">
	{foreach from=$commands_move item=valeur}
		{if $valeur.type eq 'open'}
			{assign var=level value=`$level+1`}
		{elseif $valeur.type eq 'close'}
			{assign var=level value=`$level-1`}
		{elseif $valeur.type eq 'folder'}
			<option VALUE="{$valeur.data->dossier_id}"{if $valeur.data->dossier_id == $dossier->dossier_parent} selected{/if}>{$TitreArticle|indent:$level:"&gt;&nbsp;"}{$valeur.data->dossier_nom|escape}</option>
		{/if}
	{/foreach}
	</select>
	<input class="button button-confirm" type="submit" value="{i18n key="album.folder.action.move.submit"}" />
	</form>
	
</div>

<div id="folder_rename" class="folder_action displayNone">

	<form name="folder_rename" action="{copixurl dest="album||dofolder"}" method="get">
	<input type="hidden" name="subaction" value="rename" />
	<input type="hidden" name="album_id" value="{$album_id}" />
	<input type="hidden" name="dossier_id" value="{$dossier_id}" />

	{i18n key="album.folder.action.rename"}
	<input name="folder_rename" value="{$dossier->dossier_nom|escape}" />
	<input class="button button-confirm" type="submit" value="{i18n key="album.folder.action.rename.submit"}" />
	</form>
	
</div>

<div id="folder_delete" class="folder_action displayNone">

	<form name="folder_delete" action="{copixurl dest="album||dofolder"}" method="get">
	<input type="hidden" name="subaction" value="delete" />
	<input type="hidden" name="album_id" value="{$album_id}" />
	<input type="hidden" name="dossier_id" value="{$dossier_id}" />

	{i18n key="album.folder.action.delete.alert"}
	<br />
	<select NAME="dossier_todo">
		<option value="moveparent" selected>{i18n key="album.folder.action.delete.moveparent"}</option>
		<option value="deleteall">{i18n key="album.folder.action.delete.deleteall"}</option>
	</select>

	<input class="button button-confirm" type="submit" value="{i18n key="album.folder.action.delete.submit"}" />
	</form>
	
</div>
</div>

{/if}