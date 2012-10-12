<p>{customi18n key="gestionautonome|gestionautonome.message.completeboxtocreate%%indefinite__structure_element_responsables%%for%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</p>

{if $ppo->persons}
  {if $ppo->persons|@count > 0}
    <table>
      <tr>
        <th>Sexe</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Identifiant</th>
        <th>Relation</th>
        <th>Actions</th>
      </tr>
      {assign var=index value=1}
      {foreach from=$ppo->persons key=k item=item}
        <tr class="{if $index%2 eq 0}odd{else}even{/if}">
          <td class="center">
              {if $item->res_id_sexe eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if} 
          </td>
          <td>{$item->res_nom|escape}</td>
          <td>{$item->res_prenom1|escape}</td>
          <td>{$item->login|escape}</td>
          <td>{$item->link|escape}</td>
          <td class="actions">
            {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
              <a href="{copixurl dest="gestionautonome||updatePersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId personId=$item->res_numero}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier le responsable" /></a>
              <a href="{copixurl dest=gestionautonome|default|removePersonInCharge nodeId=$ppo->nodeId personId=$item->res_numero studentId=$ppo->studentId}" class="remove-link"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Ne plus affecter ce responsable à cet élève" /></a>
            {/if}
            {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
              <a href="{copixurl dest=gestionautonome|default|deletePersonInCharge nodeId=$ppo->nodeId personId=$item->res_numero studentId=$ppo->studentId}" class="delete-person"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer ce responsable" /></a>
            {/if}
          </td>
        </tr>
        {assign var=index value=$index+1}
      {/foreach}
    </table>
  {else}
    <i>Aucun parent...</i>
  {/if}
{elseif $ppo->personsInSession}
  <table>
    <tr>
      <th>Sexe</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Identifiant</th>
      <th>Relation</th>
    </tr>
    {assign var=index value=1}
    {foreach from=$ppo->personsInSession key=k item=item}
      <tr class="{if $index%2 eq 0}odd{else}even{/if}">
        <td class="center">
              {if $item.id_sexe eq 1 || $item.res_id_sexe eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if}
        </td>
        <td>{$item.lastname|escape}</td>
        <td>{$item.firstname|escape}</td>
        <td>{$item.login|escape}</td>
        <td>{$item.parente|escape}</td>
      </tr>
      {assign var=index value=$index+1}
    {/foreach}
  </table>
{/if}

{copixconf parameter='gestionautonome|personInChargeLinkingEnabled' assign=personInChargeLinkingEnabled}
  {if personInChargeLinkingEnabled }
  <h4>RESPONSABLE {$ppo->cpt}</h4>

  {if not $ppo->errors eq null}
  	<div class="mesgErrors error-light">
  	  <ul>
  	    {foreach from=$ppo->errors item=error}
  		    <li>{$error}</li>
  	    {/foreach}
  	  </ul>
  	</div>
  {/if}

  <form name="person_creation" id="person_creation" action="{copixurl dest="|personInChargeCreation"}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="nodeId" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="nodeType" id="type-node" value="{$ppo->nodeType}" />
    <input type="hidden" name="cpt" id="cpt" value="{$ppo->cpt}" />
    <input type="hidden" name="studentId" id="student-id" value="{$ppo->studentId}" />
    
    <div id="person-method-add">
      <div class="field">
        <input id="add-new-person" type="radio" name="is-new-parent" value="1" {if isset($ppo->isNewParent) && $ppo->isNewParent}checked="checked"{/if} />
        <label for="add-new-person">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__structure_element_responsable%%for%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</label>
      </div>
      <div class="field">
        <input id="add-existing-person" type="radio" name="is-new-parent" value="0" {if isset($ppo->isNewParent) && !$ppo->isNewParent}checked="checked"{/if} />
        <label for="add-existing-person">{customi18n key="gestionautonome|gestionautonome.message.affect%%indefinite__structure_element_responsable%%for%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</label>
      </div>
    </div>
  
    <div id="person-method-new" class="{if !isset($ppo->isNewParent) || !$ppo->isNewParent}hidden{/if}">
      <fieldset>
      	<legend>Profil</legend>
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
      
      <fieldset><legend>Connexion</legend>
      <div class="field">
          <label for="login" class="form_libelle"> Identifiant :</label>
          <input class="form" type="text" name="login" id="login" value="{$ppo->account->login|escape}" /> (<a href="#" id="generate-login">Générer</a>)
        </div>
    
        <div class="field">
          <label for="password" class="form_libelle"> Mot de passe :</label>
          <input class="form" type="text" name="password" id="password" value="{$ppo->account->password|escape}" /> (<a href="#" id="generate-password">Générer</a>)
        </div>
    	</fieldset>
        
    </div>
  
    <div id="person-method-existing" class="{if !isset($ppo->isNewParent) || $ppo->isNewParent}hidden{/if}">
      <fieldset>
        <div class="field person-login">
          <label for="login" class="form_libelle">{customi18n key="gestionautonome|gestionautonome.message.loginof%%structure_element_responsable%%toaffectto%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</label>
          <input class="form" type="text" name="login-search" id="login-search" value="{$ppo->login|escape}" />
        </div>

        <div class="field agreement-check">
          {if $ppo->agreement eq "true"}
            <input type="checkbox" id="agreement" name="agreement" checked="checked" />
          {else}
            <input type="checkbox" id="agreement" name="agreement" />
          {/if}
          <span>
            {customi18n key="gestionautonome|gestionautonome.message.termstoaffect%%structure_element_responsable%%" catalog=$ppo->vocabularyCatalog->id_vc}
            
            Ce responsable aura accès à des données personnelles de l’enfant en question.
          </span>
        </div>
      </fieldset>
    </div>
  
    <div class="submit">
    	<input class="button button-confirm" type="submit" name="save" id="save-person" value="Ajouter" />
    </div>
  </form>
  
  
  {literal}
  <script type="text/javascript">
  //<![CDATA[

    jQuery(document).ready(function(){

      //jQuery('.button').button();

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

        var studentId = jQuery('#student-id').val();
        if (isNewParent == 1) {

          var lastname = jQuery('#nom').val();
          var firstname = jQuery('#prenom1').val();
          var login = jQuery('#login').val();
          var password = jQuery('#password').val();
          var gender = jQuery('input[type=radio][name=gender]:checked').attr('value');
          var idPar = jQuery('#id_par').val();

          var datas = ({studentId: studentId, isNewParent: isNewParent, nom: lastname, prenom1: firstname, login: login, password: password, gender: gender, parId: idPar, nodeId: nodeId, nodeType: nodeType, cpt: cpt});
        }
        else {

          var login = jQuery('#login-search').val();
          var agreement = jQuery('#agreement').is(':checked');

          var datas = ({studentId: studentId, isNewParent: isNewParent, login: login, agreement: agreement, nodeId: nodeId, nodeType: nodeType, cpt: cpt});
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
{/if}
