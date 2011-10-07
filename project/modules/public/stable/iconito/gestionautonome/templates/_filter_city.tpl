{if $ppo->withLabel}
  <label> Ville :</label>
{/if}
<select class="form" name="city">
  {html_options values=$ppo->citiesIds output=$ppo->citiesNames selected=$ppo->selected}
</select>