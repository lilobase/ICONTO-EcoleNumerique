{if $ppo->withLabel}
  <label> Groupe de ville :</label>
{/if}
<select class="form" name="groupcity">
  {html_options values=$ppo->cityGroupsIds output=$ppo->cityGroupsNames selected=$ppo->selected}
</select>