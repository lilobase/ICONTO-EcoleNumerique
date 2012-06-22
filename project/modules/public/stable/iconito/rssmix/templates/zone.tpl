{literal}
<script type="text/javascript"> 
        jQuery(document).ready(function($){

        $.get('{/literal}{$urlRssMix}{literal}', function(data){
            $('#rssmix-cycle').html(data);
        });

    });
</script>
{/literal}
<div id="rssmix-cycle">
<p class="mesgInfo">{i18n key="rssmix.loading" noEscape=1}</p>
</div>
{if $userIsAdmin}
    <p><a href="" class="button button-update">{i18n key="rssmix.admin.update" noEscape=1}</a></p>
{/if}