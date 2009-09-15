
<h2>{$name} :</h2>

<div>
{if $stats}
	<table class="stats_table" cellspacing="1" cellpadding="1">
	<tr>
		<th>{i18n key="stats|stats.col.objet"}</th>
		<th>{i18n key="stats|stats.col.nb"}</th>
	</tr>
	{foreach from=$stats item=stat}
	<tr>
		<td>{$stat->objet_name}</td>
		<td>{$stat->nb}</td>
	</tr>
	
	{/foreach}
	</table>
{else}
	<i>{i18n key="stats|stats.no.data"}</i>
{/if}
</div>

