{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierParent->id}

<h2>{i18n key="classeur.message.moveFiles"}</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form id="move_files" action="{copixurl dest="classeur||deplacerContenu"}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierParent->id}" />
  <input type="hidden" name="dossiers" id="dossiers" value="{$ppo->dossierIds}" />
  <input type="hidden" name="fichiers" id="fichiers" value="{$ppo->fichierIds}" />
  
  <div class="row">
    <label for="fichiersSelectionnes" class="form_libelle">{i18n key="classeur.message.selectedFiles"}</label>
    <p class="field"><input id="fichiersSelectionnes" name="fichiersSelectionnes" type="text" value="{$ppo->nomsContenus}" readonly="readonly" /></p>
  </div>
  
  <div class="row">
    <label>{i18n key="classeur.message.currentFolder"}</label>
    <p class="field">{if $ppo->dossierParent neq null}{$ppo->dossierParent->getPath()}{else}/{$ppo->classeur->titre}/{/if}</p>
  </div>
  
  <div class="row">
    <p class="label">{i18n key="classeur.message.destinationLocation"}</p>
    <div class="field selectFolder">{copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId targetType=$ppo->destinationType targetId=$ppo->destinationId withLocker=0}</div>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierParent->id}" class="button button-cancel" id="cancel">{i18n key="classeur.message.cancel"}</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>