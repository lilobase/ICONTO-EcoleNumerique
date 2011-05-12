<ul class="domains-list">
{foreach from=$ppo->domaines item=domaine}
  <li>{$domaine} ({i18n key="cahierdetextes.message.modify"} - <a href="{copixurl dest="cahierdetextes||supprimerDomaine" nid=$ppo->nid domain_id=$domaine->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteDomainConfirm"}')">{i18n key="cahierdetextes.message.delete"}</a>)</li>
{/foreach}
</ul>