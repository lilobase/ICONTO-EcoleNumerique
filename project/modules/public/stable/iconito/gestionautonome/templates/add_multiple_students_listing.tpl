<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Ajout d'une liste d'élèves</h2>

{if $ppo->studentsSuccess neq null}
  
  <p>Comptes créés avec succès</p> 
  
  <table class="liste">
    <tr>
      <th class="liste_th"></th>
      <th class="liste_th">Prénom</th> 
      <th class="liste_th">Nom</th>
      <th class="liste_th">DDN</th> 
      <th class="liste_th">Identifiant</th>
      <th class="liste_th">Mot de passe</th>
    </tr>
    {foreach from=$ppo->studentsSuccess key=k item=studentSuccess}
      <tr class="list_line{math equation="x%2" x=$k}">
        <td>
          {if $studentSuccess.gender eq 0}
            <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}
        </td>
        <td>{$studentSuccess.lastname}</td>
        <td>{$studentSuccess.firstname}</td>
        <td>{$studentSuccess.birthdate}</td>
        <td>{$studentSuccess.login}</td>
        <td>{$studentSuccess.password}</td>
      </tr>
      {foreach from=$studentSuccess.person key=j item=person}
        <tr>
          <td> 
            {if $person.gender eq 0}
              <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
            {/if}
          </td>
          <td>{$person.lastname}</td>
          <td>{$person.firstname}</td>
          <td>{$person.birthdate}</td>
          <td>{$person.login}</td>
          <td>{$person.password}</td>
        </tr>
      {/foreach}
    {/foreach}
    <tr class="liste_footer">
  		<td colspan="6"></td>
  	</tr>
  </table>
  <hr />
{/if} 

{if not $ppo->error eq null}
	<div class="message_erreur">
	  <ul>
		    <li>Les identifiants en erreur ont été remplacés.</li><br \>
	  </ul>
	</div>
{/if}

{if $ppo->students neq null}
  <form name="add_multiple_students_listing" id="add_multiple_students_listing" action="{copixurl dest="|validateMultipleStudentsListing"}" method="POST" enctype="multipart/form-data">
  
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  
  
      <table class="liste">
        <tr>
          <th class="liste_th"></th>
          <th class="liste_th">Prénom</th> 
          <th class="liste_th">Nom</th>
          <th class="liste_th">DDN</th> 
          <th class="liste_th">Identifiant</th>
          <th class="liste_th">Mot de passe</th>
          <th class="liste_th">Niveau / Relation</th>
          <th class="liste_th">Confirmer création ?</th>
        </tr>
        {foreach from=$ppo->students key=k item=student}
          <tr class="list_line1">
            <td> 
              {if $student.gender eq 0}
                <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
              {else}                                                                 
                <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
              {/if}
            </td>
            <td>{$student.firstname}</td>
            <td>{$student.lastname}</td>
            <td>{$student.birthdate}</td>
            <td>
              <input type="text" class="form" name="logins[]" value="{$student.login}" />
            </td>
            <td>
              <input type="text" class="form" name="passwords[]" value="{$student.password}" />
            </td>
            <td>
              <select class="form" name="levels[]">
                {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
          	  </select>
            </td>
            <td>
              <input type="checkbox" class="form" name="keys[]" value="{$k}" checked="checked" />
            </td>
          </tr>
          {foreach from=$student.person key=j item=person}
            <tr>
              <td> 
                {if $person.gender eq 0}
                  <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
                {else}                                                                 
                  <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
                {/if}
              </td>
              <td>{$person.firstname}</td>
              <td>{$person.lastname}</td>
              <td>{$person.birthdate}</td>
              <td>
                <input type="text" class="form" name="logins{$k}[]" value="{$person.login}" />
              </td>
              <td>
                <input type="text" class="form" name="passwords{$k}[]" value="{$person.password}" />
              </td>
              <td>{$person.nom_pa}</td>
              <td>
                <input type="checkbox" class="form" name="person-keys{$k}[]" value="{$j}" checked="checked" />
              </td>
            </tr>
          {/foreach}
        {/foreach}
        <tr class="liste_footer">
      		<td colspan="8"></td>
      	</tr>
      </table>
      <ul class="actions">
        <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
      	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
      </ul>
  </form>
{else}
  <i>Aucun élève à ajouter</i>

  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  </ul>
{/if}                                                                                

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
 	  
 	  jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
  });
//]]> 
</script>
{/literal}