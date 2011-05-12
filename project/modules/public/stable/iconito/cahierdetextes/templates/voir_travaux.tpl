{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h4>{$ppo->titre}</h4>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{/if}

<div class="works">
  {copixzone process=cahierdetextes|travauxAFaire nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
  {copixzone process=cahierdetextes|travauxEnClasse nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
</div>

<div class="sidebar">
  <span class="today-button"><a href="{copixurl dest="cahierdetextes||voirTravaux" nid=$ppo->nid}">{i18n key="cahierdetextes.message.today"}</a></span>

  {copixzone process=cahierdetextes|calendrier nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
  
  {copixzone process=cahierdetextes|memos nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
</div>