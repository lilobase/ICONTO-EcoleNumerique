<h2>{i18n key="classeur.message.copyFiles"}</h2>

<form name="move_files" id="move_files" action="{copixurl dest="classeur||copierContenu"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="dossierIds" id="dossierIds" value="{$ppo->dossierIds}">
  <input type="hidden" name="fichierIds" id="fichierIds" value="{$ppo->fichierIds}">
  
  <div class="field">
    <label for="fichiers" class="form_libelle">{i18n key="classeur.message.selectedFiles"} :</label>
    <input id="fichiers" name="fichiers[]" type="input" value="{$ppo->nomsContenus}" readonly />
  </div>
  
  <div class="field">
    <label for="fichier_titre" class="form_libelle">{i18n key="classeur.message.destinationLocation"} :</label>
    {copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId dossierCourant=$ppo->dossierId}
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>