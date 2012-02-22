{if $titre}<div class="titre">{$titre}</div>{/if}
{if count($listFluxRss)}
<ul>
{foreach from=$listFluxRss item=fluxrss}  
<li><a href="{copixurl dest="blog||showFluxRss" blog=$fluxrss->id_blog id_bfrs=$fluxrss->id_bfrs}">{$fluxrss->name_bfrs}</a></li>
{/foreach}
</ul>
{/if}