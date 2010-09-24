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
		{if $showtitle}<div class="bpage_title"><a href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{$page->name_bpge|escape}</a></div>{/if}
		{if $content}<div class="bpage_content">
      {if $truncate AND $page->content_html_bpge|strlen>$truncate}
        {$page->content_html_bpge|truncate:$truncate}
        <p class="suite"><a title="{$page->name_bpge|escape}" href="{copixurl dest="blog||showPage" blog=$page->url_blog page=$page->url_bpge}">{i18n key='blog|blog.message.readNext'}</a></p>
      {else}
        {$page->content_html_bpge}
      {/if}
      </div>
    {/if}
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
