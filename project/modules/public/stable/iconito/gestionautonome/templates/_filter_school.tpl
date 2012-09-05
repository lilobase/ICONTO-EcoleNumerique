{if $ppo->withLabel}
  <label>Ecole</label>
{/if}
<select class="form" name="{if $ppo->name neq null}{$ppo->name}{else}school{/if}">
  {if $ppo->schoolsIds|@count > 0}
    {if $ppo->withEmpty}
      <option value="" label="">&nbsp;</option>
    {/if}
    {html_options values=$ppo->schoolsIds output=$ppo->schoolsNames selected=$ppo->selected}
  {else}
    <option value="" label="">-</option>
  {/if}
</select>