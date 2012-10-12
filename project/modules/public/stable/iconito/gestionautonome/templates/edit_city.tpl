<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->nodeId neq null}
    {customi18n key="gestionautonome|gestionautonome.message.modify%%definite__city%%" catalog=$ppo->vocabularyCatalog->id_vc}
  {else}
    {customi18n key="gestionautonome|gestionautonome.message.add%%indefinite__city%%"}
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

<form name="edit_city" id="edit_city" action="{if $ppo->nodeId neq null}{copixurl dest="|validateCityUpdate"}{else}{copixurl dest="|validateCityCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
    {if $ppo->nodeId neq null}
      <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    {else}
      <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
      <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    {/if}
    
    <div class="field">
      <label class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->city->nom|escape}" />
    </div>
  </fieldset>
  
  <div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>
