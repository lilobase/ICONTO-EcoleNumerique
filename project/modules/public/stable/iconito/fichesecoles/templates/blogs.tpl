<div class="inner">

<a class="close" href="javascript:ficheViewBlogs({$rEcole->numero},'close');">[ {i18n key="annuaire|annuaire.btn.close"} ]</a>


{if $comboAnnees}
	<div class="annee"><b>{i18n key="fichesecoles|fichesecoles.blogs.annee"}</b> : {$comboAnnees}</div>
{/if}


{if $rEcole->blog}
<div><b><a href="{copixurl dest="blog||" blog=$rEcole->blog->url_blog}">{$rEcole->nom|escape}</a></b></div>
{/if}

{if $arClasses}
	{foreach from=$arClasses item=classe}
		<div><a href="{copixurl dest="blog||" blog=$classe.url_blog}">{$classe.nom|escape}</a></div>
	{/foreach}
{/if}

</div>	