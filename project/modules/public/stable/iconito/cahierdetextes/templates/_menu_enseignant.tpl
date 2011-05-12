<ul class="teacher-bar top-bar">
  <li><a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee a_faire=1}">{i18n key="cahierdetextes.message.todoWork"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.classroomWork"}</a></li>
  <li>{i18n key="cahierdetextes.message.day"}</li>
  <li>{i18n key="cahierdetextes.message.list"}</li>
  <li>{i18n key="cahierdetextes.message.domains"}</li>
  <li><a href="{copixurl dest="cahierdetextes||gererDomaines" nid=$ppo->nid}">{i18n key="cahierdetextes.message.domainsList"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.memos"}</a></li>
</ul>