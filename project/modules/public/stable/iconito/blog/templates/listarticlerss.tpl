<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title>{$rss.title|rss}</title>
    <link>{$rss.link|rss}</link>
    <description>{$rss.description|rss}</description>
    {if $rss.logo}<image>
  		<url>{copixurl}{copixurl dest="blog|admin|logo" id_blog=$blog->id_blog}</url>
		</image>{/if}
    <language>{$rss.language}</language>
    <copyright>{$rss.copyright|rss}</copyright>
    {*<dc:creator>{$rss.webmaster|rss}</dc:creator>*}
    <generator>{$rss.generator|rss}</generator>

{if count($listArticle)}
      {assign var=date value=null}
	   {foreach from=$listArticle item=article}


     <item>
     <title>{$article->name_bact}</title>
     <link>{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}</link>
     <guid isPermaLink="true">{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}</guid>
     <pubDate>{$article->dateRFC822}</pubDate>
    
     {if count($article->categories)}{assign var=cptCat value=0}
     <category>{foreach from=$article->categories item=category}{if $cptCat>0}, {/if}{assign var=cptCat value=$cptCat+1}{$category->name_bacg|rss}{/foreach}</category>
     {/if}
     
     {if $blog->has_comments_activated eq 1}<comments>{copixurl}{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}#comments</comments>{/if}
     <description>{$article->sumary_html_bact|rss}</description>
		 {$article->sumary_bact|rss_enclosure}

    </item>
	   {/foreach}
{/if}
  </channel>

</rss>
