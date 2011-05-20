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
      <td><a href="{copixurl dest="cahierdetextes||gererDomaines" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a> <a href="{copixurl dest="cahierdetextes||supprimerDomaine" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee domaineId=$domaine->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteDomainConfirm"}')"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a></td>
    </tr>
  {/foreach}
	</tbody>
</table>