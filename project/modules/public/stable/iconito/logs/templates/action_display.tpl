{if count($data)}
	<table>
	{foreach from=$data item=entry}
		<tr>
		<th>{$entry->logs_date}</th>
		<td>{$entry->logs_user_ip}</td>
		<td><acronym title="{$entry->logs_url|escape}">{$entry->logs_mod_name|escape}/{$entry->logs_mod_action|escape}</acronym></td>
		<td>{$entry->logs_message|escape} ({$entry->logs_user_login|escape})</td>
		</tr>
	{/foreach}
	</table>
{else}
	Pas de logs...
{/if}
