<link rel="stylesheet" type="text/css" href="styles/module_groupe.css" />

<DIV STYLE="float:right; padding: 2px; width: 190px;">

{if $concerto}
<div class="access_concerto">
	<div class="liens">
	{foreach from=$concerto_data item=concerto_item}
		<a class="lien box_M_border" target="_blank" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" href="{copixurl dest="concerto||go" id=$concerto_item->id}"><img border="0" width="171" height="94" src="img/concerto/go.gif" alt="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" /><br/>{i18n key="concerto|concerto.home.go" login=$concerto_item->login}</a>
	{/foreach}
	</div>
</div>
{/if}


<div style="text-align: right;"><a href="{copixurl dest="|getListMy"}" title="{i18n key="groupe.my"}"><img border="0" src="img/groupe/my_home.gif" width="180" height="37" alt="{i18n key="groupe.my"}" /></a></div>


	{if $list neq null}


<DIV ID="groups-home">
{foreach from=$list item=groupe}
<DIV CLASS="body">

			<DIV CLASS="titleb">{if $groupe->canViewHome}<A HREF="{copixurl dest="|getHome" id=$groupe->id}">{$groupe->titre}</a>{else}{$groupe->titre}{/if}</DIV>

<!--
{$groupe->description|truncate:150}
-->

			<DIV CLASS="actions">
			{if $groupe->canViewHome}<a class="home" href="{copixurl dest="|getHome" id=$groupe->id}">{i18n key="groupe.group.home"}</a>{/if}
<!--
			{if !$groupe->mondroit }<a class="subscribe" href="{copixurl dest="|doJoin" id=$groupe->id}">{i18n key="groupe.group.join"}</a>{/if}
-->
<!--
			{if $groupe->canAdmin }<a class="admin" href="{copixurl dest="|getHomeAdmin" id=$groupe->id}">{i18n key="groupe.group.admin"}</a>{/if}
-->
			{if $groupe->blog}<a class="blog" href="{copixurl dest="blog||listArticle" blog=$groupe->blog->url_blog}">{i18n key="groupe.group.blogView"}</a>{/if}
			</DIV>

</DIV>
{/foreach}
</DIV>
	{else}

  <div><i>{i18n key="groupe.noSubsc"}</i></div>

	{/if}
</DIV>

