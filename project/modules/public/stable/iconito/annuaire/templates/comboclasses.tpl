
{if $items || $linesSup}
	<select name="{$fieldName}" {$attribs}>
	
	{foreach from=$linesSup item=item}
		<option value="{$item.value}"{if $item.value == $value} selected="selected"{/if}>{$item.libelle}</option>
	{/foreach}

	{assign var="optgroup" value=0}
	{foreach from=$items item=item}
		{if $item.id == 0}
			{if $optgroup}</optgroup>{/if}
			<optgroup label="{$item.nom|escape}">
			{assign var="optgroup" value=1}
		{else}
			<option value="{$item.id}"{if $item.id == $value} selected="selected"{/if}>{$item.nom|escape}</option>
		{/if}
	{/foreach}
	{if $optgroup}</optgroup>{/if}

	</select>
{/if}