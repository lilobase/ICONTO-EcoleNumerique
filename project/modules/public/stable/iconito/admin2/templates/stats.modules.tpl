
{foreach from=$tab item=mod}

<h2>{$mod.module_nom}</h2>

<ul>
{foreach from=$mod.stats key=l item=stat}
<li>{$stat.name}</li>
{/foreach}
</ul>
{/foreach}


