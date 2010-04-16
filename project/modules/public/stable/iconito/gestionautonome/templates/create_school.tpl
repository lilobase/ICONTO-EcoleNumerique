<h2>Ajout d'une école</h2>

<h3>Ecole</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="school_creation" id="school_creation" action="{copixurl dest="|validateSchoolCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}">
    
    <div class="field">
      <label for="name"> RNE :</label>
      <input class="form" type="text" name="RNE" id="RNE" value="{$ppo->school->RNE}" />
    </div>
    
    <label for="type"> Type :</label>
    <select class="form" name="type" id="type">
  	  {html_options values=$ppo->types output=$ppo->types selected=$ppo->school->type}
  	</select>
    
    <div class="field">
      <label for="name"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->school->nom}" />
    </div>
    
    <div class="field">
      <label for="name"> Numéro rue :</label>
      <input class="form" type="text" name="num_rue" id="num_rue" value="{$ppo->school->num_rue}" />
    </div>
    
    <div class="field">
      <label for="name"> Adresse 1 :</label>
      <input class="form" type="text" name="adresse1" id="adresse1" value="{$ppo->school->adresse1}" />
    </div>
    
    <div class="field">
      <label for="name"> Adresse 2 :</label>
      <input class="form" type="text" name="adresse2" id="adresse2" value="{$ppo->school->adresse2}" />
    </div>
    
    <div class="field">
      <label for="name"> Code postal :</label>
      <input class="form" type="text" name="code_postal" id="code_postal" value="{$ppo->school->code_postal}" />
    </div>
    
    <div class="field">
      <label for="name"> Commune :</label>
      <input class="form" type="text" name="commune" id="commune" value="{$ppo->school->commune}" />
    </div>
    
    <div class="field">
      <label for="name"> Téléphone :</label>
      <input class="form" type="text" name="tel" id="tel" value="{$ppo->school->tel}" />
    </div>
    
    <div class="field">
      <label for="name"> Site internet :</label>
      <input class="form" type="text" name="web" id="web" value="{$ppo->school->web}" />
    </div>
    
    <div class="field">
      <label for="name"> Mail :</label>
      <input class="form" type="text" name="mail" id="mail" value="{$ppo->school->mail}" />
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="form_button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="form_button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->parentId nodeType=$ppo->parentType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}