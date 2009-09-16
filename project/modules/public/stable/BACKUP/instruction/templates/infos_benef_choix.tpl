
{foreach from=$ppo->list item=item}

{assign var=champId value=$ppo->rChamp->id}

{if $ppo->rChamp->type eq 'RADIO'}

	<input type="radio" id="choix_{$item->valeur}" name="champ[{$champId}]" value="{$item->valeur}"{if $ppo->values[$champId] eq $item->valeur} checked {/if}{if $ppo->rChamp->on_change} onChange="onChangeInfosBenef(this,'{$ppo->rChamp->on_change}');"{/if}/><label for="choix_{$item->valeur}"> {$item->libelle|escape}</label><br/>
	
	
	{if !$ppo->values[$champId] OR $ppo->values[$champId] eq $item->valeur}
	<script>
	onLoadOnChanges.push ('choix_{$item->valeur}');
	</script>
	{/if}
	
	
{/if}

{/foreach}



	
