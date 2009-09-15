<ul>
{if $albumlist neq null}	
	{foreach from=$albumlist item=valeur}
		<li>
			{$valeur->album_nom} ({$valeur->nb_photos})
			<br />
			{assign var=sep value=""}{assign var=sepval value=" :: "}
			{if $valeur->droit_lire}{$sep}{assign var=sep value=$sepval}<a href="{copixurl dest="album||album" album_id=$valeur->album_id}">{i18n key="album|album.albumlist.voir"}</a>{/if}
			{if $valeur->droit_publier}{$sep}{assign var=sep value=$sepval}<a href="{copixurl dest="album||addphoto" album_id=$valeur->album_id}">{i18n key="album|album.albumlist.ajouter"}</a>{/if}
			{if $valeur->droit_administrer}{$sep}{assign var=sep value=$sepval}<a href="{copixurl dest="album||delalbum" album_id=$valeur->album_id}">{i18n key="album|album.albumlist.supprimer"}</a>{/if}
		</li>
	{/foreach}
{else}
		<li>{i18n key="album|album.albumlist.noalbum"}</li>
{/if}
</ul>