<div id="students-data">      
  {if $ppo->suivis neq null}
    <table class="liste">
      <thead>
        <tr>
          <th class="liste_th">{i18n key="cahierdetextes.message.account"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.name"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.firstname"}</th>
          <th class="liste_th">{i18n key="cahierdetextes.message.level"}</th>
          {if $ppo->memo->avec_signature}
            <th class="liste_th">{i18n key="cahierdetextes.message.sign"}</th>
            <th class="liste_th">{i18n key="cahierdetextes.message.comments"}</th>
          {/if}
        </tr>
      </thead>
      <tbody>
        {foreach from=$ppo->suivis item=suivi}
          <tr class="{$eleve->niveau_court}">
            <td>{$suivi->login}</td>
            <td>{$suivi->nom}</td>
            <td>{$suivi->prenom1}</td>
            <td>{$suivi->niveau_court}</td>
            {if $ppo->memo->avec_signature}
              <td><input type="checkbox" {if $suivi->signe_le}checked="checked"{/if} disabled /></td>
              <td>{$suivi->commentaire}</td>
            {/if}
          </tr>
        {/foreach}
      </tbody>
    </table>
  {else}
    {i18n key="cahierdetextes.message.noStudent"}
  {/if} 
</div>