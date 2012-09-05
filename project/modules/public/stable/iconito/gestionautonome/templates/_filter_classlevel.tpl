{if !empty($ppo->niveauxIds)}
  {if $ppo->withLabel}
    <label>Niveau</label>
  {/if}
  <select class="form" name="{if $ppo->name neq null}{$ppo->name}{else}level{/if}">
    {if $ppo->withEmpty}
      {if $ppo->labelEmpty}
        <option value="" label="{$ppo->labelEmpty}">{$ppo->labelEmpty}</option>
      {else}
        <option value="" label="">&nbsp;</option>
      {/if}
    {/if}
    {html_options values=$ppo->niveauxIds output=$ppo->niveauxNames selected=$ppo->selected}
  </select>
{/if}