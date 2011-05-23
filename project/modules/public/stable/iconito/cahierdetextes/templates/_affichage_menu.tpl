{if $ppo->estAdmin}
  {copixzone process=cahierdetextes|menuenseignant cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee current=$ppo->current}
{elseif $ppo->eleve neq null}
  {copixzone process=cahierdetextes|menuparent cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve current=$ppo->current}
{else}
  {copixzone process=cahierdetextes|menueleve cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee current=$ppo->current}
{/if}