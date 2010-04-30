<label for="city"> Ville :</label>
<select class="form" name="city" id="city">
  {html_options values=$ppo->citiesIds output=$ppo->citiesNames selected=$ppo->selected}
</select>