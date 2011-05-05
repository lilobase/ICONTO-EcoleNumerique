<ul class="teacher-bar top-bar">
  <li><a href="{copixurl dest="cahier||addWork" nid=$ppo->nid day=$ppo->day month=$ppo->month year=$ppo->year todo=1}">{i18n key="cahier.message.todo_work"}</a></li>
  <li><a href="{copixurl dest="cahier||addWork" nid=$ppo->nid day=$ppo->day month=$ppo->month year=$ppo->year}">{i18n key="cahier.message.classroom_work"}</a></li>
  <li>{i18n key="cahier.message.day"}</li>
  <li>{i18n key="cahier.message.list"}</li>
  <li>{i18n key="cahier.message.domains"}</li>
  <li><a href="{copixurl dest="cahier||manageDomains" nid=$ppo->nid}">{i18n key="cahier.message.domains_list"}</a></li>
  <li>{i18n key="cahier.message.memos"}</li>
</ul>