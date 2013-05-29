{iconitominimail_hasuseraccess assign=has_user_access}
{if $ppo->logins neq null && $has_user_access}
  <a class="button button-update" href="{copixurl dest="minimail||getNewForm" login=$ppo->logins}">{i18n key="cahierdetextes.message.writeToTeacher"}</a>
{/if}
