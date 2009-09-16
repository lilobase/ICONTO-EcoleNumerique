{if $txt}
	{$txt}
{else}
		{if $mode eq 'button'}
		<button onclick="{$href}" class="default">{$libelle}</button>
		{else}
		<a href="{$href}">{$libelle}</a>
		{/if}
{/if}