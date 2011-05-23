{if $ppo->domaines|@count neq 0}
  <table class="classic">
    <thead>
      <tr>
        <th>{i18n key="cahierdetextes.message.domains"}</th>
        <th>{i18n key="cahierdetextes.message.actions"}</th>
      </tr>
    </thead>
    <tbody>
    {foreach from=$ppo->domaines item=domaine}
      <tr>
    	  <td>{$domaine}</td>
        <td>
          {if $ppo->eleve neq null}
          <a href="{copixurl dest="cahierdetextes||gererDomaines" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id eleve=$ppo->eleve}">
          {else}
          <a href="{copixurl dest="cahierdetextes||gererDomaines" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id}">
          {/if}
            <img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" />
          </a>
          {if $ppo->eleve neq null}
          <a href="{copixurl dest="cahierdetextes||supprimerDomaine" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id eleve=$ppo->eleve}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteDomainConfirm"}')">
          {else}
          <a href="{copixurl dest="cahierdetextes||supprimerDomaine" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteDomainConfirm"}')">
          {/if}
            <img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" />
          </a>
        </td>
      </tr>
    {/foreach}
  	</tbody>
  </table>
{/if}