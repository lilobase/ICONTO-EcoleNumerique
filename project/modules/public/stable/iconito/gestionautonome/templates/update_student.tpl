<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Modification d'un élève</h2>

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

<form name="student_update" id="student_update" action="{copixurl dest="|validateStudentUpdate"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type_node" value="{$ppo->nodeType}" />
    <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->idEleve}" />
    <input type="hidden" name="id_person" id="id-person" value="{$ppo->personId}" />
    
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
      {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->student->id_sexe}<br />
    </div>
    
    {if $ppo->personId}
      <div class="field">
        <label for="id_par" class="form_libelle"> Relation responsable :</label>
        <select class="form" name="id_par" id="id_par">
          {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->res2ele->res2ele_id_par}
    	  </select>
      </div>
    {/if}
    
    <div class="field">
      <label for="login" class="form_libelle"> Login :</label>
      <span class="form" name="login" id="login"><strong>{$ppo->account->login_dbuser}</strong></span>
    </div>  
    
    <p><strong><a href="#" id="new-password-link">Nouveau mot de passe</a></strong></p>
    
    <div class="field" id="new-password"{if $ppo->errors.password_invalid eq null} style="display: none"{/if}>
      <label for="student-password" class="form_libelle"> Mot de passe :</label>
      <input class="form" type="text" name="password" id="student-password" value="{$ppo->password}" /> (<a href="#" id="generate-student-password">Générer</a>)
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="button button-cancel" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  //jQuery('.button').button();
 	  
 	  jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/gestionautonome/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
    jQuery('#cancel').click(function() {
      {/literal}
        {if $ppo->personId}
          document.location.href='{copixurl dest=gestionautonome||updatePersonInCharge nodeId=$ppo->nodeId nodeType=$ppo->nodeType personId=$ppo->personId notxml=true}';
        {else}
          document.location.href='{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}';
        {/if}
      {literal}
    });

    jQuery('#new-password-link').click(function() {

      jQuery('#new-password').show();
    });

    jQuery('#generate-student-password').click(function() {

      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|generatePassword}'{literal},
        global: true,
        type: "GET",
        success: function(html){
          jQuery('#student-password').empty();
          jQuery("#student-password").val(html);
        }
      }).responseText;
      
      return false;
    });
  });
//]]> 
</script>
{/literal}