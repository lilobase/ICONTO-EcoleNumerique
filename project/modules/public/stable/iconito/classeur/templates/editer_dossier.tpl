{if $ppo->dossierId eq null}
  <h2>{i18n key="classeur.message.newFolder"}</h2>
{else}
  <h2>{i18n key="classeur.message.editFolder"}</h2>
{/if}

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form name="edit_folder" id="edit_folder" action="{copixurl dest="classeur||editerDossier"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="parentId" id="parentId" value="{if $ppo->dossier->parent_id eq null}{$ppo->parent->id}{else}{$ppo->dossier->parent_id}{/if}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossier->id}" />
  
  <div class="field">
    <label for="dossier_nom" class="form_libelle">{i18n key="classeur.message.folderName"} :</label>
    <input class="form" type="text" name="dossier_nom" id="dossier_nom" value="{$ppo->dossier->nom}" />
  </div>
  
  <div class="field">
    <label for="dossier_emplacement" class="form_libelle">{i18n key="classeur.message.repository"} :</label>
    <input class="form" type="text" name="dossier_emplacement" id="dossier_emplacement" value="{$ppo->path}" readonly/>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeur->id}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>