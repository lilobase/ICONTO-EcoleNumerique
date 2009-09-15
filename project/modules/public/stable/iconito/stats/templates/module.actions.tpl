
<h2>{i18n key="stats|stats.actions.titre"} :</h2>

<div>
{if $getStatsModule}
	<table class="stats_table" cellspacing="1" cellpadding="1">
	<tr>
		<th>{i18n key="stats|stats.col.action"}</th>
		<th>{i18n key="stats|stats.col.nb"}</th>
	</tr>
	{foreach from=$getStatsModule item=blog}
	<tr>
		<td>{$blog->action_name}</td>
		<td>{$blog->nb}</td>
	</tr>
	
	{/foreach}
	</table>
{else}
	<i>{i18n key="stats|stats.no.data"}</i>
{/if}
</div>

