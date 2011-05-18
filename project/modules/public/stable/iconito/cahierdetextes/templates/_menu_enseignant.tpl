<div id="submenu">
<div class="menuitems">
    <ul>
      <li><a class="current" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee a_faire=1}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.todoWork"}</span></a></li>
      <li><a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.classroomWork"}</span></a></li>
      <li class="newGroupItems"><span class="valign"></span><span>{i18n key="cahierdetextes.message.day"}</span></li>
      <li><span class="valign"></span><span>{i18n key="cahierdetextes.message.list"}</span></li>
      <li><span class="valign"></span><span>{i18n key="cahierdetextes.message.domains"}</span></li>
      <li class="newGroupItems"><a href="{copixurl dest="cahierdetextes||gererDomaines" nid=$ppo->nid}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.domainsList"}</span></a></li>
      <li><a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.memos"}</span></a></li>
    </ul>
</div>
</div>