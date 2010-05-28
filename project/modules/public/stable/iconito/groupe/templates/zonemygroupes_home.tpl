<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_groupe.css"}" />

{if $concerto}
<div class="access_concerto">
	<div class="liens">
	{foreach from=$concerto_data item=concerto_item}
		<a class="lien box_M_border" target="_blank" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" href="{copixurl dest="concerto||go" id=$concerto_item->id}"><img border="0" width="171" height="94" src="{copixresource path="img/concerto/go.gif"}" alt="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" /><br/>{i18n key="concerto|concerto.home.go" login=$concerto_item->login}</a>
	{/foreach}
	</div>
</div>
{/if}


<div>
	<a href="{copixurl dest="|getListMy"}" title="{i18n key="groupe.my"}">
	<img border="0" src="{copixresource path="img/groupe/my_home.gif"}" width="180" height="37" alt="{i18n key="groupe.my"}" />
	</a>
</div>

{if $list neq null}
<div id="groups-home">
	{foreach from=$list item=groupe}
	<div class="body">
		<div class="titleb">
			{if $groupe->canViewHome}<a href="{copixurl dest="|getHome" id=$groupe->id}">{$groupe->titre}</a>{else}{$groupe->titre}{/if}
		</div>
		<div class="actions">
			{if $groupe->canViewHome}<a class="home" href="{copixurl dest="|getHome" id=$groupe->id}">{i18n key="groupe.group.home"}</a>{/if}
			{if $groupe->blog}<a class="blog" href="{copixurl dest="blog||listArticle" blog=$groupe->blog->url_blog}">{i18n key="groupe.group.blogView"}</a>{/if}
		</div>
	</div>
	{/foreach}
</div>
{else}
<div>{i18n key="groupe.noSubsc"}</div>
{/if}