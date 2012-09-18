<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_album.css"}" />

<div class="center">

{if $photo->prev}<a class="button button-previous" href="{copixurl dest="album||photo" photo_id=$photo->prev}">{else}<span style="visibility: hidden;">{/if}{i18n key="album|album.photo.previous"}{if $photo->prev}</a>{else}</span>{/if}

<a class="button button-view" href="{copixurl dest="album||album" album_id=$photo->album_id dossier_id=$photo->photo_dossier}">{i18n key="album|album.photo.index"}</a>

{if $photo->next} <a class="button button-next" href="{copixurl dest="album||photo" photo_id=$photo->next}">{else}<span style="visibility: hidden;"> :: {/if}{i18n key="album|album.photo.next"}{if $photo->next}</a>{else}</span>{/if}


<div class="photoseule">
{if $photo->next}
<a href="{copixurl dest="album||photo" photo_id=$photo->next}">
{else}
<a href="{copixurl dest="album||album" album_id=$photo->album_id dossier_id=$photo->photo_dossier}">
{/if}
<img src="{copixurl}static/album/{$photo->album_id}_{$photo->album_cle}/{$photo->photo_id}_{$photo->photo_cle}{$photo_size}.{$photo->photo_ext}" />
</a>
</div>

{if $photo->photo_nom}<strong>{i18n key="album.form.title"}</strong> {$photo->photo_nom|escape}<br />{/if}
{if $photo->photo_comment}<strong>{i18n key="album.form.comment"}</strong> {$photo->photo_comment|escape}<br />{/if}
{if $photo->photo_date}<strong>{i18n key="album.form.date"}</strong> {$photo->photo_date}<br />{/if}

</div>
