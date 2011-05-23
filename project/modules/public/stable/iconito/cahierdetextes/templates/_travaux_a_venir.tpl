<div class="next-works">
  <h3>{i18n key="cahierdetextes.message.nextWorks"}</h3>
  {if $ppo->travaux neq null}
    <ul>
    {foreach from=$ppo->travaux key=date item=travailParDate}
      <li>
        {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
        <a class="dateToDo" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$date|substr:6:2 mois=$date|substr:4:2 annee=$date|substr:0:4 eleve=$ppo->eleve}">
        {else}
        <a class="dateToDo" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$date|substr:6:2 mois=$date|substr:4:2 annee=$date|substr:0:4}">
        {/if}
          {$date|datei18n}</a> 
          {foreach from=$travailParDate item=travail}
            <a href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$date|substr:6:2 mois=$date|substr:4:2 annee=$date|substr:0:4 eleve=$ppo->eleve}">{$travail->nom}</a>
          {/foreach}
      </li>
    {/foreach}
  </ul>
  {else}
    <p class="no-work">{i18n key="cahierdetextes.message.noWork"}</p>
  {/if}
</div>