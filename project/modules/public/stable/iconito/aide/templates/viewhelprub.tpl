<link rel="stylesheet" type="text/css" href="styles/module_aide.css" />

<div class="aidePage">

<ul>
{foreach from=$pages item=item}

<li><a href="{copixurl dest="aide||viewHelp" rubrique=$rubrique page=$item.name}">{$item.title}</a></li>

{/foreach}
</ul>


</div>