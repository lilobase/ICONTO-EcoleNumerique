{if count($listPage)}
<div id="blog-pages">
<h2>{i18n key="blog.nav.pages"}</h2>
<ul>
    {foreach from=$listPage item=page}
    <li><a href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{$page->name_bpge}</a></li>
    {/foreach}
</ul>
</div>
{/if}