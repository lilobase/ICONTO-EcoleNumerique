{if $items || $linesSup}
    <select name="{$fieldName}" {$attribs}>

        {foreach from=$linesSup item=item}
            <option value="{$item.value}"{if $item.value == $value} selected="selected"{/if}>{$item.libelle}</option>
        {/foreach}

        {foreach from=$items item=item}
            <option value="{$item.id}"{if $item.id == $value} selected="selected"{/if}>{$item.nom|escape}</option>
        {/foreach}

    </select>{/if}