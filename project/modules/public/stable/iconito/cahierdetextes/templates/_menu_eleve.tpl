<div id="submenu">
<div class="menuitems studentMenu">
<ul>
  <li class="prev-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->semainePrecedente|date_format:"%d" mois=$ppo->semainePrecedente|date_format:"%m" annee=$ppo->semainePrecedente|date_format:"%Y"}"><span class="valign"></span><span><img src="{copixurl}themes/default/images/action_back.png" alt="<" /></span></a></li>
  
  <li>
    <a class="{if $ppo->lun eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->lun|date_format:"%d" mois=$ppo->lun|date_format:"%m" annee=$ppo->lun|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.monday"} {$ppo->lun|date_format:"%d %B"}</span></a>
  </li>
  <li>
    <a class="{if $ppo->mar eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->mar|date_format:"%d" mois=$ppo->mar|date_format:"%m" annee=$ppo->mar|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.tuesday"} {$ppo->mar|date_format:"%d %B"}</span></a>
  </li>
  <li>
    <a class="{if $ppo->mer eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->mer|date_format:"%d" mois=$ppo->mer|date_format:"%m" annee=$ppo->mer|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.wednesday"} {$ppo->mer|date_format:"%d %B"}</span></a>
  </li>
  <li>
    <a class="{if $ppo->jeu eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->jeu|date_format:"%d" mois=$ppo->jeu|date_format:"%m" annee=$ppo->jeu|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.thursday"} {$ppo->jeu|date_format:"%d %B"}</span></a>
  </li>
  <li>
    <a class="{if $ppo->ven eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->ven|date_format:"%d" mois=$ppo->ven|date_format:"%m" annee=$ppo->ven|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.friday"} {$ppo->ven|date_format:"%d %B"}</span></a>
  </li>
  <li>
    <a class="{if $ppo->sam eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->sam|date_format:"%d" mois=$ppo->sam|date_format:"%m" annee=$ppo->sam|date_format:"%Y"}"><span class="valign"></span><span>{i18n key="cahierdetextes.message.saturday"} {$ppo->sam|date_format:"%d %B"}</span></a>
  </li>
  
  <li class="next-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->semaineSuivante|date_format:"%d" mois=$ppo->semaineSuivante|date_format:"%m" annee=$ppo->semaineSuivante|date_format:"%Y"}"><span class="valign"></span><span><img src="{copixurl}themes/default/images/action_next.png" alt=">" /></span></a></li>
</ul>
</div>
</div>