{if $ppo->withLabel}
  <label>Ville</label>
{/if}
<select class="form" name="{if $ppo->name neq null}{$ppo->name}{else}city{/if}">
  {if $ppo->withEmpty}
    {if $ppo->labelEmpty}
      <option value="" label="{$ppo->labelEmpty}">{$ppo->labelEmpty}</option>
    {else}
      <option value="" label="">&nbsp;</option>
    {/if}
  {/if}
  {html_options values=$ppo->citiesIds output=$ppo->citiesNames selected=$ppo->selected}
</select>