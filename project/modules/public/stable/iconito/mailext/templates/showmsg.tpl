<h1>{i18n key="mailext.mailext" noEscape=1}
</h1>
{if empty($content)}
{i18n key="mailext.noConfigured" noEscape=1}
{else}
    {foreach item=mail from=$content}
<h5>{$mail.name}</h5>
<div id="nbmail{$mail.id}">
{i18n key="mailext.wait" noEscape=1}
</div>
<a href="{$mail.id}">{i18n key="mailext.webmail" noEscape=1}</a>


{literal}
<script type="text/javascript">
    jQuery(document).ready(function($){

        $.get('{/literal}{$urlmail}{literal}?id_mail={/literal}{$mail.id}{literal}', function(data){
            $('#nbmail{/literal}{$mail.id}{literal}').html(data);
        });

    });
</script>
{/literal}
{/foreach}
{/if}
<h5>
    <a href="{copixurl dest="mailext|mailext|admin"}">{i18n key="mailext.title" noEscape=1}</a>
</h5>
