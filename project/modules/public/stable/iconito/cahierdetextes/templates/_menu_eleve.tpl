<div id="submenu">
<div class="menuitems studentMenu">
<ul>
  <li class="prev-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->semainePrecedente|date_format:"%d" mois=$ppo->semainePrecedente|date_format:"%m" annee=$ppo->semainePrecedente|date_format:"%Y"}"><span class="valign"></span><span><img src="{copixurl}themes/default/images/button-action/action_back.png" alt="<" /></span></a></li>
  
  <li>
    <a class="monday {if $ppo->lun eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->lun|substr:6:2 mois=$ppo->lun|substr:4:2 annee=$ppo->lun|substr:0:4}"><span class="valign"></span><span>{$ppo->lun|datei18n:text|substr:0:-4}</span></a>
  </li>
  <li>
    <a class="tuesday {if $ppo->mar eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->mar|substr:6:2 mois=$ppo->mar|substr:4:2 annee=$ppo->mar|substr:0:4}"><span class="valign"></span><span>{$ppo->mar|datei18n:text|substr:0:-4}</span></a>
  </li>
  <li>
    <a class="wednesday {if $ppo->mer eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->mer|substr:6:2 mois=$ppo->mer|substr:4:2 annee=$ppo->mer|substr:0:4}"><span class="valign"></span><span>{$ppo->mer|datei18n:text|substr:0:-4}</span></a>
  </li>
  <li>
    <a class="thursday {if $ppo->jeu eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->jeu|substr:6:2 mois=$ppo->jeu|substr:4:2 annee=$ppo->jeu|substr:0:4}"><span class="valign"></span><span>{$ppo->jeu|datei18n:text|substr:0:-4}</span></a>
  </li>
  <li>
    <a class="friday {if $ppo->ven eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->ven|substr:6:2 mois=$ppo->ven|substr:4:2 annee=$ppo->ven|substr:0:4}"><span class="valign"></span><span>{$ppo->ven|datei18n:text|substr:0:-4}</span></a>
  </li>
  <li>
    <a class="saturday {if $ppo->sam eq $ppo->dateSelectionnee}current{/if}" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->sam|substr:6:2 mois=$ppo->sam|substr:4:2 annee=$ppo->sam|substr:0:4}"><span class="valign"></span><span>{$ppo->sam|datei18n:text|substr:0:-4}</span></a>
  </li>
  
  <li class="next-week"><a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$ppo->semaineSuivante|date_format:"%d" mois=$ppo->semaineSuivante|date_format:"%m" annee=$ppo->semaineSuivante|date_format:"%Y"}"><span class="valign"></span><span><img src="{copixurl}themes/default/images/button-action/action_next.png" alt=">" /></span></a></li>
</ul>
</div>
</div>