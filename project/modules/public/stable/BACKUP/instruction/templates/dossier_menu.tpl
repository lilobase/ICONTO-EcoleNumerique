
<div id="dossier_menu">

<ul>

<li{if $ppo->tab eq 'demande'} id="current"{/if}><a title="Demande" href="{copixurl dest="instruction|dossiers|dossier_demande" id=$ppo->rDemande->id}">Demande</a></li>
<li{if $ppo->tab eq 'enfant'} id="current"{/if}><a title="Enfant" href="{copixurl dest="instruction|dossiers|dossier_enfant" id=$ppo->rDemande->id}">Enfant</a></li>
<li{if $ppo->tab eq 'responsables'} id="current"{/if}><a title="Responsables" href="{copixurl dest="instruction|dossiers|dossier_responsables" id=$ppo->rDemande->id}">Responsables</a></li>
<li{if $ppo->tab eq 'contrats'} id="current"{/if}><a title="Contrats" href="{copixurl dest="instruction|dossiers|dossier_contrats" id=$ppo->rDemande->id}">Contrats</a></li>
<li{if $ppo->tab eq 'factures'} id="current"{/if}><a title="Factures" href="{copixurl dest="instruction|dossiers|dossier_factures" id=$ppo->rDemande->id}">Factures</a></li>


</ul>

</div>
