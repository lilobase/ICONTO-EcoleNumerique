{foreach from=$ppo->dossiers item=dossier}
  {assign var=dossierId value=$dossier->id}
  <li class="folder">
    <p class="{if $dossier->id eq $ppo->targetId}current{/if}">
      {if $dossier->hasSousDossiers(!$dossier->casier || ($dossier->casier && $ppo->estAdmin))}
        <a href="#" class="expand-folder {$dossier->id}"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
      {else}
        <img src="{copixurl}themes/default/images/sort_right_inactive.png" alt=">" />
      {/if}
      <input type="radio" id="dossier-{$dossier->id}" name="destination" value="dossier-{$dossier->id}" {if $ppo->targetType eq "dossier" && $ppo->targetId eq $dossier->id}checked="checked"{/if} />
      <label for="dossier-{$dossier->id}">{$dossier->nom|escape}</label>
    </p>
    <ul class="child{if $ppo->alwaysOpen} open{else} closed{/if}">
      {copixzone process=classeur|selectionDossiers classeurId=$ppo->classeurId dossierId=$dossier->id targetType=$ppo->targetType targetId=$ppo->targetId alwaysOpen=$ppo->alwaysOpen withLocker=$ppo->withLocker}
    </ul>
  </li>
{/foreach}
