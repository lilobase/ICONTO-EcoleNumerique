{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder {if $dossier->id eq $ppo->dossierCourant}current-folder{/if}">
    <a href="#" class="expand {$dossier->id}"><span>+</span></a>
    <input type="radio" id="dossier-{$dossier->id}" name="destination" value="dossier-{$dossier->id}" {if $ppo->targetType eq "dossier" && $ppo->targetId eq $dossier->id}checked{/if}/>
    <label for="dossier-{$dossier->id}">{$dossier->nom}</label>
    <ul class="child" style="display: none;">
      {copixzone process=classeur|selectionDossiers classeurId=$ppo->classeurId dossierId=$dossier->id targetType=$ppo->targetType targetId=$ppo->targetId}
    </ul>
  </li>
{/foreach}