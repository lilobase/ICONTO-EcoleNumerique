<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'un responsable</h2>

<p>Ce formulaire vous permet d'ajouter un responsable d'un élève.</p>

<h3>Elève</h3>

<div class="field">
  <label class="form_libelle"> Nom :</label>
  <span>{$ppo->student->ele_nom}</span>
</div>

<div class="field">
  <label class="form_libelle"> Prénom :</label>
  <span>{$ppo->student->ele_prenom1}</span>
</div>

<div class="field">
  <label class="form_libelle"> Login :</label>
  <span>{$ppo->studentAccount->login_dbuser}</span>
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
    
    <div class="column-left">
      <div class="field">
        <label for="nom" class="form_libelle"> Nom :</label>
        <input class="form" type="text" name="nom" id="nom" value="{$ppo->person->nom}" />
      </div>
    
      <div class="field">
        <label for="prenom1" class="form_libelle"> Prénom :</label>
        <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->person->prenom1}" />
      </div>

      <div class="field">
        <label for="login" class="form_libelle"> Identifiant :</label>
        <input class="form" type="text" name="login" id="login" value="{$ppo->login}" /> (<a href="#" id="generate-login">Générer</a>)
      </div>

      <div class="field">
        <label for="password" class="form_libelle"> Mot de passe :</label>
        <input class="form" type="text" name="password" id="password" value="{$ppo->password}" /> (<a href="#" id="generate-password">Générer</a>)
      </div>
    </div>
    
    <div class="column-right">
      <div class="field">
        <label class="form_libelle"> Sexe :</label>
        {if isset ($ppo->person->id_sexe)}
          {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->person->id_sexe}
        {else}
          {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->genderIds.0}
        {/if}
      </div>

      <div class="field">
        <label for="id_par" class="form_libelle"> Relation avec l'élève :</label>
        <select class="form" name="id_par" id="id_par">
          {if isset ($ppo->id_par)}
            {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->id_par}
          {else}
            {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->linkIds.1}
          {/if}
    	  </select>
      </div>
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
 	  
 	  jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||updateStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->ele_idEleve notxml=true}'{literal};
    });

    jQuery('#generate-login').click(function() {

      var lastname = jQuery('#nom').val();
      var firstname = jQuery('#prenom1').val();
      var nodeType = 'USER_RES';   

      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|generateLogin}'{literal},
        global: true,
        type: "GET",
        data: ({lastname: lastname, firstname: firstname, type: nodeType}),
        success: function(html){
          jQuery('#login').empty();
          jQuery("#login").val(html);
        }
      }).responseText;
      
      return false;
    });

    jQuery('#generate-password').click(function() {

      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|generatePassword}'{literal},
        global: true,
        type: "GET",
        success: function(html){
          jQuery('#password').empty();
          jQuery("#password").val(html);
        }
      }).responseText;
      
      return false;
    });
  });
//]]> 
</script>
{/literal}