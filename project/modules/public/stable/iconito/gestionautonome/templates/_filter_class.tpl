{if $ppo->withLabel}
  <label> Classe :</label>
{/if}
<select class="form" name="classroom">
  {if $ppo->withEmpty}
    {if $ppo->withEmptyLabel}
      <option value="" label="Aucune">Aucune</option>
    {else}
      <option value="" label="">&nbsp;</option>
    {/if}
  {/if}
  {html_options values=$ppo->classesIds output=$ppo->classesNames selected=$ppo->selected}
</select>