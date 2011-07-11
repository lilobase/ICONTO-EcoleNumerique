<ul>
  {foreach from=$ppo->classeurs item=classeur}
    {assign var=classeurId value=$classeur->id}
    <li class="classeur {if !isset($ppo->classeursOuverts[$classeurId])}collapsed{else}open{/if}">
      <p class="{if ($ppo->classeurId eq $classeur->id) && ($ppo->dossierCourant eq 0 || $ppo->dossierCourant eq null)}current{/if}">
      {if $classeur->hasDossiers()}
        <a href="#" class="expand-classeur {$classeur->id}">
          {if !isset($ppo->classeursOuverts[$classeurId])}
            <img src="{copixurl}themes/default/images/sort_right_off.png" alt="+" />
          {else}
            <img src="{copixurl}themes/default/images/sort_down_off.png" alt="-" />
          {/if}
        </a>
      {else}
        <img src="{copixurl}themes/default/images/sort_right_inactive.png" alt=">" />
      {/if}
      {if $ppo->field neq null && $ppo->format neq null}
      <a href="{copixurl dest="classeur||getClasseurPopup" classeurId=$classeur->id field=$ppo->field format=$ppo->format}">
      {else}
      <a href="{copixurl dest="classeur||voirContenu" classeurId=$classeur->id}">
      {/if}
        {if $classeur->id eq $ppo->classeurPersonnel}
          {i18n key="classeur.message.personnalFolder"}
        {else}
          {$classeur->titre}
        {/if}
      </a></p>
      <ul class="child {if !isset($ppo->classeursOuverts[$classeurId])}closed{/if}">
        {copixzone process=classeur|arborescenceDossiers classeurId=$classeur->id dossierCourant=$ppo->dossierCourant field=$ppo->field format=$ppo->format}
      </ul>
    </li>
  {/foreach}
</ul>