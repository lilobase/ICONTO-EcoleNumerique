<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->student->idEleve neq null}
    Modification d'un élève
  {else}
    Ajout d'un élève
  {/if}
</h2>

{if $ppo->student->idEleve neq null}
  {if $ppo->personId}
    <p>Ce formulaire vous permet de modifier l'élève d'un responsable.</p>
  
    <h3>Responsable</h3>
  
    <div class="field">
      <label for="student_name"> Nom :</label>
      <span>{$ppo->person->nom}</span>
    </div>

    <div class="field">
      <label for="student_firstname"> Prénom :</label>
      <span>{$ppo->person->prenom1}</span>
    </div>

    <div class="field">
      <label for="student_login"> Login :</label>
      <span>{$ppo->account_res->login_dbuser}</span>
    </div>
  {else}
    <div id="persons-in-charge">
      {copixzone process=gestionautonome|CreatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->idEleve cpt=$ppo->cpt notxml=true}
    </div>
  {/if}
{else}
  <div id="persons-in-charge" style="display:none">
    {copixzone process=gestionautonome|CreatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType cpt=$ppo->cpt notxml=true}
  </div>
{/if}

<h3>Elève</h3>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="edit_student" id="edit_student" action="{if $ppo->student->idEleve neq null}{copixurl dest="|validateStudentUpdate"}{else}{copixurl dest="|validateStudentCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
    {if $ppo->student->idEleve neq null}
      <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
      <input type="hidden" name="type_node" id="type_node" value="{$ppo->nodeType}" />
      <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->idEleve}" />
      <input type="hidden" name="id_person" id="id-person" value="{$ppo->personId}" />
    {else}
      <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
      <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
      
      <div class="field">
        <label for="level" class="form_libelle"> Niveau :</label>
        <select class="form" name="level" id="level">
          {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
    	  </select>
      </div>
    {/if}
    
    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->student->nom}" />
    </div>
    
    <div class="field">
      <label for="prenom1" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->student->prenom1}" />
    </div>
    
    <div class="field">
      <label for="date_nais" class="form_libelle"> Date de naissance :</label>
      <input class="form datepicker" type="text" name="date_nais" id="date_nais" value="{$ppo->student->date_nais}" />
    </div> 
    
    <div class="field">
      <label class="form_libelle"> Sexe :</label>
      {if isset($ppo->student->id_sexe)}
        {html_radios name='id_sexe' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->student->id_sexe}<br />
      {else}
        {html_radios name='id_sexe' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->genderIds.0}<br />
      {/if}
    </div>
    
    {if $ppo->student->idEleve}
      {if $ppo->personId}
        <div class="field">
          <label for="id_par" class="form_libelle"> Relation responsable :</label>
          <select class="form" name="id_par" id="id_par">
            {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->res2ele->res2ele_id_par}
      	  </select>
        </div>
      {/if}
    
      <div class="field">
        <label for="student_login" class="form_libelle"> Login :</label>
        <span class="form" name="student_login" id="student_login"><strong>{$ppo->account->login_dbuser}</strong></span>
      </div>  
    
      <p><strong><a href="#" id="new-password-link">Nouveau mot de passe</a></strong></p>
    
      <div class="field" id="new-password"{if $ppo->errors.password_invalid eq null} style="display: none"{/if}>
        <label for="student-password" class="form_libelle"> Mot de passe :</label>
        <input class="form" type="text" name="student_password" id="student_password" value="{$ppo->password}" /> (<a href="#" id="generate-student-password">Générer</a>)
      </div>
    {else}
      <div class="field">
        <label for="student_login" class="form_libelle"> Identifiant :</label>
        <input class="form" type="text" name="student_login" id="student_login" value="{$ppo->login}" /> (<a href="#" id="generate-student-login">Générer</a>)
      </div>

      <div class="field">
        <label for="student_password" class="form_libelle"> Mot de passe :</label>
        <input class="form" type="text" name="student_password" id="student_password" value="{$ppo->password}" /> (<a href="#" id="generate-student-password">Générer</a>)
      </div>
      <div class="field">
        <label for="add-persons-in-charge" class="form_libelle"> Responsables :</label>
        <input class="form" type="checkbox" id="add-persons-in-charge" name="person_in_charge" {if $ppo->resp_on}checked="checked"{/if}/>
      </div>
    {/if}
  </fieldset>
  
  <div class="submit">
    <a href="{if $ppo->personId}{copixurl dest=gestionautonome||updatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType personId=$ppo->personId notxml=true}{else}{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}{/if}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  {/literal}{if $ppo->student->idEleve eq null}{literal}
 	    if (jQuery('#add-persons-in-charge').is(':checked')) {
 	    
   	    jQuery('#persons-in-charge').show();
   	  }
   	  else {
 	    
   	    jQuery('#persons-in-charge').hide();
   	  }
 	  {/literal}{/if}{literal}
 	  
 	  jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/gestionautonome/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
    jQuery('#new-password-link').click(function() {

      jQuery('#new-password').show();
    });
    
    jQuery('#add-persons-in-charge').change(function() {

      if (jQuery('#add-persons-in-charge').is(':checked')) {

        jQuery('#persons-in-charge').show();
      }
      else {

        jQuery('#persons-in-charge').hide();
      }
    })
    
    if (jQuery('#generate-student-login')) {
      
      jQuery('#generate-student-login').click(function() {

        var lastname = jQuery('#edit_student #nom').val();
        var firstname = jQuery('#edit_student #prenom1').val(); 
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

        return false;
      });
    }

    jQuery('#generate-student-password').click(function() {

      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|generatePassword}'{literal},
        global: true,
        type: "GET",
        success: function(html){
          jQuery('#student_password').empty();
          jQuery("#student_password").val(html);
        }
      }).responseText;
      
      return false;
    });
  });
//]]> 
</script>
{/literal}