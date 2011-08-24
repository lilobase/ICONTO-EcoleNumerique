<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Importer des élèves</h2>

{if $ppo->studentsSuccess neq null}
  
  <p>Comptes créés avec succès</p> 
  
  <table>
    <tr>
      <th>Sexe</th>
      <th>Prénom</th> 
      <th>Nom</th>
      <th>DDN</th> 
      <th>Identifiant</th>
      <th>Mot de passe</th>
    </tr>
    {foreach from=$ppo->studentsSuccess key=k item=studentSuccess}
      <tr class="{if $k%2 eq 0}even{else}odd{/if}">
        <td class="center">
          {if $studentSuccess.gender eq 1}
            <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
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
          <td class="center"> 
            {if $person.gender eq 1}
              <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
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
  </table>
  <hr />
{/if} 

{if not $ppo->error eq null}
	<div class="mesgErrors">
	  <ul>
		    <li>Les identifiants en erreur ont été remplacés.</li>
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
          <th>Prénom</th> 
          <th>Nom</th>
          <th>Date de <br/>naissance</th> 
          <th>Identifiant</th>
          <th>Mot de passe</th>
          <th>Niveau <br />ou Relation</th>
          <th>Confirmer ?</th>
        </tr>
        {assign var=index value=1}
        {foreach from=$ppo->students key=k item=student}
          <tr class="{if $index%2 eq 0}odd{else}even{/if}">
            <td class="center"> 
              {if $student.gender eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if}
            </td>
            <td>{$student.firstname}</td>
            <td>{$student.lastname}</td>
            <td>{$student.birthdate}</td>
            <td>
              <input type="text" name="logins[]" value="{$student.login}" />
            </td>
            <td>
              <input type="text" name="passwords[]" value="{$student.password}" />
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
              <td>{$person.firstname}</td>
              <td>{$person.lastname}</td>
              <td>{$person.birthdate}</td>
              <td><input type="text" name="logins{$k}[]" value="{$person.login}" /></td>
              <td><input type="text" name="passwords{$k}[]" value="{$person.password}" /></td>
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
  <p class="mesgError">Aucun élève à ajouter</p>

  <div class="center">
      <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  </div>
{/if} 
