<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->personnel->pers_numero}
    Modification d'une personne
  {else}
    Création d'un {$ppo->roleName}
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

<form name="edit_personnel" id="edit_personnel" action="{if $ppo->personnel->pers_numero}{copixurl dest="|validatePersonnelUpdate"}{else}{copixurl dest="|validatePersonnelCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset><legend>Profil</legend>
    {if $ppo->personnel->pers_numero neq null}
      <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
      <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
      <input type="hidden" name="id_personnel" id="id-personnel" value="{$ppo->personnel->pers_numero}" />
      <input type="hidden" name="type" id="type" value="{$ppo->type}" />
    {else}
      <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->nodeId}" />
      <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->nodeType}" />
    {/if}
    <input type="hidden" name="role" id="role" value="{$ppo->role}" />

    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->personnel->pers_nom|escape}" />
    </div>
    
    <div class="field">
      <label for="prenom1" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->personnel->pers_prenom1|escape}" />
    </div>
    
    <div class="field">
      <label for="date_nais" class="form_libelle"> Date de naissance :</label>
      <input class="form datepicker" type="text" name="date_nais" id="date_nais" value="{$ppo->personnel->pers_date_nais|escape}" />
    </div>
    
    {if $ppo->personnel->pers_numero neq null}
        <div class="field">
            <label class="form_libelle"> Sexe :</label>
            {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->personnel->pers_id_sexe}<br />
        </div>
        </fieldset>
        
        <fieldset><legend>Connexion</legend>
        <div class="field">
            <label for="login" class="form_libelle"> Identifiant :</label>
            <span class="form" name="login" id="login"><strong>{$ppo->account->login_dbuser|escape}</strong></span>
        </div>  
        
        <div class="field"><a href="#" class="button button-update" id="new-password-link">Modifier le mot de passe</a></div>
        
        <div class="field" id="new-password"{if $ppo->errors.password_invalid eq null} style="display: none"{/if}>
            <label for="password" class="form_libelle"> Mot de passe :</label>
            <input class="form" type="text" name="password" id="password" value="{$ppo->password|escape}" /> (<a href="#" id="generate-password">Générer</a>)
        </div>
	{else}
        <div class="field">
            <label class="form_libelle"> Sexe :</label>
            {if isset ($ppo->personnel->pers_id_sexe)}
                {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->personnel->pers_id_sexe}<br />
            {else}                                                                                                        
                {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->genderIds.0}<br />
            {/if}
        </div>
        </fieldset>
        
        <fieldset><legend>Connexion</legend>
        <div class="field">
            <label for="login" class="form_libelle"> Identifiant :</label>
            <input class="form" type="text" name="login" id="login" value="{$ppo->login|escape}" /> (<a href="#" id="generate-login">Générer</a>)
        </div>
        
        <div class="field">
            <label for="password" class="form_libelle"> Mot de passe :</label>
            <input class="form" type="text" name="password" id="password" value="{$ppo->password|escape}" /> (<a href="#" id="generate-password">Générer</a>)
            <span class="format">{i18n key="gestionautonome.info.formatPassword"}</span>
        </div>
      
    {/if}
  </fieldset>
  
  <div class="submit">
    <a href="{copixurl dest=gestionautonome||showTree tab=1}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
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
      yearRange: 'c-50:c+10'
    });
    

    jQuery('#new-password-link').click(function() {

      jQuery('#new-password').show();
    });
    
    if (jQuery('#generate-login')) {
      
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
    }
    
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
