<ul class="student-bar top-bar">
  <li class="prev-week"><a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->prevWeek|date_format:"%d" month=$ppo->prevWeek|date_format:"%m" year=$ppo->prevWeek|date_format:"%Y"}"><</a></li>
  
  <li class="{if $ppo->mon eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->mon|date_format:"%d" month=$ppo->mon|date_format:"%m" year=$ppo->mon|date_format:"%Y"}">{i18n key="cahier.message.monday"} {$ppo->mon|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->tue eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->tue|date_format:"%d" month=$ppo->tue|date_format:"%m" year=$ppo->tue|date_format:"%Y"}">{i18n key="cahier.message.tuesday"} {$ppo->tue|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->wed eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->wed|date_format:"%d" month=$ppo->wed|date_format:"%m" year=$ppo->wed|date_format:"%Y"}">{i18n key="cahier.message.wednesday"} {$ppo->wed|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->thu eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->thu|date_format:"%d" month=$ppo->thu|date_format:"%m" year=$ppo->thu|date_format:"%Y"}">{i18n key="cahier.message.thursday"} {$ppo->thu|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->fri eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->fri|date_format:"%d" month=$ppo->fri|date_format:"%m" year=$ppo->fri|date_format:"%Y"}">{i18n key="cahier.message.friday"} {$ppo->fri|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->sat eq $ppo->selectedDate}selected{/if}">
    <a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->sat|date_format:"%d" month=$ppo->sat|date_format:"%m" year=$ppo->sat|date_format:"%Y"}">{i18n key="cahier.message.saturday"} {$ppo->sat|date_format:"%d %B"}</a>
  </li>
  
  <li class="next-week"><a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid day=$ppo->nextWeek|date_format:"%d" month=$ppo->nextWeek|date_format:"%m" year=$ppo->nextWeek|date_format:"%Y"}">></a></li>
</ul>