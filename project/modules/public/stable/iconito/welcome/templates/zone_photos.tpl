{if $titre}<div class="titre">{$titre}</div>{/if}
{*
<object type="application/x-shockwave-flash" data="{copixurl}dewplayer/dewslider.swf?img={foreach from=$arPhotos key=key item=photo}{if $key>0},{/if}{$photo->folder}/{$photo->file}{/foreach}&showbuttons=1&randomstart=1" width="{$width}" height="{$height}">
<param name="movie" value="{copixurl}dewplayer/dewslider.swf?img={foreach from=$arPhotos key=key item=photo}{if $key>0},{/if}{$photo->folder}/{$photo->file}{/foreach}&showbuttons=1&randomstart=1" />
<param name="wmode" value="transparent" />
</object>
*}
{if $mode eq 'dewslider' and $nbPhotos>0}
<div class="slider"><object type="application/x-shockwave-flash" data="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/album/{$rAlbum->album_id}_{$rAlbum->album_cle}/dewslider.xml" width="{$width}" height="{$height}">
<param name="movie" value="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/album/{$rAlbum->album_id}_{$rAlbum->album_cle}/dewslider.xml" />
<param name="wmode" value="transparent" />
</object>
</div>
{/if}
