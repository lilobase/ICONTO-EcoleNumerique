<div class="content-panel">
    <h3>{$ppo->title}</h3>
</div>

<div class="content-panel">
    {$ppo->desc}
</div>

{foreach from=$ppo->items item=itemR}

<div class="content-panel">
    <h4><a href="{$itemR->link}">{$itemR->title}</a></h4>
    <p>{$itemR->desc}</p>
    <p><a href="{$itemR->link}"><img src="{$itemR->pic.url}" /></a></p>
    <a class="button button-continue" href="{$itemR->link}">Acceder à la ressource</a>
</div>
{/foreach}