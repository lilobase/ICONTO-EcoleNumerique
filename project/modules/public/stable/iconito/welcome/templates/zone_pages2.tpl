{if $titre}<div class="titre">{$titre}</div>{/if}
{if count($listPages)}
<ul>
{foreach from=$listPages item=page}  
<li><a href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{$page->name_bpge}</a></li>
{/foreach}
</ul>
{/if}