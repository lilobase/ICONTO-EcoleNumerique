{if count($listFluxRss)}
<div>
<h2>{i18n key="blog.nav.rss"}</h2>
<UL>
	   {foreach from=$listFluxRss item=flux}
         <LI><a href="{copixurl dest="blog||showFluxRss" blog=$flux->id_blog id_bfrs=$flux->id_bfrs}">{$flux->name_bfrs}</a></LI>
	   {/foreach}
</UL>
</div>
{/if}

