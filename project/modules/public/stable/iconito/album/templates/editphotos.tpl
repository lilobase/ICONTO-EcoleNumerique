<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_album.css"}" />


{literal}
<script type="text/javascript">
<!--
function photo_toggle (photo) {
	var form = getRef ('photo_move');
	for (var i=0 ; i < form.length ; i++) {
		if ( form[i].name == photo || photo == 'all' ) {
			if( form[i].checked == false ) form[i].checked = true;
			else form[i].checked = false;
		}
	}
}

function photo_all ( mode ) {
	var form = getRef ('photo_move');
	for (var i=0 ; i < form.length ; i++) {
		form[i].checked = mode;
	}
}

//-->
</script>
{/literal}

{if $pictures neq null}	

<form name="photo_move" id="photo_move" action="{copixurl dest="album||doeditphotos" album=$album_id dossier=$dossier_id}" method="post">

	<table class="liste">
	{foreach from=$pictures item=picture}
		<tr class="">
			<td width="1"><input type="checkbox" name="photo_{$picture->photo_id}" value="1" /></td>
			<td width="1">
				<a href="javascript:photo_toggle('photo_{$picture->photo_id}');">
				<img src="{copixurl}static/album/{$picture->album_id}_{$picture->album_cle}/{$picture->photo_id}_{$picture->photo_cle}{$picture_thumbsize}.{$picture->photo_ext}" alt="{$picture->photo_nom|escape}" title="{$picture->photo_nom|escape}" width="{$album_thumbsize_width}" height="{$album_thumbsize_height}" class="floatleft" id="thumb_{$picture->photo_id}"/>
				</a>
			</td>
			<td align="left">
				{$picture->photo_nom|escape}
			</td>
		</tr>
	{/foreach}
	</table>

	<p>{i18n key="album|album.editphotos.select"} : <a href="javascript:photo_all(true);">{i18n key="album|album.editphotos.select_all"}</a> / <a href="javascript:photo_all(false);">{i18n key="album|album.editphotos.select_none"}</a> / <a href="javascript:photo_toggle('all');">{i18n key="album|album.editphotos.select_toggle"}</a></p>

	{i18n key="album|album.editphotos.moveto"} :
	{assign var=level value=0}
	{assign var=forbidden value=0}
	<select NAME="folder_move">
	{foreach from=$commands_move item=valeur}
		{if $valeur.type eq 'open'}
			{assign var=level value=`$level+1`}
		{elseif $valeur.type eq 'close'}
			{assign var=level value=`$level-1`}
		{elseif $valeur.type eq 'folder'}
			<option VALUE="{$valeur.data->dossier_id}"{if $valeur.data->dossier_id == $dossier->dossier_id} selected{/if}>{$TitreArticle|indent:$level:"&gt;&nbsp;"}{$valeur.data->dossier_nom|escape}</option>
		{/if}
	{/foreach}
	</select>
	<input class="button button-confirm" type="submit" value="{i18n key="album|album.editphotos.moveto_submit"}" />

</form>

{else}
	{i18n key="album.error.emptyfolder"}
{/if}