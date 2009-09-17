<link rel="stylesheet" href="/styles/topnav.css" type="text/css" />

{if $nodes neq null}
	<div id="mainmenu">
	<ul id="top_nav">

	<li style="background-image: none;">
	<a href="{copixurl dest="kernel||getNodes"}"><span class="ctr">Profils{if $home neq null} : {$home}{/if}</span></a>
	<ul>
	{foreach from=$nodes item=data_val key=node_type}
	{if $node_type|truncate:3:"":true == "BU_"}
		{foreach from=$data_val item=node_val key=node_id}
			<li><a href="{copixurl dest="kernel||doSelectHome" type=$node_type id=$node_id}">{$node_type}/{$node_id} <i>({$node_val->droit})</i> - Mods : {$node_val->enabled|@count}/{$node_val->available_type|@count}</a></li>
			<!--
			{foreach from=$node_val->enabled item=module_val key=module_key}
				<li>{$module_val->module_type}
				{if isset($module_val->module_id)}
					: {$module_val->module_id}
				{/if}
				</li>
			
			{/foreach}
			-->
		{/foreach}
	{/if}
	{/foreach}
	</ul>
	</li>

	{if $modules neq null}
	<li style="background-image: none;">
	<a href="{copixurl dest="kernel||getHome"}"><span class="ctr">Modules</span></a>
	<ul>
	{foreach from=$modules item=val_modules key=key_modules}
		{assign var="module_type_array" value="_"|split:$val_modules->module_type|lower}
		<li><a href="{copixurl dest="$module_type_array[1]||go" id=$val_modules->module_id}">{$module_type_array[1]|capitalize}:{$val_modules->module_id}</a></li>
	{/foreach}
	</ul>
	</li>
	{/if}
	
	
	</ul>
	</div>
{else}
	Il n'y a aucun lien avec la base unique.
{/if}
