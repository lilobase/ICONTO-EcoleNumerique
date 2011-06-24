<ul>
  {foreach from=$ppo->classeurs item=classeur}
    <li class="classeur {if $ppo->classeurId eq $classeur->id}current-classeur{else}collapsed{/if}">
      <a href="#" class="expand"><span>+</span></a>
      <input type="radio" id="classeur-{$classeur->id}" name="destination" value="classeur-{$classeur->id}" {if $ppo->targetType eq "classeur" && $ppo->targetId eq $classeur->id}checked{/if} />
      <label for="classeur-{$classeur->id}">
        {if $ppo->classeurPersonnel eq $classeur->id}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </label>
      <ul class="child" style="{if $ppo->classeurId ne $classeur->id}display:none{/if}">
        {copixzone process=classeur|selectionDossiers classeurId=$classeur->id targetType=$ppo->targetType targetId=$ppo->targetId}
      </ul>
    </li>
  {/foreach}
</ul>