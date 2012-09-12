
<div id="article">
{foreach from=$arFlux item=news}
	<UL>
  {if $news.link != ''}
	<h4><a href="{$news.link}" title="" target="_new">{$news.title}</a></h4>
  {else}
	<h4>{$news.title}</h4>
  {/if}
	<div class="desc"><span style="color:gray;">{$news.date_timestamp|datei18n:"date_short_time"} - </span>{$news.description}</div>
	{if $news.enclosure}
	<div style="margin-left:20px;">
	{foreach from=$news.enclosure item=encl}
	<img src="{copixresource path="img/blog/enclosure.gif"}" width="16" height="16" /><a href="{$encl.url}" type="{$encl.type}">{$encl.url|substrpos}</a> ({$encl.length|human_file_size})
	{/foreach}
	</div>
	{/if}

	</UL>
	<!--<div class="summary">{$news.summary}</div>-->
{/foreach}
</div>
