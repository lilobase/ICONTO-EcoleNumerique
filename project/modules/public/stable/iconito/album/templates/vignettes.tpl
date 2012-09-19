
<div align="center">

<h1>{$message}</h1>
{if ! $finish}
<br />
<table style="border:0;"><tr><td valign="middle">
	<table width="400" style="border: 2px solid #666;" cellpadding="0" cellspacing="3">
	<tr>
	<td bgcolor="#666666" width="{math equation="400 * ( bar_value / bar_max ) + 1"
	      bar_value=$bar_value
	      bar_max=$bar_max}">&nbsp;</td>
	<td>&nbsp;</td>
	</table>
</td><td valign="middle">
	<h2 style="padding: 0px; margin: 0px;">{math equation="( bar_value / bar_max ) * 100"
		bar_value=$bar_value
		bar_max=$bar_max
		format="%d"}%</h2>
</td></tr></table>
{/if}
</div>

