{if $ppo->userType == 'USER_ENS'}
  {copixzone process=cahier|teacherbar nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
{elseif $ppo->userType == 'USER_ELE'}
  {copixzone process=cahier|studentbar nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
{elseif $ppo->userType == 'USER_RES'}
  {copixzone process=cahier|parentbar nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
{/if}

<h4>{$ppo->title}</h4>

<div class="works">
  {copixzone process=cahier|todoWorks nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
  {copixzone process=cahier|classroomWorks nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
</div>

<div class="sidebar">
  <span class="today-button"><a href="{copixurl dest="cahier||showWorks" nid=$ppo->nid}">{i18n key="cahier.message.today"}</a></span>

  {copixzone process=cahier|calendar nid=$ppo->nid date_day=$ppo->day date_month=$ppo->month date_year=$ppo->year}
</div>
