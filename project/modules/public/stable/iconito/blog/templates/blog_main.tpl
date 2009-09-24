
<link rel="stylesheet" type="text/css" href="{copixurl dest="blog||getBlogCss" id_blog=$blog->id_blog}" />

<div id="mainBlog">
<DIV ID="mainContent">
{$ListArticle}
{$Article}
{$Page}
{$Flux}
</DIV>

<DIV ID="mainSideBar">

{if $blog->logo_blog!=''}
<A HREF="{copixurl dest="blog||" blog=$blog->url_blog}" title="{i18n key="blog.nav.accueil"}"><div class="bloglogo">
<img src="{copixurl dest="blog||logo" id_blog=$blog->id_blog}" border=0></div></A>
{elseif $blog->parent.type}
<A HREF="{copixurl dest="blog||" blog=$blog->url_blog}" title="{i18n key="blog.nav.accueil"}"><div class="bloglogo">
<img src="{copixresource path="img/blog/default-logo-`$blog->parent.type`.gif"}" border=0></div></A>
{/if}

<H2><div style="float:right; margin-top:3px;"><a href="{copixurl dest="blog||rss" blog=$blog->url_blog}"><img src="{copixresource path="img/blog/feed-icon-16x16.png"}" width="16" height="16" border="0" alt="RSS" title="RSS" /></a></div><A HREF="{copixurl dest="blog||" blog=$blog->url_blog}" title="{i18n key="blog.nav.accueil"}">{i18n key="blog.nav.accueil"}</A></H2>
{$ListPage}
{$ListCategory}
{$ListLink}
{$ListArchive}
{$ListFluxRss}
{$ListSearch}

</div>
<br clear="all" />
</div>
