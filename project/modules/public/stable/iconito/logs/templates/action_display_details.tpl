{literal}
<style>
<!--
	TABLE.logs_details TH {
		text-align: right;
		padding-right: 10px;
	}
-->
</style>
{/literal}

<table class="logs_details">
	{foreach from=$data key=key item=item}
	<tr>
		<th>{$key}</th>
		<td>{$item|escape}</td>
	</tr>
	{/foreach}
</table>
