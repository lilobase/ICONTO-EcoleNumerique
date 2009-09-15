
{if count($listLink)}
<div>
<h2>{i18n key="blog.nav.links"}</h2>
<UL>
	   {foreach from=$listLink item=link}
         <LI><a href="{$link->url_blnk}" target="_blank">{$link->name_blnk}</a></LI>
	   {/foreach}
</UL>
</div>
{/if}

