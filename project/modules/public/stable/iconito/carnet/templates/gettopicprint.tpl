
	{assign var=sep value=""}
	
{foreach from=$topic->eleves item=item}{$sep}
<table cellpadding="0" cellspacing="0">
<thead style="display: table-header-group;">
<tr>
<td class="carnet_message">
<div class="carnet_message">
	{$topic->message|render:$topic->format}

</div>
	
</td>
</tr>
</thead>
</table>	<br/>
{/foreach}

