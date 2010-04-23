<h2>Ajout d'un élève</h2>

<div id="persons-in-charge" style="display:none">
  {copixzone process=gestionautonome|CreatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType cpt=$ppo->cpt notxml=true}
</div>

<h3>Elève</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur error-light">
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
      <label for="level" class="form_libelle"> Niveau :</label>
      <select class="form" name="level" id="level">
        {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
  	  </select>
    </div>

    <div class="field">
      <label for="student_lastname" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="student_lastname" id="student_lastname" value="{$ppo->student->nom}" />
    </div>
    
    <div class="field">
      <label for="student_firstname" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="student_firstname" id="student_firstname" value="{$ppo->student->prenom1}" />
    </div>
    
    <div class="field">
      <label class="form_libelle"> Sexe :</label>
      {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->student->gender selected=$ppo->genderIds[0]}<br />
    </div>

    <div class="field">
      <label for="student_login" class="form_libelle"> Identifiant :</label>
      <input class="form" type="text" name="student_login" id="student_login" value="{$ppo->login}" /> (<a href="#" id="student-generate-login">Générer</a>)
    </div>
    
    <div class="field">
      <label for="student_password" class="form_libelle"> Mot de passe :</label>
      <input class="form" type="text" name="student_password" id="student_password" value="{$ppo->password}" /> (<a href="#" id="student-generate-password">Générer</a>)
    </div>
    
    <div class="field">
      <label for="student_birthdate" class="form_libelle"> Date de naissance :</label>
      <input class="form" type="text" name="student_birthdate" id="student_birthdate" value="{$ppo->student->date_nais}" />
    </div>
    
    <div class="field">
      <label for="add-persons-in-charge" class="form_libelle"> Responsables :</label>
      <input class="form" type="checkbox" id="add-persons-in-charge" name="person_in_charge" {if $ppo->resp_on}checked="checked"{/if}/>
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
 	  
 	  if (jQuery('#add-persons-in-charge').is(':checked')) {
 	    
 	    jQuery('#persons-in-charge').show();
 	  }
 	  else {
 	    
 	    jQuery('#persons-in-charge').hide();
 	  }
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
  
  jQuery('#add-persons-in-charge').change(function() {
    
    if (jQuery('#add-persons-in-charge').is(':checked')) {
      
      jQuery('#persons-in-charge').show();
    }
    else {
      
      jQuery('#persons-in-charge').hide();
    }
  });
  
  jQuery('#student-generate-login').click(function() {
    
    var lastname = jQuery('#student_lastname').val();
    var firstname = jQuery('#student_firstname').val(); 
    var nodeType = 'USER_ELE';   
    
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|generateLogin}'{literal},
      global: true,
      type: "GET",
      data: ({lastname: lastname, firstname: firstname, type: nodeType}),
      success: function(html){
        jQuery('#student_login').empty();
        jQuery("#student_login").val(html);
      }
    }).responseText;
  });
  
  jQuery('#student-generate-password').click(function() {
    
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|generatePassword}'{literal},
      global: true,
      type: "GET",
      success: function(html){
        jQuery('#student_password').empty();
        jQuery("#student_password").val(html);
      }
    }).responseText;
  });
//]]> 
</script>
{/literal}