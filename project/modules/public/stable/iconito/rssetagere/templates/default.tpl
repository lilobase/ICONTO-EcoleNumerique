
<h2>{$ppo->title}
{if $ppo->isEns}
<a href="https://www.coreprim.fr/viewClasses.html"  target="_blank" class="button button-update floatright">{i18n key="rssetagere.linkCoreprim"}</a>
{/if}
</h2>

<p>
    {$ppo->desc}
</p>

<ul class="resource">
{foreach from=$ppo->items item=itemR}
<li class="">
    <h3><a href="{$itemR->link}" target="_blank">{$itemR->title}</a></h3>
    <div class="content-panel">
        <a href="{$itemR->link}" target="_blank" class="illustration"><img src="{$itemR->pic.url}" alt="{$itemR->title}" /></a>
        <p>{$itemR->desc}</p>
        <a class="button button-continue" target="_blank" href="{$itemR->link}">{i18n key="rssetagere.link"}</a>
    </div>
</li>
{/foreach}
</ul>