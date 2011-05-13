<ul class="student-bar top-bar">
  <li class="prev-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->semainePrecedente|date_format:"%d" mois=$ppo->semainePrecedente|date_format:"%m" annee=$ppo->semainePrecedente|date_format:"%Y"}"><</a></li>
  
  <li class="{if $ppo->lun eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->lun|date_format:"%d" mois=$ppo->lun|date_format:"%m" annee=$ppo->lun|date_format:"%Y"}">{i18n key="cahierdetextes.message.monday"} {$ppo->lun|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->mar eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->mar|date_format:"%d" mois=$ppo->mar|date_format:"%m" annee=$ppo->mar|date_format:"%Y"}">{i18n key="cahierdetextes.message.tuesday"} {$ppo->mar|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->mer eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->mer|date_format:"%d" mois=$ppo->mer|date_format:"%m" annee=$ppo->mer|date_format:"%Y"}">{i18n key="cahierdetextes.message.wednesday"} {$ppo->mer|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->jeu eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->jeu|date_format:"%d" mois=$ppo->jeu|date_format:"%m" annee=$ppo->jeu|date_format:"%Y"}">{i18n key="cahierdetextes.message.thursday"} {$ppo->jeu|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->ven eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->ven|date_format:"%d" mois=$ppo->ven|date_format:"%m" annee=$ppo->ven|date_format:"%Y"}">{i18n key="cahierdetextes.message.friday"} {$ppo->ven|date_format:"%d %B"}</a>
  </li>
  <li class="{if $ppo->sam eq $ppo->dateSelectionnee}selected{/if}">
    <a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->sam|date_format:"%d" mois=$ppo->sam|date_format:"%m" annee=$ppo->sam|date_format:"%Y"}">{i18n key="cahierdetextes.message.saturday"} {$ppo->sam|date_format:"%d %B"}</a>
  </li>
  
  <li class="next-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$ppo->semaineSuivante|date_format:"%d" mois=$ppo->semaineSuivante|date_format:"%m" annee=$ppo->semaineSuivante|date_format:"%Y"}">></a></li>
  
  <li><a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.memos"}</a></li>
</ul>