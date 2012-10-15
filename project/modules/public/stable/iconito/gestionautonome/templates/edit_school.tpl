<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>
  {if $ppo->nodeId neq null}
    {customi18n key="gestionautonome|gestionautonome.message.modify%%definite__structure%%" catalog=$ppo->vocabularyCatalog->id_vc}
  {else}
    {customi18n key="gestionautonome|gestionautonome.message.add%%indefinite__structure%%"}
  {/if}
</h2>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="edit_school" id="edit_school" action="{if $ppo->nodeId neq null}{copixurl dest="|validateSchoolUpdate"}{else}{copixurl dest="|validateSchoolCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
    {if $ppo->nodeId neq null}
      <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    {else}
      <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
      <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}">
    {/if}
    
    <label for="type" class="form_libelle"> Type :</label>
    <select class="form" name="type" id="type">
  	  {html_options values=$ppo->types output=$ppo->types selected=$ppo->school->type}
  	</select>
    
    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->school->nom|escape}" />
    </div>
    <div class="field">
      <label for="adresse1" class="form_libelle"> Adresse :</label>
      <input class="form short-text" type="text" name="num_rue" id="num_rue" value="{$ppo->school->num_rue|escape}" />
      <input class="form text" type="text" name="adresse1" id="adresse1" value="{$ppo->school->adresse1|escape}" />
    </div>
    <div class="field">
      <label for="adresse2" class="form_libelle"> Complément d'adresse :</label>
      <input class="form long-text" type="text" name="adresse2" id="adresse2" value="{$ppo->school->adresse2|escape}" />
    </div>
    <div class="field">
      <label for="code_postal" class="form_libelle"> Code postal :</label>
      <input class="form" type="text" name="code_postal" id="code_postal" value="{$ppo->school->code_postal|escape}" />
    </div>
    <div class="field">
      <label for="commune" class="form_libelle"> Commune :</label>
      <input class="form" type="text" name="commune" id="commune" value="{$ppo->school->commune|escape}" />
    </div>
    <div class="field">
      <label for="tel" class="form_libelle"> Téléphone :</label>
      <input class="form" type="text" name="tel" id="tel" value="{$ppo->school->tel|escape}" />
    </div>
  </fieldset>
  
  <div class="submit">
      <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
      <input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>
