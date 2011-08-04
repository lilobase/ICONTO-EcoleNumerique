<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Création d'un {$ppo->roleName}</h2>

<h3>Personne</h3>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="personnel_creation" id="personnel_creation" action="{copixurl dest="|validatePersonnelCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    <input type="hidden" name="role" id="type-role" value="{$ppo->role}" />

    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->personnel->pers_nom}" />
    </div>
    
    <div class="field">
      <label for="prenom1" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->personnel->pers_prenom1}" />
    </div>
    
    <div class="field">
      <label for="date_nais" class="form_libelle"> Date de naissance :</label>
      <input class="form datepicker" type="text" name="date_nais" id="date_nais" value="{$ppo->personnel->pers_date_nais}" />
    </div>
    
    <div class="field">
      <label for="login" class="form_libelle"> Identifiant :</label>
      <input class="form" type="text" name="login" id="login" value="{$ppo->login}" /> (<a href="#" id="generate-login">Générer</a>)
    </div>
    
    <div class="field">
      <label for="password" class="form_libelle"> Mot de passe :</label>
      <input class="form" type="text" name="password" id="password" value="{$ppo->password}" /> (<a href="#" id="generate-password">Générer</a>)
    </div>
    
    <div class="field">
      <label class="form_libelle"> Sexe :</label>
      {if isset ($ppo->personnel->pers_id_sexe)}
        {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->personnel->pers_id_sexe}<br />
      {else}                                                                                                        
        {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->genderIds.0}<br />
      {/if}
    </div>
  </fieldset>
  
  <div class="submit">
      <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
      <input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/gestionautonome/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-50:c+10'
    });
    
    jQuery('#generate-login').click(function() {

      var lastname = jQuery('#nom').val();
      var firstname = jQuery('#prenom1').val();
      var nodeType = {/literal}{if $ppo->nodeType eq 'BU_GRVILLE' || $ppo->nodeType eq 'BU_VILLE'}'USER_VIL'{else}null{/if}{literal};   

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