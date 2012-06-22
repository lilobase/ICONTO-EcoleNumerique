<h2>{i18n key="rssmix.title" noEscape=1}</h2>
<p class="content-info">{i18n key="rssmix.description" noEscape=1}</p>

{if !empty($ppo->success)}

    <p class="mesgSuccess">{$ppo->success}</p>
    
{/if}

{if empty($ppo->rss)}
    <div class="content-panel">
    {i18n key="rssmix.noUrl" noEscape=1}
    </div>
    {else}
{foreach from=$ppo->rss item=rssItem}
    
    <div class="content-panel rm-item">
        
        {$rssItem.url}  <a href="{copixurl dest="rssmix|default|delete" id=$rssItem.id}" class="button button-delete floatright delete" >{i18n key="rssmix.delete" noEscape=1}</a>
        <a href="{copixurl dest="rssmix|default|update" id=$rssItem.id}" class="button button-update floatright" >{i18n key="rssmix.update" noEscape=1} </a>
    <br /><br />
    </div>

{/foreach}
{/if}
<div class="content-panel content-panel-button">
    <a href="{copixurl dest="rssmix|default|create"}" class="button button-add" >{i18n key="rssmix.add" noEscape=1}</a>
</div>