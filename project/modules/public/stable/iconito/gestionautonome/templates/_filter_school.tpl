<label for="school"> Ecole :</label>
<select class="form" name="school" id="school">
  {html_options values=$ppo->schoolsIds output=$ppo->schoolsNames selected=$ppo->selected}
</select>