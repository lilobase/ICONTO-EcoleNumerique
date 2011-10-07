{if $ppo->withLabel}
  <label> Classe :</label>
{/if}
<select class="form" name="classroom">
  {html_options values=$ppo->classesIds output=$ppo->classesNames selected=$ppo->selected}
</select>