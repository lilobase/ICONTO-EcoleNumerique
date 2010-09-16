{if count($listPage)}
	{assign var=i value=0}
	<div class="blog_pages">
	{foreach from=$listPage item=page}
		{if $parCols>1 }
			{if $i%$parCols eq 0}
				{if $i>0}</div>{/if}		
				<div style="float:left;width:{$widthColonne};">
			{/if}
		{/if}
		<div class="bpage_item">
		<div class="bpage_title"><a href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{$page->name_bpge}</a></div>
		{if $content}<div class="bpage_content">{$page->content_html_bpge}</div>{/if}
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
	-
{/if}
