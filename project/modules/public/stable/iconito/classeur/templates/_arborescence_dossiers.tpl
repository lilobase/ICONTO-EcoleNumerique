{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder {if !isset($ppo->dossiersOuverts[$dossierId])}collapsed{else}open{/if}">
    <p class="{if $dossier->id eq $ppo->dossierCourant}current{/if}">
    <a href="#" class="expand {$dossier->id}"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
    <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$dossier->id}">{$dossier->nom}</a>
    </p>
    <ul class="child {if !isset($ppo->dossiersOuverts[$dossierId])}closed{/if}">
      {copixzone process=classeur|arborescenceDossiers classeurId=$ppo->classeurId dossierId=$dossier->id dossierCourant=$ppo->dossierCourant}
    </ul>
  </li>
{/foreach}