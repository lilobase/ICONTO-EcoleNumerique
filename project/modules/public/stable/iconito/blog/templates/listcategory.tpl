{if count($listCategory)}
<div>
<h2>{i18n key="blog.nav.categories"}</h2>
<UL>
	   {foreach from=$listCategory item=cat}
        <LI><a href="{copixurl dest="blog||listArticle" blog=$blog->url_blog cat=$cat->url_bacg}">{$cat->name_bacg}</a></LI>
	   {/foreach}
		 </UL>
</div>
{/if}

