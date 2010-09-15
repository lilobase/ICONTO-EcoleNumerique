{if count($listPage)}
	{assign var=i value=0}
	<div class="blog_pages">
	{foreach from=$listPage item=page}
		{if $i%$parCols eq 0}
			{if $i>0}</div>{/if}		
			<div style="float:left;width:{$widthColonne};">
		{/if}
		<div class="item">
		<div class="bpage_title"><a href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{$page->name_bpge}</a></div>
		{if $content}<div class="bpage_content">{$page->content_html_bpge}</div>{/if}
		{if $hr}<div class="hr"></div>{/if}
		</div>
		{assign var=i value=$i+1}
	{/foreach}
	{if $i>0}</div>{/if}
	<br clear="left" />
	</div>
{else}
	-
{/if}
