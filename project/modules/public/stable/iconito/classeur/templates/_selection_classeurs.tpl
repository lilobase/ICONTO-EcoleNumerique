<ul>
  {foreach from=$ppo->classeurs item=classeur}
    <li class="classeur {if $ppo->classeurId eq $classeur->id}current{else}collapsed{/if}">
      <p class="{if $ppo->classeurId eq $classeur->id}current{/if}">
      <a href="#" class="expand"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
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
        {copixzone process=classeur|selectionDossiers classeurId=$classeur->id targetType=$ppo->targetType targetId=$ppo->targetId}
      </ul>
    </li>
  {/foreach}
</ul>