{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}

<h2>
  {if $ppo->fichier->id neq null}
    {i18n key="classeur.message.editFile"}
  {else}
    {i18n key="classeur.message.addFiles"}
  {/if}
</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form name="edit_files" id="edit_files" action="{copixurl dest="classeur||editerFichiers"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="fichierId" id="fichierId" value="{$ppo->fichier->id}" />
  <input type="hidden" name="dossierTmp" id="dossierTmp" value="{$ppo->dossierTmp}" />
  
  <div class="field">
    <label for="fichiers" class="form_libelle">{i18n key="classeur.message.files"} :</label>
    {if $ppo->fichier->id neq null}{$ppo->fichier->titre} - {$ppo->fichier->fichier}{/if}<br />
    <input id="fichiers" name="fichiers[]" type="file" />
  </div>
  
  <div class="field">
    <label for="fichier_titre" class="form_libelle">{i18n key="classeur.message.title"} :</label>
    <input class="form" type="text" name="fichier_titre" id="fichier_titre" value="{$ppo->fichier->titre}" />
  </div>
  
  <div class="textarea">
    <label for="fichier_commentaire" class="form_libelle">{i18n key="classeur.message.comment"}</label>
    <textarea name="fichier_commentaire" id="fichier_commentaire">{$ppo->fichier->commentaire}</textarea>
  </div>
  
  <div class="field">
    <label for="fichier_emplacement" class="form_libelle">{i18n key="classeur.message.repository"} :</label>
    <input class="form" type="text" name="fichier_emplacement" id="fichier_emplacement" value="{$ppo->path}" readonly/>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}">
      <span class="button button-cancel" class="cancel" id="cancel">{i18n key="classeur.message.cancel"}</span>
    </a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>

{if $ppo->fichier->id eq null}
  {literal}
  <script type="text/javascript">
  //<![CDATA[
  $(document).ready(function() {
    $('#fichiers').uploadify({
      'uploader'        : '/js/uploadify/uploadify.swf',
      'script'          : '/js/uploadify/module_classeur.php',
      'cancelImg'       : '',
      'folder'          : '{/literal}{$ppo->dossierTmp}{literal}',
      'auto'            : true,
      'multi'           : true,
      'removeCompleted' : false,
      'buttonText'      : 'Parcourir'
    });
  });
  //]]> 
  </script>
  {/literal}
{/if}