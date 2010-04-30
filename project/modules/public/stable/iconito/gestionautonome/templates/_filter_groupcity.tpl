<label for="groupcity"> Groupe de ville :</label>
<select class="form" name="groupcity" id="groupcity">
  {html_options values=$ppo->cityGroupsIds output=$ppo->cityGroupsNames selected=$ppo->selected}
</select>