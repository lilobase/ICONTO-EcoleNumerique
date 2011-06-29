{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}

{if $ppo->confirmMessage}
  <p class="mesgSuccess">{$ppo->confirmMessage}</p>
{/if}

<div id="sidebar">
  {copixzone process=classeur|arborescenceClasseurs classeurId=$ppo->classeurId dossierCourant=$ppo->dossierId}
</div>

<div class="content-view">
  {if $ppo->vue eq 'liste'}
    {copixzone process=classeur|vueListe classeurId=$ppo->classeurId dossierId=$ppo->dossierId}
  {/if}
  
  <ul class="mass-actions">
    {if $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH || $ppo->classeurId eq $ppo->idClasseurPersonnel}
      <li><a href="{copixurl dest="classeur||supprimerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="delete-content">{i18n key="classeur.message.delete"}</a></li>
      <li><a href="{copixurl dest="classeur||deplacerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="move-content">{i18n key="classeur.message.move"}</a></li>
    {/if}
    <li><a href="{copixurl dest="classeur||copierContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="copy-content">{i18n key="classeur.message.copy"}</a></li> 
    <li><a href="{copixurl dest="classeur||telechargerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="download-content">{i18n key="classeur.message.download"}</a></li>
  </ul>
</div>

<script type="text/javascript">
  var classeurId = {$ppo->classeurId};
  var dossierId  = {$ppo->dossierId};
</script>