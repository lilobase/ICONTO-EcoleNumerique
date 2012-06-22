<h1>{i18n key="mailext.mailext" noEscape=1}
</h1>


{if empty($content)}
    <p class="mesgInfo">{i18n key="mailext.noConfigured" noEscape=1}</p>
{else}
    {foreach item=mail from=$content}
<h4 class="mailext-title">{i18n key="mailext.box" noEscape=1} <em>{$mail.name}</em></h4>

<a class="mailext-webmail" href="{$mail.webmail_url}" target="_blank">
    <div id="nbmail{$mail.id}">
        {i18n key="mailext.wait" noEscape=1}
    </div>
</a>


{literal}
<script type="text/javascript">
    jQuery(document).ready(function($){

        $.get('{/literal}{$urlmail}{literal}?id_mail={/literal}{$mail.id}{literal}', function(data){
            $('#nbmail{/literal}{$mail.id}{literal}').html(data);
        });

    });
</script>
{/literal}

<hr class="mailext-hr"/>
{/foreach}
{/if}

    <a href="{copixurl dest="mailext|mailext|admin"}" class="button button-update">{i18n key="mailext.goAdmin" noEscape=1}</a>
