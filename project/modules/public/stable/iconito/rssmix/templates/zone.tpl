{literal}
<script type="text/javascript"> 

        jQuery(document).ready(function($){

        $.get('{/literal}{$urlRssMix}{literal}', function(data){
            $('#rssmix-content').html(data);
            $('#rssmix-cycle').jCarouselLite({ 
                vertical: true,
                auto: true,
                speed: 6000,
                circular: true,
                visible: 4,
                scroll: 1,
                mouseWheel: true,
                pauseOnMouseOver: true
            });
        });

    });
</script>
{/literal}
<h1>{i18n key="rssmix.front.title" noEscape=1}</h1>
<div id="rssmix-content">
<p class="mesgInfo">{i18n key="rssmix.loading" noEscape=1}</p>
</div>
{if $userIsAdmin}
    <p><a href="{$urladmin}" class="button button-update">{i18n key="rssmix.admin.update" noEscape=1}</a></p>
{/if}