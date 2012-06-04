<ul>
  {foreach from=$ppo->classeurs item=classeur}
    <li class="classeur {if $ppo->classeurId neq $classeur->id}collapsed{/if}">
      <p class="{if ($ppo->classeurId eq $classeur->id) && ($ppo->dossierCourant eq 0 || $ppo->dossierCourant eq null)}current{/if}">
      {if $classeur->hasDossiers(false)}
        <a href="#" class="expand-classeur {$classeur->id}"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
      {else}
        <img src="{copixurl}themes/default/images/sort_right_inactive.png" alt=">" />
      {/if}
      <input type="radio" id="classeur-{$classeur->id}" name="destination" value="classeur-{$classeur->id}" {if $ppo->targetType eq "classeur" && $ppo->targetId eq $classeur->id}checked="checked"{/if} />
      <label for="classeur-{$classeur->id}">
        {if $ppo->classeurPersonnel eq $classeur->id}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </label>
      </p>
      <ul class="child {if $ppo->classeurId ne $classeur->id}closed{/if}">
        {copixzone process=classeur|selectionDossiers classeurId=$classeur->id targetType=$ppo->targetType targetId=$ppo->targetId withLocker=$ppo->withLocker}
      </ul>
    </li>
  {/foreach}
</ul>