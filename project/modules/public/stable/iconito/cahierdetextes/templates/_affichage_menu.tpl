{if $ppo->estAdmin}
  {copixzone process=cahierdetextes|menuenseignant cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
{elseif $ppo->eleve neq null}
  {copixzone process=cahierdetextes|menuparent cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve}
{else}
  {copixzone process=cahierdetextes|menueleve cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
{/if}