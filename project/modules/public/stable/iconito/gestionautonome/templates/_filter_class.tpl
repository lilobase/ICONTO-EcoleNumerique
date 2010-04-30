<label for="class"> Classe :</label>
<select class="form" name="class" id="class">
  {html_options values=$ppo->classesIds output=$ppo->classesNames selected=$ppo->listFilters.class}
</select>