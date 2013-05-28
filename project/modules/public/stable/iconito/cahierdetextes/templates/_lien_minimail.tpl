{has_classroom_access module="MOD_MINIMAIL"}
{if $ppo->logins neq null && $access}
  <a class="button button-update" href="{copixurl dest="minimail||getNewForm" login=$ppo->logins}">{i18n key="cahierdetextes.message.writeToTeacher"}</a>
{/if}