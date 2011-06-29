{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierParent->id}

<h2>{i18n key="classeur.message.moveFolder"}</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form name="move_folder" id="move_folder" action="{copixurl dest="classeur||deplacerDossier"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="parentId" id="parentId" value="{$ppo->dossierParent->id}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossier->id}" />
  
  <div class="field">
    <label for="dossier_id" class="form_libelle">{i18n key="classeur.message.selectedFolder"} :</label>
    <input id="dossier_id" name="dossier_id" type="input" value="{$ppo->dossier->nom}" readonly />
  </div>
  
  <div class="field">
    <label>{i18n key="classeur.message.currentFolder"} :</label>
    {$ppo->dossier->getPath()}
  </div>
  
  <div class="field">
    <label for="fichier_titre" class="form_libelle">{i18n key="classeur.message.destinationLocation"} :</label>
    {copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId targetType=$ppo->destinationType targetId=$ppo->destinationId}
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierParent->id}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>