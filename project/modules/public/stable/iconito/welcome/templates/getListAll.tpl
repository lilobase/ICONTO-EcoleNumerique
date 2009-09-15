{if $url_list neq null}
<table><tr><td>URL</td><td>Node type</td><td>Node Id</td></tr>
	{foreach from=$url_list item=url_item}
		<tr>
			<td><a href="{copixurl dest="welcome||getList" url=$url_item->url}">{$url_item->url}</a></td>
			<td>{$url_item->node_type}</td>
			<td>{$url_item->node_id}</td>
		</tr>
	{/foreach}
</table>
{/if}

{if $node neq null}
<ul>
<li>URL: {$node->url}</li>
<li>Type: {$node->node_type}</li>
<li>Id: {$node->node_id}</li>
</ul>
{/if}