{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder {if $dossier->id eq $ppo->dossierCourant}current{/if}">
    <a href="#" class="expand {$dossier->id}"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
    <input type="radio" id="dossier-{$dossier->id}" name="destination" value="dossier-{$dossier->id}" {if $ppo->targetType eq "dossier" && $ppo->targetId eq $dossier->id}checked{/if}/>
    <label for="dossier-{$dossier->id}">{$dossier->nom}</label>
    <ul class="child closed>
      {copixzone process=classeur|selectionDossiers classeurId=$ppo->classeurId dossierId=$dossier->id targetType=$ppo->targetType targetId=$ppo->targetId}
    </ul>
  </li>
{/foreach}