{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->parent->id}

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
  
  <div class="row">
    <label for="dossier_nom" class="form_libelle">{i18n key="classeur.message.folderName"}</label>
    <p class="field"><input class="form" type="text" name="dossier_nom" id="dossier_nom" value="{$ppo->dossier->nom}" required="required" /></p>
  </div>
  
  <div class="row">
    <label for="dossier_emplacement" class="form_libelle">{i18n key="classeur.message.repository"}</label>
    <p class="field"><input class="form" type="text" name="dossier_emplacement" id="dossier_emplacement" value="{$ppo->path}" readonly="readonly" /></p>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>