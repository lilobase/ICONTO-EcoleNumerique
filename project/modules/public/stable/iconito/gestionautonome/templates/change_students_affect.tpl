<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Liste des élèves</h2>

{if $ppo->students neq null}

<form>
	<fieldset>
        <legend>Positionner une nouvelle affectation pour <strong>tous</strong> les élèves</legend>
        <label for="allAffect">Nouvelle affectation :</label>
        <select name="allAffect" id="allAffect">
            <option value="">-- pas de changement --</option>
            {html_options values=$ppo->levelIds output=$ppo->levelNames}
        </select>
        <input class="button button-confirm" type="button" value="Appliquer" id="btnAllAffect" /> 
    </fieldset>
</form>


  <form name="change_students_affect" id="change_students_affect" action="{copixurl dest="|validateChangeStudentsAffect"}" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    
    <table>
      <thead>
      <tr>
        <th>Sexe</th>
        <th>Compte</th> 
        <th>Nom</th>
        <th>Prénom</th> 
        <th>Dernière affectation</th>
        <th>Nouvelle affectation</th>
      </tr>
      </thead>
      <tbody>
      {foreach from=$ppo->students key=k item=student}
        <tr class="{if $k%2 eq 0}even{else}odd{/if}">
          <td class="center"><input type="hidden" name="students[]" value="{$student->idEleve}" />
              {if $student->id_sexe eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if}
          </td>
          <td>{$student->login}</td>
          <td>{$student->nom}</td>
          <td>{$student->prenom1}</td>
          <td>{$student->niveau_court} - {$student->nom_classe}</td>
          <td>
              <select class="form" name="newAffects[]">
                  <option value="">-- pas de changement --</option>
                  {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
              </select>
          </td>
        </tr>
      {/foreach}
      </tbody>
    </table>

    <div class="submit">
        <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
        <input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer les nouvelles affectations" />
    </div>
  </form>
{else}
  <em>Aucun élève</em>
  
  <div class="submit">
      <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
  </div>
{/if}