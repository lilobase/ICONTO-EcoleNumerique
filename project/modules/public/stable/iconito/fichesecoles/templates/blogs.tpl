<div class="inner">

<div class="close">[ <a href="javascript:ficheViewBlogs({$rEcole->numero});">{i18n key="annuaire|annuaire.btn.close"}</a> ]</div>

{if $rEcole->blog}
<div><b><a href="{copixurl dest="blog||" blog=$rEcole->blog->url_blog}">{$rEcole->nom|htmlentities}</a></b></div>
{/if}

{if $arClasses}
	{foreach from=$arClasses item=classe}
		<div><a href="{copixurl dest="blog||" blog=$classe.url_blog}">{$classe.nom|htmlentities}</a></div>
	{/foreach}
{/if}

</div>	