<ul>
  {foreach from=$ppo->classeurs item=classeur}
    {assign var=classeurId value=$classeur->id}
    <li class="classeur {if !isset($ppo->classeursOuverts[$classeurId])}collapsed{else}open{/if}">
      <p class="{if $ppo->classeurId eq $classeur->id}current{/if}">
      <a href="#" class="expand"><img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" /></a>
      <a href="{copixurl dest="classeur||voirContenu" classeurId=$classeur->id}">
        {if $classeur->id eq $ppo->classeurPersonnel}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </a></p>
      <ul class="child {if $ppo->classeurId ne $classeur->id}closed{/if}">
        {copixzone process=classeur|arborescenceDossiers classeurId=$classeur->id dossierCourant=$ppo->dossierCourant}
      </ul>
    </li>
  {/foreach}
</ul>