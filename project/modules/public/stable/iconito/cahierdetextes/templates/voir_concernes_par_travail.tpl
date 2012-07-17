<div id="students-data">      
  {if $ppo->eleves neq null}
    <table class="liste">
      <thead>
        <tr>
          <th class="liste_th">{i18n key="cahierdetextes.message.account"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.name"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.firstname"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.level"}</th>
          {if $ppo->travail->a_rendre}<th class="liste_th">{i18n key="cahierdetextes.message.return"}</th>{/if}
        </tr>
      </thead>
      <tbody>
        {foreach from=$ppo->eleves item=eleve}
          <tr class="{$eleve->niveau_court}">
            <td>{$eleve->login}</td>
            <td>{$eleve->nom}</td>
            <td>{$eleve->prenom1}</td>
            <td>{$eleve->niveau_court}</td>
            {if $ppo->travail->a_rendre}<td>{if $eleve->rendu_le}{$eleve->rendu_le|datei18n:"date_short_time"}{/if}</td>{/if}
          </tr>
        {/foreach}
      </tbody>
    </table>
  {/if} 
</div>