<table class="domains-list">
	<thead>
    	<tr>
        	<th>{i18n key="cahierdetextes.message.domains"}</th>
            <th>{i18n key="cahierdetextes.message.domains"}</th>
        </tr>
     </thead>
     <tbody>
{foreach from=$ppo->domaines item=domaine}
  <tr>
  	<td>{$domaine}</td>
    <td><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /> <a href="{copixurl dest="cahierdetextes||supprimerDomaine" nid=$ppo->nid domain_id=$domaine->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteDomainConfirm"}')"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a></td>
  </tr>
{/foreach}
	</tbody>
</table>