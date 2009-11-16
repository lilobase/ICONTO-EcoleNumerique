{if $combofolders}
	<SELECT NAME="{$fieldName}" {$attribs}>
	{foreach from=$linesSup item=item}
		<OPTION VALUE="{$item.value}">{$item.libelle}</OPTION>
	{/foreach}
	<OPTION VALUE="0"{if 0 == $folder} SELECTED{/if}>{i18n key="malle.root"}</OPTION>
	{foreach from=$combofolders item=item}
	<OPTION VALUE="{$item.id}"{if $item.id == $folder} SELECTED{/if}>{$item.nom|escape|indent:$item.niveau:"&nbsp;&middot;&nbsp;"}</OPTION>
	{/foreach}
	</SELECT>
{/if}