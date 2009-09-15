
{if $items || $linesSup}

	{assign var=current_type value=""}

	<SELECT NAME="{$fieldName}" {$attribs}>
	
	{foreach from=$linesSup item=item}
		<OPTION VALUE="{$item.value}"{if $item.value == $value} SELECTED{/if}>{$item.libelle}</OPTION>
	{/foreach}

	{assign var="optgroup" value=0}
	{foreach from=$items item=item}

		{*
		{if $item.type <> $current_type}
			<OPTION VALUE="">==== {$item.type} ====</OPTION>
			{assign var=current_type value=$item.type}
		{/if}
		*}
		
		{if $item.id == 0}
			{if $optgroup}</optgroup>{/if}
			<optgroup label="{$item.nom|htmlentities}">
			{assign var="optgroup" value=1}
		{else}
			<OPTION VALUE="{$item.id}"{if $item.id == $value} SELECTED{/if}>{$item.nom|htmlentities}{if $item.type} ({$item.type|htmlentities}){/if}</OPTION>
		{/if}
	{/foreach}
	{if $optgroup}</optgroup>{/if}
	
	</SELECT>
{/if}