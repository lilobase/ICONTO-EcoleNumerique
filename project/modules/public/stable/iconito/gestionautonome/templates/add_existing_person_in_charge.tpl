<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Associer un parent existant à un élève</h2>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="person_link" id="person-link" action="{copixurl dest="|validateExistingPersonInChargeAdd"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="id_student" id="id-student" value="{$ppo->student->ele_idEleve}" />
    
  <div class="field person-login">
    <label for="login" class="form_libelle">Identifiant du parent à rattacher à cet élève</label>
    <input class="form" type="text" name="login" id="login" value="{$ppo->login}" />
  </div>
    
  <div class="field agreement-check">
    {if $ppo->agreement}
      <input type="checkbox" name="agreement" checked="checked" />
    {else}
      <input type="checkbox" name="agreement" />
    {/if}
    <span>
      Je reconnais connaître l’identité du parent ayant l’identifiant ci-dessus, et m’être assuré qu’il s’agit bien d’un responsable légal de l’enfant ci-dessous. 
      Ce responsable aura accès à des données personnelles de l’enfant en question.
    </span>
  </div>

  
  <div class="student-datas">
    <div class="label">Elève auquel le parent sera rattaché</div>
    <div class="datas">
      <div class="field">
        <label class="form_libelle"> Nom :</label>
        <span><strong>{$ppo->student->ele_nom}</strong></span>
      </div>

      <div class="field">
        <label class="form_libelle"> Prénom :</label>
        <span><strong>{$ppo->student->ele_prenom1}</strong></span>
      </div>
    
      {if $ppo->student->ele_date_nais}
        <div class="field">
          <label class="form_libelle"> Date de naissance :</label>
          <span>{$ppo->student->ele_date_nais}</span>
        </div>
      {/if}
  
      <div class="field">
        <label class="form_libelle"> Sexe :</label>
        {if $ppo->student->ele_id_sexe == 1}
          <span>Masculin</span>
        {else}
          <span>Feminin</span>
        {/if}
      </div>

      <div class="field">
        <label class="form_libelle"> Login :</label>
        <span><strong>{$ppo->studentAccount->login_dbuser}</strong></span>
      </div>
    </div>
    <hr class="clear" />
  </div>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
    <li><input class="button" type="submit" name="save" id="save" value="Valider" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	
 	  //jQuery('.button').button();
 	  
 	  jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||updateStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->student->ele_idEleve notxml=true}'{literal};
    });
  });
//]]> 
</script>
{/literal}