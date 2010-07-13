<?xml version="1.0" encoding="UTF-8"?>
<SIMPLEVIEWER_DATA   maxImageDimension="{$publ_size}"
                     textColor="0xFFFFFF"
                     frameColor="0xFFFFFF"
                     bgColor="0x181818"
                     frameWidth="20"
                     stagePadding="40"
                     thumbnailColumns="3"
                     thumbnailRows="3"
                     navPosition="right"
                     navDirection="LTR"
                     title="{$album_titre}"
                     imagePath="{copixurl}static/album/{$album_id}_{$album_key}/{if $dossier_id gt 0}{$dossier_id}_{$dossier_key}/{/if}images/"
                     thumbPath="{copixurl}static/album/{$album_id}_{$album_key}/{if $dossier_id gt 0}{$dossier_id}_{$dossier_key}/{/if}thumbs/">

{foreach from=$photolist item=photo}
<IMAGE>
	<NAME>{$photo->photo_id}_{$photo->photo_cle}.jpg</NAME>
	<CAPTION><![CDATA[<b>{$photo->photo_nom|escape:"html"|utf8_encode}</b>{if $photo->photo_comment}<br /><i>{$photo->photo_comment}<i></i>{/if}]]></CAPTION>
</IMAGE>
{/foreach}

			
</SIMPLEVIEWER_DATA>