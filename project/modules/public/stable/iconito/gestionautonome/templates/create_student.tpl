<h2>Ajout d'un élève</h2>

<h3>Elève</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="student_creation" id="student_creation" action="{copixurl dest="|validateStudentCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    
    <div class="field">
      <label for="level"> Niveau :</label>
      <select class="form" name="level" id="level">
        {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
  	  </select>
    </div>
    
    <label for="civilite"> Civilité :</label>
    <select class="form" name="civilite" id="civilite">
      {html_options values=$ppo->civilities output=$ppo->civilities selected=$ppo->student->civilite}
  	</select>
  	
    <div class="field">
      <label for="nom"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->student->nom}" />
    </div>
    
    <div class="field">
      <label for="prenom1"> Prenom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->student->prenom1}" />
    </div>
    
    <div class="field">
      <label for="num_rue"> Numéro de rue :</label>
      <input class="form" type="text" name="num_rue" id="num_rue" value="{$ppo->student->num_rue}" />
    </div>
    
    <div class="field">
      <label for="adresse1"> Adresse 1 :</label>
      <input class="form" type="text" name="adresse1" id="adresse1" value="{$ppo->student->adresse1}" />
    </div>
    
    <div class="field">
      <label for="adresse2"> Adresse 2 :</label>
      <input class="form" type="text" name="adresse2" id="adresse2" value="{$ppo->student->adresse2}" />
    </div>
    
    <div class="field">
      <label for="code_postal"> Code postal :</label>
      <input class="form" type="text" name="code_postal" id="code_postal" value="{$ppo->student->code_postal}" />
    </div>
    
    <div class="field">
      <label for="commune"> Commune :</label>
      <input class="form" type="text" name="commune" id="commune" value="{$ppo->student->commune}" />
    </div>

    <div class="field">
      <label for="ville"> Ville :</label>
      <select class="form" name="ville" id="ville">
        {html_options values=$ppo->cityIds output=$ppo->cityNames selected=$ppo->student->id_ville}
  	  </select>
    </div>

    <div class="field">
      <label for="pays"> Pays :</label>
      <select class="form" name="pays" id="pays">
        {html_options values=$ppo->countryIds output=$ppo->countryNames selected=$ppo->student->pays}
  	  </select>
    </div>
    
    <div class="field">
      <label for="hors_scol"> Hors scolarité :</label>
      <select class="form" name="hors_scol" id="hors_scol">
        <option value="0">Non</option>
        <option value="1">Oui</option>
  	  </select>
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
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}