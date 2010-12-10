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
          {if $item.id_sexe eq 1}
            <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
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
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="cpt" id="cpt" value="{$ppo->cpt}" />
    
  <div id="person-method-add">
    <div class="field">
      <input id="add-new-person" type="radio" name="is-new-parent" value="1" {if isset($ppo->isNewParent) && $ppo->isNewParent}checked="checked"{/if} />
      <label for="add-new-person">Créer un nouveau parent pour cet élève</label>
    </div>
    <div class="field">
      <input id="add-existing-person" type="radio" name="is-new-parent" value="0" {if isset($ppo->isNewParent) && !$ppo->isNewParent}checked="checked"{/if} />
      <label for="add-existing-person">Associer un parent existant à cet élève</label>
    </div>
  </div>
  
  <div id="person-method-new" class="{if !isset($ppo->isNewParent) || !$ppo->isNewParent}hidden{/if}">
    <fieldset>
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
        {if isset($ppo->person->id_sexe)}
          {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->person->id_sexe}
        {else}
          {html_radios name='gender' values=$ppo->genderIds output=$ppo->genderNames selected=$ppo->genderIds.0}
        {/if}
      </div>

      <div class="field">
        <label for="id_par" class="form_libelle"> Relation :</label>
        <select class="form" name="id_par" id="id_par">
          {if isset ($ppo->id_par)}
            {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->id_par}
          {else}
            {html_options values=$ppo->linkIds output=$ppo->linkNames selected=$ppo->linkIds.1}
          {/if}
    	  </select>
      </div>
    </fieldset>
  </div>
  
  <div id="person-method-existing" class="{if !isset($ppo->isNewParent) || $ppo->isNewParent}hidden{/if}">
    <fieldset>
      <div class="field person-login">
        <label for="login" class="form_libelle">Identifiant du parent à rattacher à cet élève</label>
        <input class="form" type="text" name="login-search" id="login-search" value="{$ppo->login}" />
      </div>

      <div class="field agreement-check">
        {if $ppo->agreement eq "true"}
          <input type="checkbox" id="agreement" name="agreement" checked="checked" />
        {else}
          <input type="checkbox" id="agreement" name="agreement" />
        {/if}
        <span>
          Je reconnais connaître l’identité du parent ayant l’identifiant ci-dessus, et m’être assuré qu’il s’agit bien d’un responsable légal de l’enfant ci-dessous. 
          Ce responsable aura accès à des données personnelles de l’enfant en question.
        </span>
      </div>
    </fieldset>
  </div>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save-person" value="Ajouter" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[

  jQuery(document).ready(function(){

    jQuery('.button').button();
    
    jQuery('#person_creation').submit(function(e) {

      e.preventDefault();

      var nodeId = {/literal} {$ppo->nodeId} {literal}
      var nodeType = {/literal} '{$ppo->nodeType}' {literal}
      var cpt = {/literal} '{$ppo->cpt}' {literal}
      
      <!-- S'agit-il d'un nouveau parent ou d'une association de parent existant ? -->
      var isNewParent = jQuery('input[type=radio][name=is-new-parent]:checked').attr('value');

      if (isNewParent === undefined) {
        
        return false;
      }
  
      if (isNewParent == 1) {
        
        var lastname = jQuery('#nom').val();
        var firstname = jQuery('#prenom1').val();
        var login = jQuery('#login').val();
        var password = jQuery('#password').val();
        var gender = jQuery('input[type=radio][name=gender]:checked').attr('value');
        var id_par = jQuery('#id_par').val();
        
        var datas = ({isNewParent: isNewParent, nom: lastname, prenom1: firstname, login: login, password: password, gender: gender, parId: id_par, nodeId: nodeId, nodeType: nodeType, cpt: cpt});
      }
      else {
        
        var login = jQuery('#login-search').val();
        var agreement = jQuery('#agreement').is(':checked');
        
        var datas = ({isNewParent: isNewParent, login: login, agreement: agreement, nodeId: nodeId, nodeType: nodeType, cpt: cpt});
      }

      jQuery.ajax({
        
        url: {/literal}'{copixurl dest=gestionautonome|default|personInChargeCreation}'{literal},
        global: true,
        type: "POST",
        data: datas,
        success: function(html){
          
          jQuery('#persons-in-charge').empty();
          jQuery('#persons-in-charge').html(html);
        }
      });
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
      });
      
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
      });
      
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
    
    
    jQuery('#add-new-person').click(function() {

      jQuery('#person-method-new').removeClass('hidden');
      jQuery('#person-method-existing').addClass('hidden');
    });
    
    jQuery('#add-existing-person').click(function() {

      jQuery('#person-method-new').addClass('hidden');
      jQuery('#person-method-existing').removeClass('hidden');
    });
  });
//]]> 
</script>
{/literal}