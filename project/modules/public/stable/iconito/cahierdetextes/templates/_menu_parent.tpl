<ul class="parent-bar top-bar">
  <li><a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">{i18n key="cahierdetextes.message.day"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirListeTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">{i18n key="cahierdetextes.message.list"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirTravauxParDomaine" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">{i18n key="cahierdetextes.message.domains"}</a></li>
  <li><a href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}"><span class="memos-count">{$ppo->nombreMemos}</span> {i18n key="cahierdetextes.message.memos"}</a></li>
</ul>