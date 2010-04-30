<p>Ne remplissez ce bloc que si vous souhaitez créer des comptes parents associés à cet élève.</p>

{if not $ppo->personsInSession eq null}
  <table class="liste">
    <tr>
      <th class="liste_th"></th>
      <th class="liste_th">Nom</th>
      <th class="liste_th">Prénom</th>
      <th class="liste_th">Login</th> 
      <th class="liste_th">Password</th> 
    </tr>
    {foreach from=$ppo->personsInSession key=k item=item}
      <tr>
        <td>
          {if $item.id_sexe eq 0}
            <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}  
        </td>
        <td>{$item.lastname}</td>
        <td>{$item.firstname}</td>
        <td>{$item.login}</td>
        <td>{$item.password}</td>
      </tr>
    {/foreach}
  </table>                                  
{/if}

<h4>RESPONSABLE {$ppo->cpt}</h4>

{if not $ppo->errors eq null}
	<div class="message_erreur error-light">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="person_creation" id="person_creation" action="{copixurl dest="|personInChargeCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    <input type="hidden" name="cpt" id="cpt" value="{$ppo->cpt}" />
    
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
      <input class="form" type="text" name="login" id="login" value="{$ppo->account->login}" /> (<a href="#" id="generate-login">Générer</a>)
    </div>
    
    <div class="field">
      <label for="password" class="form_libelle"> Mot de passe :</label>
      <input class="form" type="text" name="password" id="password" value="{$ppo->account->password}" /> (<a href="#" id="generate-password">Générer</a>)
    </div>
    
    <div class="field">
      <label class="form_libelle"> Sexe :</label>
      {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->person->id_sexe}
    </div>

    <div class="field">
      <label for="id_par" class="form_libelle"> Relation :</label>
      <select class="form" name="id_par" id="id_par">
        {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->id_par}
  	  </select>
    </div>
  </fieldset>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save-person" value="Ajouter un autre responsable" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[

  jQuery(document).ready(function(){

    jQuery('.button').button();
  });
  
  jQuery('#person_creation').submit(function(e) {
    
    e.preventDefault();
    
    var lastname = jQuery('#nom').val();
    var firstname = jQuery('#prenom1').val();
    var login = jQuery('#login').val();
    var password = jQuery('#password').val();
    var gender = jQuery('input[type=radio][name=gender]:checked').attr('value');
    var id_par = jQuery('#id_par').val();
    var nodeId = {/literal} {$ppo->nodeId} {literal}
    var nodeType = {/literal} '{$ppo->nodeType}' {literal}
    var cpt = {/literal} '{$ppo->cpt}' {literal}
    
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|personInChargeCreation}'{literal},
      global: true,
      type: "POST",
      data: ({nom: lastname, prenom1: firstname, login: login, password: password, gender: gender, parId: id_par, nodeId: nodeId, nodeType: nodeType, cpt: cpt}),
      success: function(html){
        jQuery('#persons-in-charge').empty();
        jQuery('#persons-in-charge').html(html);
      }
    }).responseText;
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
  });
//]]> 
</script>
{/literal}