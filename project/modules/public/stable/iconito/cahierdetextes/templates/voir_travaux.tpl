{copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve current=voirTravaux vue=jour}
<div id="dayView">
    <h2>{$ppo->dateSelectionnee|datei18n:text}</h2>
    
    {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
      {copixzone process=cahierdetextes|lienMinimail cahierId=$ppo->cahierId}
    {/if}
    
    {if $ppo->msgSuccess}
      <p class="mesgSuccess">{$ppo->msgSuccess}</p>
    {/if}
    
    <div class="works">
      {copixzone process=cahierdetextes|travauxAFaire cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee vue=jour eleve=$ppo->eleve}
      {copixzone process=cahierdetextes|travauxEnClasse cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee vue=jour eleve=$ppo->eleve}
    </div>
</div>    

<div class="sidebar">
    <p class="today-button">
      {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
        <a class="button" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId eleve=$ppo->eleve}">{i18n key="cahierdetextes.message.today"}</a>
      {else}
        <a class="button" href="{copixurl dest="cahierdetextes||voirTravaux" cahierId=$ppo->cahierId}">{i18n key="cahierdetextes.message.today"}</a>
      {/if}
    </p>
    
    {copixzone process=cahierdetextes|calendrier cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve}
    {copixzone process=cahierdetextes|travauxAVenir cahierId=$ppo->cahierId eleve=$ppo->eleve}
    {copixzone process=cahierdetextes|memos cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve}
</div>
