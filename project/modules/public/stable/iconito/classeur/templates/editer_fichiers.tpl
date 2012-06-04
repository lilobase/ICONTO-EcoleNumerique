{copixzone process=classeur|affichageMenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId current="editerFichiers"}

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

<form id="edit_files" action="{copixurl dest="classeur||editerFichiers"}" method="post" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="fichierId" id="fichierId" value="{$ppo->fichier->id}" />
  <input type="hidden" name="dossierTmp" id="dossierTmp" value="{$ppo->dossierTmp}" />
  <input type="hidden" name="MAX_FILE_SIZE" value="{$ppo->conf->max_file_size}">
  
  <div class="row">
    <label for="fichiers" class="form_libelle">{i18n key="classeur.message.file"}</label>
    <p class="field">{if $ppo->fichier->id neq null}{if $ppo->fichier->estUneImage()}<img src="{$ppo->fichier->getLienMiniature(45)}" />{else}{$ppo->fichier}{/if}<br />{/if}
    <input id="fichier" name="fichier[]" type="file" /></p>
    {if $ppo->fichier->id eq null}
    <p class="field info">{i18n key="classeur.message.maxfilesize} {$ppo->conf->max_file_size|human_file_size}</p>
    {/if}
  </div>
  
  <div class="row">
    <label for="fichier_titre" class="form_libelle">{i18n key="classeur.message.title"}</label>
    <p class="field"><input class="form" type="text" name="fichier_titre" id="fichier_titre" value="{$ppo->fichier->titre}" /></p>
    <p class="field info" id="title_note" style="display: none;">{i18n key="classeur.message.titleNote"}</p>
  </div>
  
  <div class="row">
    <label for="fichier_commentaire" class="form_libelle">{i18n key="classeur.message.comment"}</label>
    <p class="field"><textarea name="fichier_commentaire" id="fichier_commentaire">{$ppo->fichier->commentaire}</textarea></p>
  </div>
  
  {if $ppo->fichier->id eq null}
  <div class="row">
    <label for="with_decompress">{i18n key="classeur.message.zipFile"}</label>
    <p class="field"><input type="checkbox" id="with_decompress" name="with_decompress" value="1" /> <label for="with_decompress">{i18n key="classeur.message.withDecompress"}</label></p>
  </div>
  {/if}
  
  <div class="row">
    <label for="fichier_emplacement" class="form_libelle">{i18n key="classeur.message.repository"}</label>
    <p class="field"><input class="form" type="text" name="fichier_emplacement" id="fichier_emplacement" value="{$ppo->path}" readonly="readonly" /></p>
  </div>
  
  <div class="submit">
    <a href="{copixurl dest=classeur||voirContenu classeurId=$ppo->classeurId dossierId=$ppo->dossierId}" class="button button-cancel" id="cancel">{i18n key="classeur.message.cancel"}</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.save"}" />
  </div>
</form>

{if $ppo->fichier->id eq null}
  {literal}
  <script type="text/javascript">
  //<![CDATA[
  $(document).ready(function() {    
    $('#with_decompress').change(function() {
      
      if ($('#with_decompress').is(':checked')) {

        $('#title_note').show();
        $('#fichier_titre').attr('disabled', 'disabled');
      }
      else {
        
        $('#title_note').hide();
        $('#fichier_titre').removeAttr('disabled');
      }
    });
  });
  //]]> 
  </script>
  {/literal}
{/if}