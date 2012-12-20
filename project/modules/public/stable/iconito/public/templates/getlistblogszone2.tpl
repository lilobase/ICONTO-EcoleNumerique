
<div id="blogs">

	{if $list neq null}

		<h3>{i18n key="public.blog.listGroupes"} :</h3>

		{counter assign="i" name="i"}
	
		{foreach from=$list item=item}
			<div class="blogBody block">
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">
			<a class="title" title="{$item->name_blog}" href="{copixurl dest="blog||listArticle" blog=$item->url_blog}">{$item->name_blog}</a><a class="" href="{copixurl dest="blog||listArticle" blog=$item->url_blog}" target="_BLANK"><img alt="{i18n key="public.openNewWindow"}" title="{i18n key="public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a>
			<div class="blogType">{$item->type} {if $item->parent}({$item->parent}){/if}</div>
			<div class="blogStats">{if !$item->stats.nbArticles.value}{i18n key="public.blog.0article"}{elseif $item->stats.nbArticles.value>1}{i18n key="public.blog.Narticle" 1=$item->stats.nbArticles.value}{else}{i18n key="public.blog.1article"}{/if}
			{if $item->stats.lastUpdate.value} - {i18n key="public.blog.lastUpdate" 1=$item->stats.lastUpdate.value|datei18n:"date_short_time"}{/if}</div>
			</td>
			<td align="right" valign="top">
			{if $item->logo_blog}<div><a title="{$item->name_blog}" href="{copixurl dest="blog||listArticle" blog=$item->url_blog}"><img class="logo" src="{copixurl dest="blog||logo" id_blog=$item->id_blog}" border="0" /></a></div>{/if}
			</td></tr></table>
			</div>
			{counter name="i"}
		{/foreach}
		
        {if $can_public_rssfeed}
		<div class="blogBody">
			<table border="0" width="100%" cellspacing="0" cellpadding="0"><tr><td valign="top">
			<div class="blogStats"><a title="RSS" href="{copixurl dest="public||rss"}"><img src="{copixresource path="img/blog/feed-icon-16x16.png"}" width="16" height="16" border="0" alt="RSS" title="RSS" align="left" hspace="4" /> {i18n key="public.rss.link"}</a></div>
			</td>
			<td></td></tr></table>
			</div>
		{/if}
	
	{/if}

</div>



<div id="blogs_ecoles">
{if $villes neq null}

	<h3>{i18n key="public.listEcoles"} :</h3>
	{foreach from=$villes item=ville}
		<div class="ville">
		<div class="nom">{$ville.nom}</div>

		{assign var="villeid" value=$ville.id}
		{assign var="ec" value=$ecoles.$villeid}

		{if $ec}
			{foreach from=$ec item=ecole}
				<div>
				{*
				{if isset($ecole.blog)}
					<a class="" href="{copixurl dest="blog||listArticle" blog=$ecole.blog.url_blog}">{$ecole.nom}</a>{if $ecole.type} ({$ecole.type}){/if}<a class="" href="{copixurl dest="blog||listArticle" blog=$ecole.blog.url_blog}" target="_BLANK"><img alt="{i18n key="public.openNewWindow"}" title="{i18n key="public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a><a class="fancybox" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}"><img alt="{i18n key="public|public.openPopup"}" title="{i18n key="annuaire|annuaire.fiche"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="1" /></a>
				{else}
					{$ecole.nom}{if $ecole.type} ({$ecole.type}){/if}
				{/if}
				*}
				<a title="Fiche &eacute;cole" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{$ecole.nom}</a>{if $ecole.type} ({$ecole.type}){/if}
				</div>
			{/foreach}
		{else}
			<div><i>{i18n key="public.blog.noEcole"}</i></div>
		{/if}
		</div>
		
	{/foreach}
{/if}
</div>
