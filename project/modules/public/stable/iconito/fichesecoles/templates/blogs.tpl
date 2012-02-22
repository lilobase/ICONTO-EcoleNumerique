{if $comboAnnees}
	<div class="annee"><b>{i18n key="fichesecoles|fichesecoles.blogs.annee"}</b> : {$comboAnnees}</div>
{/if}
<div class="fiche">{customi18n key="fichesecoles.fields.view%%definite__structure%%blogs" catalog=$catalog}</div>

<img class="icon" alt="{i18n key="fichesecoles.fields.viewblogs"}" title="{i18n key="fichesecoles.fields.viewblogs"}" width="56" height="62" src="{copixresource path="img/fichesecoles/icon_blog.gif"}" />

{if $rEcole->blog}
<div><strong><a href="{copixurl dest="blog||" blog=$rEcole->blog->url_blog}">{$rEcole->nom|escape}</a></strong></div>
{/if}

{if $arClasses}
	{foreach from=$arClasses item=classe}
		<div><a href="{copixurl dest="blog||" blog=$classe.url_blog}">{$classe.nom|escape}</a></div>
	{/foreach}
{/if}
<p class="clearBoth"></p>