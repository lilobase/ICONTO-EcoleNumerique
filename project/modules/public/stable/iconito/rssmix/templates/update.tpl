{literal}
    <script type="text/javascript">
    jQuery(document).ready(function($){
       $('.rm-test').click(function(e){
            $('.test-panel').html('<p class="mesgInfo">{/literal}{i18n key="rssmix.loading" noEscape=1}{literal}</p>');
            $.get('{/literal}{$ppo->urlTest}{literal}?url='+encodeURIComponent($('#rm-i-url').val()), function(data){
                $('.test-panel').html('<h3>{/literal}{i18n key="rssmix.test.title" noEscape=1}{literal}</h3><p>'+data+'</p>').addClass('content-panel');
            });
            return false;
       });
    });
    </script>
{/literal}
<h2>{i18n key="rssmix.title.update" noEscape=1}</h2>
<p class="content-info">{i18n key="rssmix.description" noEscape=1}</p>

{if isset($ppo->error)}
    <p class="mesgError" >
        {$ppo->error}
    </p>
{/if}

<form action="{copixurl dest="rssmix|default|updatep" id=$ppo->id}" method="post" id="rm-form">

    <div class="field">
        <label for="rm-i-url">{i18n key="rssmix.label.update" noEscape=1}</label> : 
        <input type="url" name="rm-url" id="rm-i-url" size="50" value="{$ppo->url}"/> <a href="" class="button button-confirm rm-test" >{i18n key="rssmix.test" noEscape=1}</a>
    </div>

    <div class="test-panel">
    </div>

    <p class="center">
        <a href="{copixurl dest="rssmix|default|default"}" class="button button-cancel" >{i18n key="rssmix.cancel" noEscape=1}</a> <input type="submit" value="{i18n key="rssmix.submit" noEscape=1}" class="button button-confirm "/>
    </p>
</form>
