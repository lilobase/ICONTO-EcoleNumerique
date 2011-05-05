<p class="title">{i18n key="cahier.message.classroom_work"}</p><span><a href="{copixurl dest="cahier||addWork" nid=$ppo->nid day=$ppo->day month=$ppo->month year=$ppo->year}">{i18n key="cahier.message.add_classroom_work"}</a></span>

{foreach from=$ppo->works item=work}
<div class="domain">
  <h3>{$work->nom}</h3>
  {$work->description}
</div>
{/foreach}