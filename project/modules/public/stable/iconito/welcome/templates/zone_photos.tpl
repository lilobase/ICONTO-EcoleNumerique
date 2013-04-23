{if $titre}<div class="titre">{$titre}</div>{/if}
{*
<object type="application/x-shockwave-flash" data="{copixurl}dewplayer/dewslider.swf?img={foreach from=$arPhotos key=key item=photo}{if $key>0},{/if}{$photo->folder}/{$photo->file}{/foreach}&showbuttons=1&randomstart=1" width="{$width}" height="{$height}">
<param name="movie" value="{copixurl}dewplayer/dewslider.swf?img={foreach from=$arPhotos key=key item=photo}{if $key>0},{/if}{$photo->folder}/{$photo->file}{/foreach}&showbuttons=1&randomstart=1" />
<param name="wmode" value="transparent" />
</object>
*}
{if $mode eq 'dewslider' and $nbPhotos>0}
  {if $rAlbum}
    <div class="slider">
      <object type="application/x-shockwave-flash" data="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/album/{$rAlbum->album_id}_{$rAlbum->album_cle}/dewslider.xml" width="{$width}" height="{$height}">
        <param name="movie" value="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/album/{$rAlbum->album_id}_{$rAlbum->album_cle}/dewslider.xml" />
        <param name="wmode" value="transparent" />
      </object>
    </div>
  {elseif $rClasseur}
    <div class="slider">
        <object type="application/x-shockwave-flash" data="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/classeur/{$rClasseur->id}-{$rClasseur->cle}/dewslider.xml" width="{$width}" height="{$height}">
            <param name="movie" value="{copixurl}dewplayer/dewslider.swf?xml={copixurl}static/classeur/{$rClasseur->id}-{$rClasseur->cle}/dewslider.xml" />
            <param name="wmode" value="transparent" />
        </object>
    </div>
  {/if}
{else}
    <ul class="slide">
        {foreach from=$photolist key=k item=photo}
            <li class="left"><img src="{$photo.file}" alt="{$photo.title}"></li>
            {assign var=puces value=$puces<li>•</li>}
        {/foreach}
    </ul>
    <div class="slider-nav">
        <a href='#' id="sliderprev" class="sliderbutton" title="Image précédente"><img src="{copixresource path="img/slider-prev.png"}" alt="Image précédente"></a> 
        <a href='#' id="slidernext" class="sliderbutton" title="Image suivante"><img src="{copixresource path="img/slider-next.png"}" alt="Image suivante"></a>
    </div>
    <ul id="sliderposition" class="pagination">
        {$puces}
    </ul>
    <script type="text/javascript">{literal}
    /* Slideshow Images JS */
    jQuery(document).ready(function($){
        if(document.getElementById('slider_photos') !== null && typeof TINY !== 'undefined') {
            hpslideshow = new TINY.slider.slide('hpslideshow',{
                id: 'slider_photos',
                //auto: 3,
                resume: true,
                vertical: false,
                navid: 'sliderposition',
                activeclass: 'on',
                position: 0
            });
            document.getElementById('sliderprev').onclick = function() {
                hpslideshow.move(-1);
                return false;
            }
            document.getElementById('slidernext').onclick = function() {
                hpslideshow.move(1);
                return false;
            }
        }
    });{/literal}
	</script>
{/if}