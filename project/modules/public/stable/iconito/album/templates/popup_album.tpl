<div class="album">

{if $dossier->dossier_id gt 0}
<div class="photo"><a class="parent" href="{copixurl dest="album||getpopup" album_id=$dossier->dossier_album dossier_id=$dossier->dossier_parent field=$field format=$format}"></a></div>
{/if}

{if $dossierlist neq null}	
	{foreach from=$dossierlist item=valeur}
		<div class="photo"><a class="album" href="{copixurl dest="album||getpopup" album_id=$album->album_id dossier_id=$valeur->dossier_id field=$field format=$format}">{$valeur->dossier_nom|escape}<br /><em style="font-size: 0.8em;">({if $valeur->photos|@count eq 0}album vide{else}{$valeur->photos|@count} photo{if $valeur->photos|@count gt 1}s{/if}{/if})</em></a></div>
	{/foreach}
{/if}

{if $photolist neq null}	
	{foreach from=$photolist item=valeur}
		<div class="photo"><a href="#" onClick="return sendPhoto('{$valeur->album_id}_{$valeur->album_cle}','{$valeur->photo_id}_{$valeur->photo_cle}', '{$valeur->photo_ext}', '{$valeur->photo_nom|escape:"url"}');"><img src="{copixurl}static/album/{$valeur->album_id}_{$valeur->album_cle}/{$valeur->photo_id}_{$valeur->photo_cle}{$album_thumbsize}.{$valeur->photo_ext}" alt="{$valeur->photo_nom|escape}" title="{$valeur->photo_nom|escape}"></a></div>
	{/foreach}
{/if}
</div>

