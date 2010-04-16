<h2>Ajout d'un responsable</h2>

<p>Ce formulaire vous permet d'ajouter un responsable d'un élève.</p>

<h3>Eleve</h3>

<div class="field">
  <label for="student_name"> Nom :</label>
  <span>{$ppo->student->ele_nom}</span>
</div>

<div class="field">
  <label for="student_firstname"> Prénom :</label>
  <span>{$ppo->student->ele_prenom1}</span>
</div>

<div class="field">
  <label for="student_login"> Login :</label>
  <span>{$ppo->student->ele_login}</span>
</div>

<h3>Responsable</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="person_creation" id="person_creation" action="{copixurl dest="|validatePersonInChargeCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->ele_idEleve}" />
    
    <label for="civilite"> Civilité :</label>
    <select class="form" name="civilite" id="civilite">
      {html_options values=$ppo->civilities output=$ppo->civilities selected=$ppo->person->civilite}
  	</select>
    
    <div class="field">
      <label for="nom"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->person->nom}" />
    </div>
    
    <div class="field" id="field_nomjf" {if $ppo->person->civilite neq 'Madame'}style="display: none"{/if}>
      <label for="nanom_jfe"> Nom de jeune fille :</label>
      <input class="form" type="text" name="nom_jf" id="nom_jf" value="{$ppo->person->nom_jf}" />
    </div>
    
    <div class="field">
      <label for="prenom1"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->person->prenom1}" />
    </div>
    
    <div class="field">
      <label for="date_nais"> Date de naissance :</label>
      <input class="form" type="text" name="date_nais" id="date_nais" value="{$ppo->person->date_nais}" />
    </div>

    <div class="field">
      <label for="pcs"> PCS :</label>
      <select class="form" name="id_pcs" id="id_pcs">
        {html_options values=$ppo->pcsIds output=$ppo->pcsNames selected=$ppo->person->id_pcs}
    	</select>
    </div>
    
    <div class="field">
      <label for="profession"> Profession :</label>
      <input class="form" type="text" name="profession" id="profession" value="{$ppo->person->profession}" />
    </div>
    
    <div class="field">
      <label for="situation"> Situation familiale :</label>
      <select class="form" name="id_fam" id="id_fam">
        {html_options values=$ppo->situationIds output=$ppo->situationNames selected=$ppo->person->id_fam}
    	</select>
    </div>
    
    <div class="field">
      <label for="tel_dom"> Téléphone fixe :</label>
      <input class="form" type="text" name="tel_dom" id="tel_dom" value="{$ppo->person->tel_dom}" />
    </div>
    
    <div class="field">
      <label for="tel_gsm"> Téléphone portable :</label>
      <input class="form" type="text" name="tel_gsm" id="tel_gsm" value="{$ppo->person->tel_gsm}" />
    </div>
    
    <div class="field">
      <label for="tel_pro"> Téléphone professionnel :</label>
      <input class="form" type="text" name="tel_pro" id="tel_pro" value="{$ppo->person->tel_pro}" />
    </div>
    
    <div class="field">
      <label for="tel_pro"> Numéro de poste :</label>
      <input class="form" type="text" name="num_poste" id="num_poste" value="{$ppo->person->num_poste}" />
    </div>
    
    <div class="field">
      <label for="mel"> Mail :</label>
      <input class="form" type="text" name="mel" id="mel" value="{$ppo->person->mel}" />
    </div>
    
    <div class="field">
      <label for="mel"> Mail pro :</label>
      <input class="form" type="text" name="mel_pro" id="mel_pro" value="{$ppo->person->mel_pro}" />
    </div>
    
    <div class="field">
      <label for="num_rue"> Numéro de rue :</label>
      <input class="form" type="text" name="num_rue" id="num_rue" value="{$ppo->person->num_rue}" />
    </div>
    
    <div class="field">
      <label for="adresse1"> Adresse 1 :</label>
      <input class="form" type="text" name="adresse1" id="adresse1" value="{$ppo->person->adresse1}" />
    </div>
    
    <div class="field">
      <label for="adresse2"> Adresse 2 :</label>
      <input class="form" type="text" name="adresse2" id="adresse2" value="{$ppo->person->adresse2}" />
    </div>
    
    <div class="field">
      <label for="code_postal"> Code postal :</label>
      <input class="form" type="text" name="code_postal" id="code_postal" value="{$ppo->person->code_postal}" />
    </div>
    
    <div class="field">
      <label for="commune"> Commune :</label>
      <input class="form" type="text" name="commune" id="commune" value="{$ppo->person->commune}" />
    </div>

    <div class="field">
      <label for="ville"> Ville :</label>
      <select class="form" name="ville" id="ville">
        {html_options values=$ppo->cityIds output=$ppo->cityNames selected=$ppo->person->id_ville}
  	  </select>
    </div>
    
    <div class="field">
      <label for="pays"> Pays :</label>
      <select class="form" name="pays" id="pays">
        {html_options values=$ppo->countryIds output=$ppo->countryNames selected=$ppo->person->pays}
  	  </select>
    </div>
    
    <div class="field">
      <label for="id_par"> Relation avec l'élève :</label>
      <select class="form" name="id_par" id="id_par">
        {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->id_par}
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
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||updateStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->ele_idEleve notxml=true}'{literal};
  });
  
  $('#civilite').change(function() {
    
    if ($("option:selected", this).val() == 'Madame') {
      
      $('#field_nomjf').show();
    }
    else {
      
      $('#field_nomjf').hide();
    }
  });
//]]> 
</script>
{/literal}