<h2>Modification d'un élève</h2>

<p>Ce formulaire vous permet de modifier l'élève et de gérer ses responsables (parents).</p>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<div id="persons-in-charge">
  {copixzone process=gestionautonome|PersonsInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->idEleve notxml=true}
</div>

<form name="student_update" id="student_update" action="{copixurl dest="|validateStudentUpdate"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type_node" value="{$ppo->nodeType}" />
    <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->idEleve}" />
    
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
        <option value="0" {if $ppo->student->hors_scol == 0} selected=selected {/if}>Non</option>
        <option value="1" {if $ppo->student->hors_scol == 1} selected=selected {/if}>Oui</option>
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