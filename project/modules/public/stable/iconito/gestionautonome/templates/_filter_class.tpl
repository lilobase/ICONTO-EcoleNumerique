{if $ppo->withLabel}
  <label>Classe</label>
{/if}

<select class="form" name="{if $ppo->name neq null}{$ppo->name}{else}classroom{/if}">
  {if $ppo->classesIds|@count > 0}
    {if $ppo->withEmpty}
      {if $ppo->labelEmpty}
        <option value="" label="{$ppo->labelEmpty}">{$ppo->labelEmpty}</option>
      {else}
        <option value="" label="">&nbsp;</option>
      {/if}
    {/if}
    {html_options values=$ppo->classesIds output=$ppo->classesNames selected=$ppo->selected}
  {else}
    <option value="" label"-">-</option>
  {/if}
</select>