
<h2>{$ppo->title}</h2>

<p>
    {$ppo->desc}
</p>

<ul class="resource">
{foreach from=$ppo->items item=itemR}
<li class="">
    <h3><a href="{$itemR->link}">{$itemR->title}</a></h3>
    <div class="content-panel">
        <a href="{$itemR->link}" class="illustration"><img src="{$itemR->pic.url}" alt="{$itemR->title}" /></a>
        <p>{$itemR->desc}</p>
        <a class="button button-continue" href="{$itemR->link}">{i18n key="rssetagere.link"}</a>
    </div>
</li>
{/foreach}
</ul>