
{if $items || $linesSup}
	<SELECT NAME="{$fieldName}" {$attribs}>
	
	{foreach from=$linesSup item=item}
		<OPTION VALUE="{$item.value}"{if $item.value == $value} SELECTED{/if}>{$item.libelle}</OPTION>
	{/foreach}
	
	{foreach from=$items item=item}
	<OPTION VALUE="{$item.id}"{if $item.id == $value} SELECTED{/if}>{$item.nom|htmlentities}</OPTION>
	{/foreach}

	</SELECT>{/if}