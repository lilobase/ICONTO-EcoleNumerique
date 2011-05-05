<p class="title">{i18n key="cahier.message.todo_work"}</p><span><a href="{copixurl dest="cahier||addWork" nid=$ppo->nid day=$ppo->day month=$ppo->month year=$ppo->year todo=1}">{i18n key="cahier.message.add_todo_work"}</a></span>

{foreach from=$ppo->works item=work}
<div class="domain">
  <h3>{$work->nom}</h3>
  {$work->description}
</div>
{/foreach}