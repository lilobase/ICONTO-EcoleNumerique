<div class="next-works">
  <h3>{i18n key="cahierdetextes.message.nextWorks"}</h3>
  {if $ppo->travaux neq null}
    <ul>
    {foreach from=$ppo->travaux key=date item=travailParDate}
      <li>
        <a class="actionLink" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId jour=$date|substr:6:2 mois=$date|substr:4:2 annee=$date|substr:0:4 eleve=$ppo->eleve}">
          {$date|datei18n} : 
          {foreach from=$travailParDate item=travail}
            {$travail->nom}
          {/foreach}
        </a>
      </li>
    {/foreach}
  </ul>
  {else}
    <p class="no-work">{i18n key="cahierdetextes.message.noWork"}</p>
  {/if}
</div>