<h2>{i18n key="rssmix.title.update" noEscape=1}</h2>
<p class="content-info">{i18n key="rssmix.description" noEscape=1}</p>

{if isset($ppo->error)}
    <p class="mesgError" >
        {$ppo->error}
    </p>
{/if}

<form action="{copixurl dest="rssmix|default|updatep" id=$ppo->id}" method="post" id="rm-form">
    
    <div class="content-panel">
        <label for="rm-i-url">{i18n key="rssmix.label.update" noEscape=1}</label> : 
        <input type="url" name="rm-url" id="rm-i-url" size="50" value="{$ppo->url}"/> <a href="" class="button" >{i18n key="rssmix.test" noEscape=1}</a>
    </div>

    <div class="content-panel">
        <a href="{copixurl dest="rssmix|default|default"}" class="button button-cancel" >{i18n key="rssmix.cancel" noEscape=1}</a> <input type="submit" value="{i18n key="rssmix.submit" noEscape=1}" class="button button-save "/>
    </div>
</form>
