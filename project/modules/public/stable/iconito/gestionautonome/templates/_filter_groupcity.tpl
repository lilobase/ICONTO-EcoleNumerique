{if $ppo->withLabel}
  <label>Groupe de ville</label>
{/if}
<select class="form" name="{if $ppo->name neq null}{$ppo->name|escape}{else}groupcity{/if}">
  {if $ppo->withEmpty}
    {if $ppo->labelEmpty}
      <option value="" label="{$ppo->labelEmpty}">{$ppo->labelEmpty|escape}</option>
    {else}
      <option value="" label="">&nbsp;</option>
    {/if}
  {/if}
  {html_options values=$ppo->cityGroupsIds output=$ppo->cityGroupsNames selected=$ppo->selected}
</select>
