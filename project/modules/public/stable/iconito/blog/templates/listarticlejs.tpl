{if count($listArticle)}
	{assign var=i value=0}
	<div class="js">
	{foreach from=$listArticle item=article}
		{if $i%$parCols eq 0}
			{if $i>0}</div>{/if}		
			<div style="float:left;width:{$widthColonne};">
		{/if}
		<div class="item">
		<div class="date">
		{i18n key="blog|blog.article.presentationshort" 1=$article->date_bact|datei18n:text}
		{assign var=cptCat value=1}
		{foreach from=$article->categories item=categorie }
			{$categorie->name_bacg}
			{if $cptCat<$article->categories|@count} - {/if}
			{assign var=cptCat value=$cptCat+1}
		{/foreach}
		</div>
		<div class="titre"><a href="{copixurl dest="blog||showArticle" blog=$article->url_blog article=$article->url_bact}">{$article->name_bact}</a></div>
		{if $chapo}<div class="chapo">{$article->sumary_html_bact}</div>{/if}
		{if $hr}<div class="hr"></div>{/if}
		</div>
		{assign var=i value=$i+1}
	{/foreach}
	{if $i>0}</div>{/if}
	<br clear="left" />
	</div>
{else}
	{i18n key="blog|blog.message.noArticle"}
{/if}
