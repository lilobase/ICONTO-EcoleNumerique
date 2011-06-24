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

<form name="move_file" id="move_file" action="{copixurl dest="classeur||deplacerFichier"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="fichierId" id="fichierId" value="{$ppo->fichier->id}" />
  
  <div class="field">
    <label for="fichier" class="form_libelle">{i18n key="classeur.message.selectedFile"} :</label>
    <input id="fichier" name="fichier" type="input" value="{$ppo->fichier->titre}" readonly />
  </div>
  
  <div class="field">
    <label for="destination" class="form_libelle">{i18n key="classeur.message.destinationLocation"} :</label>
    {copixzone process=classeur|selectionClasseurs classeurId=$ppo->classeurId targetType=$ppo->destinationType targetId=$ppo->destinationId}
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>