{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder {if $dossier->id eq $ppo->dossierCourant}current-folder{/if}{if !isset($ppo->dossiersOuverts[$dossierId])}collapsed{/if}">
    <a href="#" class="expand {$dossier->id}"><span>+</span></a>
    <a href="{copixurl dest="classeur||voirContenu" classeurId=$ppo->classeurId dossierId=$dossier->id}">{$dossier->nom}</a>
    <ul class="child" style="{if !isset($ppo->dossiersOuverts[$dossierId])}display: none;{/if}">
      {copixzone process=classeur|arborescenceDossiers classeurId=$ppo->classeurId dossierId=$dossier->id dossierCourant=$ppo->dossierCourant}
    </ul>
  </li>
{/foreach}