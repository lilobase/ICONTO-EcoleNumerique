<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Ajout d'une liste d'élèves</h2>

<p>Liste des comptes créés</p>

{if $ppo->studentsSuccess neq null}
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
    {/foreach}
    <tr class="liste_footer">
  		<td colspan="8"></td>
  	</tr>
  </table>
{/if}