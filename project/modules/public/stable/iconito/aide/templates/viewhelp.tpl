<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_aide.css"}" />

<div class="aidePage">

{*

{i18n key="aide.intro"}

<ul>
{foreach from=$rubriques item=item}

<li><a href="{copixurl dest="aide||viewHelp" rubrique=$item.name}">{$item.title}</a></li>

{/foreach}


</ul>

{i18n key="aide.guide"}

*}

{i18n key="aide.guide_ext" noEscape=1}


</div>