<div id="submenu">
  <div class="menuitems">
    <ul>
      <li><a class="todo{if $ppo->current == "editerTravailAFaire"} current{/if}" href="{copixurl dest="cahierdetextes||editerTravail" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee a_faire=1 vue=$ppo->vue}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.todoWork"}</span></a></li>
      <li><a class="classroom{if $ppo->current == "editerTravailEnClasse"} current{/if}" href="{copixurl dest="cahierdetextes||editerTravail" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee vue=$ppo->vue}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.classroomWork"}</span></a></li>
      <li class="newGroupItems"><a class="event{if $ppo->current == "voirTravaux"} current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.day"}</span></a></li>
      <li><a class="page{if $ppo->current == "voirListeTravaux"} current{/if}" href="{copixurl dest="cahierdetextes||voirListeTravaux" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.list"}</span></a></li>
      <li><a class="list-active{if $ppo->current == "voirTravauxParDomaine"} current{/if}" href="{copixurl dest="cahierdetextes||voirTravauxParDomaine" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.domains"}</span></a></li>
      <li class="newGroupItems"><a class="list-inactive{if $ppo->current == "gererDomaines"} current{/if}" href="{copixurl dest="cahierdetextes||gererDomaines" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.domainsList"}</span></a></li>
      <li><a class="article{if $ppo->current == "voirMemos"} current{/if}" href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.memos"}</span></a></li>
    </ul>
  </div>
</div>