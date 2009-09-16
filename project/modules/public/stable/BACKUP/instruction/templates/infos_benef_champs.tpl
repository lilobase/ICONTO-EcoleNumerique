
{foreach from=$ppo->list item=item}

{assign var=champId value=$item->id}


{assign var=name value="champ"|cat:$ppo->suffixe|cat:"["|cat:$item->id|cat:"]"}



{assign var=value value=$ppo->values[$champId]}


<p>

{if $item->type eq 'CHECKBOX'}

<input type="checkbox" id="champ_{$ppo->suffixe}{$item->id}" name="{$name}" value="1" {if $value==1}checked {/if}/><label for="champ_{$ppo->suffixe}{$item->id}"> {$item->libelle|escape}</label>

{elseif $item->type eq 'RADIO'}
	{copixzone process=inscription|infos_benef_choix champ=$item values=$ppo->values}

{elseif $item->type eq 'VARCHAR'}
	{$item->libelle|escape} : {inputtext name=$name value=$value maxlength=40 style="width:200px;"}

{else}
	<div style="color:red">Format de champ {$item->type} non g&eacute;g&eacute;.</div>

{/if}



{if $item->id|in_array:$ppo->default}
<br/><span style="color:red">Cette valeur est positionn&eacute;es par d&eacute;faut, vous devez enregistrer pour la prendre en compte.</span>
{/if}

</p>

{/foreach}



	
