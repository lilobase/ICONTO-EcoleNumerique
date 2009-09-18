<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_album.css"}" />

{$dossiers}

{if $dossier->dossier_id gt 0}
<div class="photo"><a class="parent" href="{copixurl dest="album||album" album_id=$dossier->dossier_album dossier_id=$dossier->dossier_parent}"></a></div>
{/if}

{if $dossierlist neq null}	
	{foreach from=$dossierlist item=valeur}
		<div class="photo"><a class="album" href="{copixurl dest="album||album" album_id=$valeur->dossier_album dossier_id=$valeur->dossier_id}">{$valeur->dossier_nom}<br /><i style="font-size: 0.8em;">({if $valeur->photos|@count eq 0}{i18n key="album.display.photocount.0"}{else}{if $valeur->photos|@count eq 1}{i18n key="album.display.photocount.1"}{else}{i18n key="album.display.photocount.n" 1=$valeur->photos|@count}{/if}{/if})</i></a></div>
	{/foreach}
{/if}

{if $photolist neq null}	

	{foreach from=$photolist item=valeur}
		<div class="photo"><a href="{copixurl dest="album||photo" photo_id=$valeur->photo_id}"><img src="{copixurl}static/album/{$valeur->album_id}_{$valeur->album_cle}/{$valeur->photo_id}_{$valeur->photo_cle}{$album_thumbsize}.{$valeur->photo_ext}" border="0" alt="{$valeur->photo_nom|htmlentities}" title="{$valeur->photo_nom|htmlentities}" width="{$album_thumbsize_width}" height="{$album_thumbsize_height}"></a></div>
	{/foreach}

{else}
	{i18n key="album.error.emptyfolder"}
{/if}
