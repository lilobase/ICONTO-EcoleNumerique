{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossier->id}

<h2>{i18n key="classeur.message.moveFile"}</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form id="move_file" action="{copixurl dest="classeur||deplacerFichier"}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="fichierId" id="fichierId" value="{$ppo->fichier->id}" />
  
  <div class="row">
    <label for="fichier" class="form_libelle">{i18n key="classeur.message.selectedFile"}</label>
    <p class="field"><input id="fichier" name="fichier" type="text" value="{$ppo->fichier}" readonly="readonly" /></p>
  </div>
  
  <div class="row">
    <label>{i18n key="classeur.message.currentFolder"}</label>
    <p class="field">{if $ppo->dossier neq null}{$ppo->dossier->getPath()}{else}/{$ppo->classeur->titre}/{/if}</p>
  </div>
  
  <div class="row">
    <p class="label">{i18n key="classeur.message.destinationLocation"}</p>
    <div class="field selectFolder">{copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId targetType=$ppo->destinationType targetId=$ppo->destinationId withMainLocker=true withSubLockers=$ppo->withSubLockers}</div>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierId}" class="button button-cancel" id="cancel">{i18n key="classeur.message.cancel"}</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>