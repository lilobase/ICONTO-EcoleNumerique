
{foreach from=$ppo->list item=item}

{if $item->disp_titre}<h3>{$item->nom|escape}</h3>{/if}
	
	{copixzone process=instruction|infos_benef_champs groupe=$item->id parent=0 values=$ppo->values benef_type=$ppo->benef_type suffixe=$ppo->suffixe}
	
{/foreach}	

	
