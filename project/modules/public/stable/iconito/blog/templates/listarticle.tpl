
<div id="listArticles">
{if isset($cat)}
<H2><b>{$cat->name_bacg}</b></H2>
{/if}

{if count($listArticle)}
      {assign var=date value=null}
	   {foreach from=$listArticle item=article}
	   {if $date neq $article->date_bact}
         {assign var=date value=$article->date_bact}
         <div class="day">{$article->date_bact|datei18n:text}</div>
      {/if}
		<!--Modification vboniface 06.11.2006 bug mantis 54
			ancienne version:<div class="postTitle"><a href="{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}">{$article->name_bact}</a></div>-->
        <div class="postTitle"><a href="{copixurl dest="blog||showArticle" blog=$blog->url_blog article=$article->url_bact}">{$article->name_bact}</a></div>
         <div class="postInfo">

						 {assign var=cptCat value=1}
						 {assign var=listCat value=""}
             {foreach from=$article->categories item=categorie}
							  {copixurl dest="blog||" blog=$blog->url_blog cat=$categorie->url_bacg assign="url"}
							  {assign var=thisA value='<a href="'|cat:$url|cat:'">'|cat:$categorie->name_bacg|cat:'</a> '"}
						 		{assign var=listCat value=$listCat|cat:$thisA}
								{if $cptCat<$article->categories|@count}{assign var=listCat value=$listCat|cat:' - '}{/if}
								{assign var=cptCat value=$cptCat+1}
             {/foreach}
						 {if !$article->categories|@count}{i18n key="blog.article.nocategory" assign="listCat"}{/if}
						 {i18n key="blog.message.theAtIn" day=$article->date_bact|datei18n:text time=$article->time_bact|hour_format:"%H:%i" categ=$listCat}
         </div>
         <div class="postContent">
         {$article->sumary_html_bact}
         {if strlen($article->content_html_bact) > 0}
         <!--Modification vboniface 06.11.2006 bug mantis 54
		 ancienne version: <a class="suite" href="{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}">{i18n key="blog.message.readNext"}</a><br />-->
		 <a class="suite" href="{copixurl dest="blog||showArticle" blog=$blog->url_blog article=$article->url_bact}">{i18n key="blog.message.readNext"}</a><br />
         {/if}
		 {assign var=id_bact value=$article->id_bact}
		 {if $arNbCommentByArticle[$id_bact] neq 0} 
			 <a class="suite" href="{copixurl dest="blog||showArticle" blog=$blog->url_blog article=$article->url_bact}#comments">{i18n key="blog.messages.article.comment" pNb=$arNbCommentByArticle[$id_bact]}</a>
         {/if}
         </div>
	   {/foreach}
	   <p class="pager">
	   {$pager}
	   </p>
{else}
{i18n key="blog.message.noArticle"}
{/if}
</div>

