<div class="next-works">
  <h3>{i18n key="cahierdetextes.message.nextWorks"}</h3>
  {if $ppo->travaux neq null}
    <ul>
    {foreach from=$ppo->travaux key=date item=travailParDate}
      <li>
        <a class="actionLink" href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid jour=$date|date_format:"%d" mois=$date|date_format:"%m" annee=$date|date_format:"%Y"}">
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