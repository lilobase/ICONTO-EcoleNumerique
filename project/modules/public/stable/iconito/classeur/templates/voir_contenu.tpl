<div id="submenu">
  <a class="fancybox" href="{copixurl dest="classeur||editerDossier" classeurId=$ppo->classeurId parentId=$ppo->dossierId}">{i18n key="classeur.message.newFolder"}</a> - 
  {i18n key="classeur.message.addFavorite"} - 
  <a href="{copixurl dest="classeur||editerFichiers" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">{i18n key="classeur.message.addFiles"}</a>
</div>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{/if}

<div id="sidebar">
  {copixzone process=classeur|arborescenceClasseurs classeurId=$ppo->classeurId dossierCourant=$ppo->dossierId}
</div>

<div class="content-view">
  {if $ppo->vue eq 'liste'}
    {copixzone process=classeur|vueListe classeurId=$ppo->classeurId dossierId=$ppo->dossierId}
  {/if}
  
  <ul class="mass-actions">
    <li><a href="{copixurl dest="classeur||supprimerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="delete-content">{i18n key="classeur.message.delete"}</a></li>
    <li><a href="{copixurl dest="classeur||deplacerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="move-content">{i18n key="classeur.message.move"}</a></li>
    <li><a href="{copixurl dest="classeur||copierContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="copy-content">{i18n key="classeur.message.copy"}</a></li> 
    <li><a href="{copixurl dest="classeur||telechargerContenu" classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="download-content">{i18n key="classeur.message.download"}</a></li>
  </ul>
</div>

<script type="text/javascript">
  var classeurId = {$ppo->classeurId};
  var dossierId  = {$ppo->dossierId};
</script>