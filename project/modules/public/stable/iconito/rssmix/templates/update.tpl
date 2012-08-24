{literal}
    <script type="text/javascript">
    jQuery(document).ready(function($){
       $('.rm-test').click(function(e){
            $('.test-panel').html('<p class="mesgInfo">{/literal}{i18n key="rssmix.loading" noEscape=1}{literal}</p>');
            $.get('{/literal}{copixurl dest="rssmix|default|test"}{literal}?url='+encodeURIComponent($('#rm-i-url').val()), function(data){
                $('.test-panel').html('<h3>{/literal}{i18n key="rssmix.test.title" noEscape=1}{literal}</h3><p>'+data+'</p>').addClass('content-panel');
            });
            return false;
       });
    });
    </script>
{/literal}
<h2>{if empty($ppo->url)}{i18n key="rssmix.title.create" noEscape=1}{else}{i18n key="rssmix.title.update" noEscape=1}{/if}</h2>
<p class="content-info">{i18n key="rssmix.description" noEscape=1}</p>

{if isset($ppo->error)}
    <p class="mesgError" >
        {$ppo->error}
    </p>
{/if}

{if isset($ppo->success)}
    <p class="mesgSuccess" >
        {$ppo->success}
    </p>
{/if}

<form action="{$ppo->formAction}" method="post" id="rm-form" enctype="multipart/form-data" class="edit">
    
    <div class="field">
        <label for="rm-i-title">{i18n key="rssmix.label.streamTitle" noEscape=1}</label>
        <input type="text" name="rm-title" id="rm-i-title" size="50" value="{$ppo->title}" />
    </div>    
    <div class="field">
        
        <label for="rm-i-image">{i18n key="rssmix.label.image" noEscape=1}</label>
        <p class="input">
            {if !empty($ppo->image)}
                <img src="{$ppo->image}" alt="" />
                <a href="{copixurl dest="rssmix|default|deleteImage" id=$ppo->id}" class="button button-delete">{i18n key="rssmix.image.delete" noEscape=1}</a><br />
            {/if}
            <input type="file" name="rm-file" id="rm-i-file" size="15" />
        </p>
    </div>    
    <div class="field required">
        <label for="rm-i-url">{i18n key="rssmix.label.url" noEscape=1}</label>
        <input type="url" name="rm-url" id="rm-i-url" size="50" value="{$ppo->url}" required /> <a href="" class="button button-confirm rm-test" >{i18n key="rssmix.test" noEscape=1}</a>
    </div>

    <div class="test-panel">
    </div>

    <p class="submit">
        <a href="{copixurl dest="rssmix|default|default"}" class="button button-cancel" >{i18n key="rssmix.cancel" noEscape=1}</a> <input type="submit" value="{i18n key="rssmix.submit" noEscape=1}" class="button button-confirm "/>
    </p>
</form>
