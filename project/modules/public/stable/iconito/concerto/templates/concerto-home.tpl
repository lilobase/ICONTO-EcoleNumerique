<div class="access_concerto">
	<div class="liens">
	{foreach from=$concerto_data item=concerto_item}
		<a class="lien box_M_border" target="_blank" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" href="{copixurl dest="concerto||go" id=$concerto_item->id}"><img border="0" width="171" height="94" src="{copixresource path="img/concerto/go.gif"}" alt="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" title="{i18n key="concerto|concerto.home.go" login=$concerto_item->login}" /><br/>{i18n key="concerto|concerto.home.go" login=$concerto_item->login}</a>
	{/foreach}
	</div>
</div>