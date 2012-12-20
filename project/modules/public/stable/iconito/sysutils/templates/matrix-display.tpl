{literal}
<style>
<!--
table.viewItems tr.highlight td { font-weight: bold; }
-->
</style>

<script>
$(function() {
	$( "#tabs" ).tabs();
});
</script>

{/literal}

<form method="post">
<input type="hidden" name="save" value="1" />


<div id="tabs">
<ul>
{foreach from=$ppo->from item=from name=from}
<li><a href="#tabs-{math equation="1 + x" x=$smarty.foreach.from.index}">{$ppo->trad.$from}</a></li>
{/foreach}
</ul>
	
{foreach from=$ppo->from item=from name=from}

<div id="tabs-{math equation="1 + x" x=$smarty.foreach.from.index}">

<table class="viewItems">
<tr>
	{* <th scope="col" class="profil">Profil</th> *}
	<th scope="col">Par rapport à</th>
	{foreach from=$ppo->where item=where name=where}
		<th>{$ppo->trad.$where}</th>
	{/foreach}
</tr>

{foreach from=$ppo->to item=to name=to}

<tr class="{if $smarty.foreach.to.index % 2 == 0}even {else}odd {/if}{if $from eq $to}highlight {/if}">
	{* {if $smarty.foreach.to.first}<td rowspan="{$ppo->to|@count}"><strong>{$ppo->trad.$from}</strong></td>{/if} *}
	<td>{$ppo->trad.$to}</td>
	{foreach from=$ppo->where item=where name=where}
		<td>
			{foreach from=$ppo->do item=do name=do}
			{* <input type="checkbox" name="right_{$from}_{$to}_{$where}_{$do}" id="right_{$from}_{$to}_{$where}_{$do}" value="1" {if $ppo->right.$from.$to.$where.$do}checked="checked"{/if}><label for="right_{$from}_{$to}_{$where}_{$do}">{$do|strtolower|ucfirst}</label> *}
			
			{if ( $from eq "USER_EXT" || $to eq "USER_EXT" || $from eq "USER_ATI" || $to eq "USER_ATI" || $from eq "USER_ADM" || $to eq "USER_ADM" ) && ( $where neq "NOWHERE" && $where neq "ROOT" ) }
			{else}
				<input type="radio" name="right_{$from}_{$to}_{$do}" id="right_{$from}_{$to}_{$where}_{$do}" value="{$where}" {if $ppo->right.$from.$to.$where.$do || $where eq "NOWHERE"}checked="checked"{/if}><label for="right_{$from}_{$to}_{$where}_{$do}">{$ppo->trad.$do}</label>{if ! $smarty.foreach.do.last}<br/>{/if}
			{/if}
			{/foreach}
		</td>
	{/foreach}
</tr>

{/foreach}

</table>

</div>

{/foreach}

</div>
<div class="center">
<input type="reset" value="Annuler" class="button button-cancel" />
<input type="submit" value="Enregistrer" class="button button-confirm" />
</div>
</form>