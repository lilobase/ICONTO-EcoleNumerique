<div id="blog-template">
    <div id="blog-sidebar">
        <div style="float:right; margin-top:3px;">
            <a href="{copixurl dest="blog||rss" blog=$blog->url_blog}"><img src="{copixresource path="img/blog/feed-icon-16x16.png"}" width="16" height="16" border="0" alt="RSS" title="RSS" /></a>
        </div>
        <div class="blog-header">
            <a href="{copixurl dest="blog||" blog=$blog->url_blog}" title="{i18n key="blog.nav.accueil"}"
            {if $blog->logo_blog!=''}
            ><img src="{copixurl dest="blog||logo" id_blog=$blog->id_blog}" border=0>
            {elseif $blog->parent.type}
            id="blog-logo">
            {/if}
            <br/>
            {$blog->name_blog}
            </a>
        </div>
        {$ListPage}
        {$ListCategory}
        {$ListLink}
        {$ListArchive}
        {$ListFluxRss}
        {$ListSearch}
    </div>
    <div id="blog-content">
        {$ListArticle}
        {$Article}
        {$Page}
        {$Flux}
    </div>
</div>