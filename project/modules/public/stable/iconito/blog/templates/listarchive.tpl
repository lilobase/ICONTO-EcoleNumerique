{if count($listArchive)}
<div>
<h2>{i18n key="blog.nav.archives"}</h2>
	   <UL>
		 {foreach from=$listArchive item=article}
        <LI><a href="{copixurl dest="blog||listArticle" blog=$blog->url_blog date=$article->dateValue}">{$article->drawDate}</a></LI>
	   {/foreach}
		 </UL>
</div>
{/if}

