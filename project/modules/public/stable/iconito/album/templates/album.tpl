{if $petit_poucet}
	<div class="album_petit_poucet">
	{foreach from=$petit_poucet item=petit_poucet_item name=petit_poucet}
		/
		{if ! $smarty.foreach.petit_poucet.last}<a href="{copixurl dest="album||album" album_id=$petit_poucet_item->dossier_album dossier_id=$petit_poucet_item->dossier_id}">{/if}
		{$petit_poucet_item->dossier_nom|escape}
		{if ! $smarty.foreach.petit_poucet.last}</a>{/if}
	{/foreach}
	</div>
{/if}

<div>
{$dossiers}
</div>

{if ! $photolist|@count}
	<div class="album-emptyfolder">{i18n key="album.error.emptyfolder"}</div>
{/if}

{if $dossier->dossier_id gt 0}
<div class="photo"><a class="parent" href="{copixurl dest="album||album" album_id=$dossier->dossier_album dossier_id=$dossier->dossier_parent}"></a></div>
{/if}

{if $dossierlist neq null}	
	{foreach from=$dossierlist item=valeur}
		<div class="photo"><a class="album" href="{copixurl dest="album||album" album_id=$valeur->dossier_album dossier_id=$valeur->dossier_id}">{$valeur->dossier_nom|escape}<br /><em style="font-size: 0.8em;">({if $valeur->photos|@count eq 0}{i18n key="album.display.photocount.0"}{else}{if $valeur->photos|@count eq 1}{i18n key="album.display.photocount.1"}{else}{i18n key="album.display.photocount.n" 1=$valeur->photos|@count}{/if}{/if})</em></a></div>
	{/foreach}
{/if}

{if $photolist|@count}	
	{foreach from=$photolist item=valeur}
		<div class="photo"><a href="{copixurl dest="album||photo" photo_id=$valeur->photo_id}"><img src="{copixurl}static/album/{$valeur->album_id}_{$valeur->album_cle}/{$valeur->photo_id}_{$valeur->photo_cle}{$album_thumbsize}.{$valeur->photo_ext}" alt="{$valeur->photo_nom|escape}" title="{$valeur->photo_nom|escape}" width="{$album_thumbsize_width}" height="{$album_thumbsize_height}"></a></div>
	{/foreach}
{/if}
