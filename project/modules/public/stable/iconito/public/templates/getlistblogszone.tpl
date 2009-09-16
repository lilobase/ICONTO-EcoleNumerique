<div id="blogs">

	{if $list neq null}
		{counter assign="i" name="i"}
	
		{foreach from=$list item=item}
			<div class="blogBody">
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">
			<a class="title" href="{copixurl dest="blog||listArticle" blog=$item->url_blog}">{$item->name_blog}</a><a class="" href="{copixurl dest="blog||listArticle" blog=$item->url_blog}" target="_BLANK"><img alt="{i18n key="public.openNewWindow"}" title="{i18n key="public.openNewWindow"}" border="0" width="12" height="12" src="img/public/open_window.png" hspace="4" /></a>
			<div class="blogType">{$item->type} {if $item->parent}({$item->parent}){/if}</div>
			<div class="blogStats">{if !$item->stats.nbArticles.value}{i18n key="public.blog.0article"}{elseif $item->stats.nbArticles.value>1}{i18n key="public.blog.Narticle" 1=$item->stats.nbArticles.value}{else}{i18n key="public.blog.1article"}{/if}
			{if $item->stats.lastUpdate.value} - {i18n key="public.blog.lastUpdate" 1=$item->stats.lastUpdate.value|datei18n:"date_short_time"}{/if}</div>
			</td>
			<td align="right" valign="top">
			{if $item->logo_blog}<div><img class="logo" src="{copixurl dest="blog|admin|logo" id_blog=$item->id_blog}" border="0" /></div>{/if}
			</td></tr></table>
			</div>
			{counter name="i"}
		{/foreach}
		
	{else}
		
		{i18n key="public.blog.noBlogs"}
	
	{/if}

</div>

