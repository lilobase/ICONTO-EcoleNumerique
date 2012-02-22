{literal}
<style>
<!--
-->
</style>
{/literal}

<table class="visibility">

<tr><th></th>
{foreach from=$user_types item=dst_type}
{assign var="dst_type_small" value=$dst_type|lower}
<th class="top">{i18n key="kernel|kernel.codes.$dst_type_small"}</th>
{/foreach}
</tr>

{foreach from=$user_types item=src_type}
{assign var="src_type_small" value=$src_type|lower}
<tr>
<th class="left">{i18n key="kernel|kernel.codes.$src_type_small"}</th>
{foreach from=$user_types item=dst_type}
<td style="background-color:
	{if $visibility[$src_type][$dst_type]=="FULL"}#6C6
	{elseif $visibility[$src_type][$dst_type]=="NONE"}#F66
	{else}#99F
	{/if}
;">{$visibility[$src_type][$dst_type]}</td>
{/foreach}
</tr>
{/foreach}

</table>
