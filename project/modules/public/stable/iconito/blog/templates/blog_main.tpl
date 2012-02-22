<div id="blog-template">
    <div id="blog-sidebar">
        <a class="blog-rss" href="{copixurl dest="blog||rss" blog=$blog->url_blog}" title="{i18n key="blog.nav.rss"}"></a>
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