{if count($data)}
	<table>
	{foreach from=$data item=entry}
		<tr>
		<th>{$entry->logs_date}</th>
		<td>{$entry->logs_user_ip}</td>
		<td><acronym title="{$entry->logs_url|htmlentities}">{$entry->logs_mod_name|htmlentities}/{$entry->logs_mod_action|htmlentities}</acronym></td>
		<td>{$entry->logs_message|htmlentities} ({$entry->logs_user_login|htmlentities})</td>
		</tr>
	{/foreach}
	</table>
{else}
	Pas de logs...
{/if}
