{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->parent->id current="editerDossier"}

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

{if $ppo->dossier->parent_id eq null}
  {assign var="parentId" value=$ppo->parent->id}
{else}
  {assign var="parentId" value=$ppo->dossier->parent_id}
{/if}

<form id="edit_folder" action="{copixurl dest="classeur||editerDossier"}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="parentId" id="parentId" value="{$parentId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossier->id}" />
  
  <div class="row">
    <label for="dossier_nom" class="form_libelle">{i18n key="classeur.message.folderName"}</label>
    <p class="field"><input class="form" type="text" name="dossier_nom" id="dossier_nom" value="{$ppo->dossier->nom}" required="required" autofocus="autofocus" /></p>
  </div>
  
  <div class="row">
    <label for="dossier_emplacement" class="form_libelle">{i18n key="classeur.message.repository"}</label>
    <p class="field"><input class="form" type="text" name="dossier_emplacement" id="dossier_emplacement" value="{$ppo->path}" readonly="readonly" /></p>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$parentId}" class="button button-cancel" id="cancel">{i18n key="classeur.message.cancel"}</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>