<h2>{i18n key="classeur.message.addFavorite"}</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="classeur.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<ul class="mesgErrors">
    {foreach from=$ppo->erreurs item=erreur}
	    <li>{$erreur}</li>
    {/foreach}
</ul>
{/if}

<form name="edit_favorite" id="edit_favorite" action="{copixurl dest="classeur||editerFavori"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeurId}" />
  <input type="hidden" name="dossierId" id="dossierId" value="{$ppo->dossierId}" />
  <input type="hidden" name="favoriId" id="favoriId" value="{$ppo->favori->id}" />
  
  <div class="field">
    <label for="favori_titre" class="form_libelle">{i18n key="classeur.message.title"} :</label>
    <input class="form" type="text" name="favori_titre" id="fichier_titre" value="{$ppo->favori->titre}" />
  </div>
  
  <div class="textarea">
    <label for="favori_adresse" class="form_libelle">{i18n key="classeur.message.url"}</label>
    <textarea name="favori_adresse" id="favori_adresse">{$ppo->favori->adresse}</textarea>
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
    'uploader'  : '/js/uploadify/uploadify.swf',
    'script'    : '/js/uploadify/module_classeur.php',
    'cancelImg' : '/js/uploadify/cancel.png',
    'folder'    : '{/literal}{$ppo->dossierTmp}{literal}',
    'auto'      : false,
    'multi'     : true,
    'buttonText': 'Parcourir'
  });
});
//]]> 
</script>
{/literal}
{/if}