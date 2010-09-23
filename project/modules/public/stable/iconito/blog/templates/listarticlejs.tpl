{if count($listArticle)}
	{assign var=i value=0}
	<div class="blog_articles">
	{foreach from=$listArticle item=article}
		{if $parCols>1 }
			{if $i%$parCols eq 0}
				{if $i>0}</div>{/if}		
				<div style="float:left;width:{$widthColonne};">
			{/if}
		{/if}
		<div class="bart_item">
		{if $showdate OR $showcategorie}<div class="bart_date">
		{if $showdate}{i18n key="blog|blog.article.presentationshort" 1=$article->date_bact|datei18n:text}{/if}
    {if $showcategorie}
		{assign var=cptCat value=1}
		{foreach from=$article->categories item=categorie }
			{$categorie->name_bacg|escape}
			{if $cptCat<$article->categories|@count} - {/if}
			{assign var=cptCat value=$cptCat+1}
		{/foreach}
    {/if}
		</div>{/if}
		{if $showtitle}<div class="bart_title"><a href="{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}">{$article->name_bact}</a></div>{/if}
		{if $chapo}<div class="bart_chapo">{$article->sumary_html_bact}</div>{/if}
		{if $hr}<div class="hr"></div>{/if}
		</div>
		{assign var=i value=$i+1}
	{/foreach}
	{if $parCols>1 && $i>0}</div>{/if}
	{if $parCols>1 }
		<br clear="left" />
	{/if}
	</div>
{else}
	{i18n key="blog|blog.message.noArticle"}
{/if}
