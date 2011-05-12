{if $ppo->typeUtilisateur == 'USER_ENS'}
  {copixzone process=cahierdetextes|menuenseignant nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
{elseif $ppo->typeUtilisateur == 'USER_ELE'}
  {copixzone process=cahierdetextes|menueleve nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
{elseif $ppo->typeUtilisateur == 'USER_RES'}
  {copixzone process=cahierdetextes|menuparent nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}
{/if}