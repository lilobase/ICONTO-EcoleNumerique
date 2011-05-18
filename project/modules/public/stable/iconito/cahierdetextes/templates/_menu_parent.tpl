<ul class="parent-bar top-bar">
  <li><a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.day"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirListeTravaux" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.list"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirTravauxParDomaine" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.domains"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="memos-count">{$ppo->nombreMemos}</span> {i18n key="cahierdetextes.message.memos"}</a></li>
</ul>