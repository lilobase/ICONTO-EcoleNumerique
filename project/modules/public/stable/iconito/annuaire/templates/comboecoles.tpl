
{if $items || $linesSup}

	{assign var=current_type value=""}

	<select name="{$fieldName}" {$attribs}>
	
	{foreach from=$linesSup item=item}
		<option value="{$item.value}"{if $item.value == $value} selected="selected"{/if}>{$item.libelle}</option>
	{/foreach}

	{assign var="optgroup" value=0}
	{foreach from=$items item=item}

		{*
		{if $item.type <> $current_type}
			<option value="">==== {$item.type} ====</option>
			{assign var=current_type value=$item.type}
		{/if}
		*}
		
		{if $item.id == 0}
			{if $optgroup}</optgroup>{/if}
			<optgroup label="{$item.nom|escape}">
			{assign var="optgroup" value=1}
		{else}
			<option value="{$item.id}"{if $item.id == $value} selected="selected"{/if}>{$item.nom|escape}{if $item.type} ({$item.type|escape}){/if}</option>
		{/if}
	{/foreach}
	{if $optgroup}</optgroup>{/if}
	
	</select>
{/if}