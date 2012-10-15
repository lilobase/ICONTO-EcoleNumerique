<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Importer {customi18n key="gestionautonome|gestionautonome.message.%%indefinite__structure_element_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</h2>

{if $ppo->studentsSuccess neq null}
  
  <p>Comptes créés avec succès</p> 
  
  <table>
    <tr>
      <th>Sexe</th>
      <th>Nom</th>
      <th>Prénom</th> 
      <th>Date de<br /> naissance</th> 
      <th>Identifiant</th>
      <th>Mot de passe</th>
    </tr>
    {foreach from=$ppo->studentsSuccess key=k item=studentSuccess}
      <tr class="{if $k%2 eq 0}even{else}odd{/if}">
        <td class="sexe">
          {if $studentSuccess.gender eq 1}
            <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}
        </td>
        <td>{$studentSuccess.firstname|escape}</td>
        <td>{$studentSuccess.lastname|escape}</td>
        <td>{$studentSuccess.birthdate|escape}</td>
        <td>{$studentSuccess.login|escape}</td>
        <td>{$studentSuccess.password|escape}</td>
      </tr>
      {foreach from=$studentSuccess.person key=j item=person}
        <tr>
          <td class="sexe"> 
            {if $person.gender eq 1}
              <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
            {/if}
          </td>
          <td>{$person.firstname|escape}</td>
          <td>{$person.lastname|escape}</td>
          <td>{$person.birthdate|escape}</td>
          <td>{$person.login|escape}</td>
          <td>{$person.password|escape}</td>
        </tr>
      {/foreach}
    {/foreach}
  </table>
  <hr />
{/if} 

{if not $ppo->error eq null}
	<div class="mesgErrors">
	  <ul>
		    <li>Les identifiants erronés ont été remplacés.</li>
	  </ul>
	</div>
{/if}

{if $ppo->students neq null}
  <form name="add_multiple_students_listing" id="add_multiple_students_listing" action="{copixurl dest="|validateMultipleStudentsListing"}" method="POST" enctype="multipart/form-data">
  
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  
  
      <table>
        <tr>
          <th class="sexe">Sexe</th>
          <th>Nom</th>
          <th>Prénom</th> 
          <th>Date de <br/>naissance</th> 
          <th>Identifiant</th>
          <th>Mot de passe</th>
          <th>Niveau <br />ou Relation</th>
          <th>Confirmer ?</th>
        </tr>
        {assign var=index value=1}
        {foreach from=$ppo->students key=k item=student}
          <tr class="{if $index%2 eq 0}odd{else}even{/if}">
            <td> 
              {if $student.gender eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if}
            </td>
            <td>{$student.lastname|escape}</td>
            <td>{$student.firstname|escape}</td>
            <td>{$student.birthdate|escape}</td>
            <td>
              <input type="text" name="logins[]" value="{$student.login|escape}" />
            </td>
            <td>
              <input type="text" name="passwords[]" value="{$student.password|escape}" />
            </td>
            <td class="center">
              <select name="levels[]">
                {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
          	  </select>
            </td>
            <td class="center">
              <input type="checkbox" name="keys[]" value="{$k}" checked="checked" />
            </td>
          </tr>
          {foreach from=$student.person key=j item=person}
            
            <tr class="{if $index%2 eq 0}odd{else}even{/if}">
              <td><img src="{copixurl}themes/default/images/child-of.png" alt="" />{if $person.gender eq 1}<img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />{else}<img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />{/if}</td>
              <td>{$person.lastname|escape}</td>
              <td>{$person.firstname|escape}</td>
              <td>{$person.birthdate|escape}</td>
              <td><input type="text" name="logins{$k}[]" value="{$person.login|escape}" /></td>
              <td><input type="text" name="passwords{$k}[]" value="{$person.password|escape}" /></td>
              <td class="center">{$person.nom_pa}</td>
              <td class="center"><input type="checkbox" name="person-keys{$k}[]" value="{$j}" checked="checked" /></td>
            </tr>
            
          {/foreach}
          {assign var=index value=$index+1}
        {/foreach}
      </table>
      <div class="submit">
          <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
          <input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
      </div>
  </form>
{else}
  <p class="mesgError">Aucun {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc} à ajouter</p>

  <div class="center">
      <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  </div>
{/if} 
