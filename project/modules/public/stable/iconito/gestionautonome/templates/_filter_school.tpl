{if $ppo->withLabel}
  <label> Ecole :</label>
{/if}
<select class="form" name="school">
  {html_options values=$ppo->schoolsIds output=$ppo->schoolsNames selected=$ppo->selected}
</select>