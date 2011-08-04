<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'une ville</h2>

<h3>Ville</h3>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="city_creation" id="city_creation" action="{copixurl dest="|validateCityCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    
    <div class="field">
      <label class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->city->nom}" />
    </div>
  </fieldset>
  
  <div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>