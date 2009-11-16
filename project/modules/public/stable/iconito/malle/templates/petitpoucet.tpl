
{if $petitpoucet}
<DIV CLASS="malle_petit_poucet">
	{foreach from=$petitpoucet item=item}
	
	/ <A HREF="{$item.lien}">{$item.libelle|escape}</A>
	
	{/foreach}
</DIV>
{/if}


