<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->student->idEleve neq null}
    Modification d'un élève
  {else}
    Ajout d'un élève
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

{if $ppo->student->idEleve neq null}
    {if $ppo->personId}
        <div class="contentLinked">
            <h3>Responsable</h3>
            
            <div class="field">
                <p class="label"> Nom :</p>
                <p class="input">{$ppo->person->nom|escape}</p>
            </div>
            
            <div class="field">
                <p class="label"> Prénom :</p>
                <p class="input">{$ppo->person->prenom1|escape}</p>
            </div>
            
            <div class="field">
                <p class="label"> Identifiant :</p>
                <p class="input">{$ppo->account_res->login_dbuser|escape}</p>
            </div>
        </div>
    {else}
        <div id="persons-in-charge" class="contentLinked">
            {copixzone process=gestionautonome|CreatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->idEleve cpt=$ppo->cpt notxml=true}
        </div>
    {/if}
{else}
  <div class="contentLinked">
    <div class="field">
        <input type="checkbox" id="withPersonInCharge" name="withPersonInCharge" {if $ppo->resp_on}checked="checked"{/if}/> <label for="withPersonInCharge">Avec responsable(s)</label>
    </div>
    <div id="persons-in-charge">
      {copixzone process=gestionautonome|CreatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType cpt=$ppo->cpt notxml=true}
    </div>
  </div>
{/if}


<form name="edit_student" id="edit_student" action="{if $ppo->student->idEleve neq null}{copixurl dest="|validateStudentUpdate"}{else}{copixurl dest="|validateStudentCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <legend>Profil</legend>
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
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->student->nom|escape}" />
    </div>
    
    <div class="field">
      <label for="prenom1" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->student->prenom1|escape}" />
    </div>
    
    <div class="field">
      <label for="date_nais" class="form_libelle"> Date de naissance :</label>
      <input class="form datepicker" type="text" name="date_nais" id="date_nais" value="{$ppo->student->date_nais|escape}" />
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
    </fieldset>
    
    <fieldset>
      <legend>Connexion</legend>
      <div class="field">
        <label for="student_login" class="form_libelle"> Identifiant :</label>
        <span class="form" name="student_login" id="student_login"><strong>{$ppo->account->login_dbuser|escape}</strong></span>
      </div>  
    
      <div class="field"><a href="#" class="button button-update" id="new-password-link">Modifier le mot de passe</a></div>
    
      <div class="field" id="new-password"{if $ppo->errors.password_invalid eq null} style="display: none"{/if}>
        <label for="student-password" class="form_libelle"> Mot de passe :</label>
        <input class="form" type="text" name="student_password" id="student_password" value="{$ppo->password|escape}" /> (<a href="#" id="generate-student-password">Générer</a>)
      </div>
    {else}
      </fieldset>
    
    <fieldset>
      <legend>Connexion</legend>
      <div class="field">
        <label for="student_login" class="form_libelle"> Identifiant :</label>
        <input class="form" type="text" name="student_login" id="student_login" value="{$ppo->login|escape}" /> (<a href="#" id="generate-student-login">Générer</a>)
      </div>

      <div class="field">
        <label for="student_password" class="form_libelle"> Mot de passe :</label>
        <input class="form" type="text" name="student_password" id="student_password" value="{$ppo->password|escape}" /> (<a href="#" id="generate-student-password">Générer</a>)
      </div>
    {/if}
  </fieldset>
  
  {if $ppo->student->idEleve eq null}
    <div class="hidden">
        <label for="add-persons-in-charge" class="form_libelle">Avec responsable(s) :</label>
        <input class="form" type="checkbox" id="add-persons-in-charge" name="person_in_charge" {if $ppo->resp_on}checked="checked"{/if}/>
    </div>
  {/if}
  
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
 	  if (jQuery('#add-persons-in-charge').is(':checked')) 
           jQuery('#persons-in-charge').show();
   	  else 
	      jQuery('#persons-in-charge').hide();
 	  {/literal}{/if}{literal}
 	  
	jQuery('#withPersonInCharge').change(function() {
		if (jQuery('#withPersonInCharge').is(':checked')) {
			jQuery('#persons-in-charge').show();
			jQuery('#add-persons-in-charge').attr('checked', true);
		}
		else {
			jQuery('#persons-in-charge').hide();
			jQuery('#add-persons-in-charge').attr('checked', false);
		}
		console.log('caché '+jQuery('#add-persons-in-charge').is(':checked'));
	});
	
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
