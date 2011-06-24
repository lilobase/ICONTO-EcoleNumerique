<ul>
  {foreach from=$ppo->classeurs item=classeur}
    <li class="classeur {if $ppo->classeurId eq $classeur->id}current-classeur{else}collapsed{/if}">
      <a href="#" class="expand"><span>+</span></a>
      <a href="{copixurl dest="classeur||voirContenu" classeurId=$classeur->id}">
        {if $classeur->id eq $ppo->classeurPersonnel}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </a>
      <ul class="child" style="{if $ppo->classeurId ne $classeur->id}display:none{/if}">
        {copixzone process=classeur|arborescenceDossiers classeurId=$classeur->id dossierCourant=$ppo->dossierCourant}
      </ul>
    </li>
  {/foreach}
</ul>