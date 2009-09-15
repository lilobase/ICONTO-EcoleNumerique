<select name="{$name}" id="{$name}" {$extra}>
<option value=""{if "" eq $selected OR (is_array($selected) AND ""|in_array:$selected)} selected{/if}>--- ({$list|@count} cr&egrave;che{if $list|@count>1}s{/if}) ---</option>

{foreach from=$list item=item}
	<option value="{$item->id}"{if $item->id eq $selected OR (is_array($selected) AND $item->id|in_array:$selected)} selected{/if}{if $item->id eq $disabled OR (is_array($disabled) AND $item->id|in_array:$disabled)} disabled{/if}>{$item->type_nom|escape} {$item->nom|escape}</option>
{/foreach}
</select>