{if $data neq null}
	<ol>
	{foreach from=$data item=data_item}
		<li><h1>[{$data_item->type}/{$data_item->id}]: {$data_item->title}</h1>

		{if $data_item->enabled neq null}
			<h2>Modules créés</h2>
			<ol>
			{foreach from=$data_item->enabled item=enabled_item}
				<li>
				<b>{$enabled_item->module_type}
				{if isset($enabled_item->module_id)} / {$enabled_item->module_id}{/if}
				</b> ({$data_item->droit})
				</li>
			{/foreach}
			</ol>
		{/if}

		{if $data_item->available_type neq null}
			<h2>Modules possibles</h2>
			<ol>
			{foreach from=$data_item->available_type item=avail_item}
				<li>
				<b>{$avail_item->module_type}</b>
				</li>
			{/foreach}
			</ol>
		{/if}


		</li>
	{/foreach}
	</ol>
{else}
	Aucun module...
{/if}