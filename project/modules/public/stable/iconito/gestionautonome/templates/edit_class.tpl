<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->nodeId neq null}
    {customi18n key="gestionautonome|gestionautonome.message.modify%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}
  {else}
    {customi18n key="gestionautonome|gestionautonome.message.add%%indefinite__structure_element%%"}
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

<form name="edit_class" id="edit_class" action="{if $ppo->nodeId neq null}{copixurl dest="|validateClassUpdate"}{else}{copixurl dest="|validateClassCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
    {if $ppo->nodeId neq null}
      <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    {else}
      <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
      <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    {/if}
    
    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->class->nom|escape}" placeholder="CP/CE1 de Mme Lambert" /> <span class="formExample">Exemple : CP/CE1 de Mme Lambert</span>
    </div>
    
    <div class="field">
      <label for="niveaux" class="form_libelle"> Niveaux :</label>
      <p class="input">{assign var=currentLevel value=0}
      {foreach from=$ppo->levels item=level}
      	<input type="checkbox" name="niveaux[]" value="{$level->id_n}" id="level{$level->id_n}" {if in_array($level->id_n, $ppo->levelsSelected)}checked="checked"{/if} /><label for="level{$level->id_n}">{$level->niveau_court}</label>{if $level->id_cycle neq $currentLevel && $currentLevel neq 0}<br />{/if}
        {assign var=currentLevel value=$level->id_cycle}
      {/foreach}
      </p>
    </div>
    
    <label for="type" class="form_libelle"> Type :</label>
    <select class="form" name="type" id="type">
      {html_options values=$ppo->typeIds output=$ppo->typeNames selected=$ppo->type} 
  	</select>
  </fieldset>
  
  <div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>
