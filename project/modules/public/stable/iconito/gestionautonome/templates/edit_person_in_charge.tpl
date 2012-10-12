<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>
  {if $ppo->person->numero neq null}
    Modification d'un responsable
  {else}
    Ajout d'un responsable
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

{if $ppo->studentId}
  <div class="contentLinked">
      <h3>Elève</h3>
    
      <div class="field">
        <p class="label"> Nom :</p>
        <p class="input">{$ppo->student->nom|escape}</p>
      </div>
    
      <div class="field">
        <p class="label"> Prénom :</p>
        <p class="input">{$ppo->student->prenom1|escape}</p>
      </div>
    
      <div class="field">
        <p class="label"> Identifiant :</p>
        <p class="input">{$ppo->student_account->login_dbuser|escape}</p>
      </div>
  </div>
{else}
  <div id="students" class="contentLinked">
    {copixzone process=gestionautonome|students nodeId=$ppo->nodeId nodeType=$ppo->nodeType personId=$ppo->person->numero cpt=$ppo->cpt notxml=true}
  </div>
{/if}


<form name="edit_person" id="edit_person" action="{if $ppo->person->numero neq null}{copixurl dest="|validatePersonInChargeUpdate"}{else}{copixurl dest="|validatePersonInChargeCreation"}{/if}" method="POST" enctype="multipart/form-data">
  <fieldset>
  	<legend>Profil</legend>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    {if $ppo->person->numero neq null}
      <input type="hidden" name="id_student" id="id-student" value="{$ppo->studentId}" />
      <input type="hidden" name="id_person" id="id-person" value="{$ppo->person->numero}" />
    {else}
      <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->ele_idEleve}" />
    {/if}
    
    <div class="field">
      <label for="nom" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->person->nom|escape}" />
    </div>
    
    <div class="field">
      <label for="prenom1" class="form_libelle"> Prénom :</label>
      <input class="form" type="text" name="prenom1" id="prenom1" value="{$ppo->person->prenom1|escape}" />
    </div>

    <div class="field">
      <label class="form_libelle"> Sexe :</label>
      {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->person->id_sexe}
    </div>
  
    {if $ppo->studentId}
      <div class="field">
        <label for="id_par" class="form_libelle"> Relation avec l'élève :</label>
        <select class="form" name="id_par" id="id_par">
          {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->res2ele->res2ele_id_par}
    	  </select>
      </div>
    {/if}
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
  </fieldset>
  
  <div class="submit">
    <a href="{if $ppo->studentId}{copixurl dest=gestionautonome||updateStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId save=1 notxml=true}{else}{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true tab=2}{/if}" class="button button-cancel">Annuler</a>
  	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	
    jQuery('#new-password-link').click(function() {

      jQuery('#new-password').show();
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
    
    jQuery("input[name=gender]").change(function() {
      
      jQuery('#id_par').val(11);
    });
    
    var links = { "1":"2", "2":"1", "3":"1", "4":"2", "5":"1", "6":"2", "7":"1", "8":"2" };
    jQuery('#id_par').change(function() {
      
      var genderId = links[jQuery(this).val()];
      jQuery("input[name=gender][value="+genderId+"]").attr("checked", "checked"); 
    });
  });
//]]> 
</script>
{/literal}
