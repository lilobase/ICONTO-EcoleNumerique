<div class="content-panel">
    <h3>{$ppo->title}</h3>
</div>

<div class="content-panel">
    {$ppo->desc}
</div>

{foreach from=$ppo->items item=itemR}

<div class="content-panel">
    {$itemR->title}
    {$itemR->desc}
    {$itemR->link}
</div>
{/foreach}