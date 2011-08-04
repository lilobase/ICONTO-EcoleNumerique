<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'une classe</h2>

<h3>Classe</h3>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="class_creation" id="class_creation" action="{copixurl dest="|validateClassCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    
    <div class="field">
      <label for="name" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->class->nom}" placeholder="CP/CE1 de Mme Lambert" /> <span class="formExample">Exemple : CP/CE1 de Mme Lambert</span>
    </div>
    
    <div class="field">
      <label for="name" class="form_libelle"> Niveaux :</label>
      <p class="input">{assign var=currentLevel value=0}
      {foreach from=$ppo->levels item=level}
      	<input type="checkbox" name="levels[]" value="{$level->id_n}" id="level{$level->id_n}" {if in_array($level->id_n, $ppo->levelsSelected)}checked="checked"{/if} /><label for="level{$level->id_n}">{$level->niveau_court}</label>{if $level->id_cycle neq $currentLevel && $currentLevel neq 0}<br />{/if}
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