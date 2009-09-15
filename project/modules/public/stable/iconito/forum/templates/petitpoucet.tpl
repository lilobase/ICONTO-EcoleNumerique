
{if $petitpoucet}
<DIV CLASS="forum_petit_poucet">
	{foreach from=$petitpoucet item=item}
	
	/ <A HREF="{$item.lien}">{$item.libelle}</A>
	
	{/foreach}
</DIV>
{/if}


