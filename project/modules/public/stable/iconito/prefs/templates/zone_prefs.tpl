<!--
Modules :

<ol>
<li>Préférences générales</li>
{if $modules neq null}
	{foreach from=$modules item=module}
		<li>{$module->type}</li>
	{/foreach}
{/if}
</ol>
-->
