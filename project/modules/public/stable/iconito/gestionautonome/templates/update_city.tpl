<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Modification d'une ville</h2>

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

<form name="city_update" id="city_update" action="{copixurl dest="|validateCityUpdate"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    
    <label class="form_libelle"> Nom :</label>
    <input class="form" type="text" name="name" id="name" value="{$ppo->city->nom}" />
  </fieldset>
  
  <div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>