<h4>Personnes dans : {$ppo->parent.nom}</h4>

{if $ppo->childs neq null}
  <table class="liste">
    <tr>
      <th class="liste_th"></th>
      <th class="liste_th">type</th>
      <th class="liste_th">nom</th>
      <th class="liste_th">prenom</th>
      <th class="liste_th">compte</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->childs item=item}
      <tr>
        <td>{$item.sexe}</td>
        <td>{$item.type_nom}</td>
        <td>{$item.nom}</td>
        <td>{$item.prenom}</td>
        <td>{$item.login}</td>
        <td>
          {if $item.type eq 'USER_ELE'}
            <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}";>Modifier</a> -
            <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir retirer cet élève ?')";>Retirer</a> -
            <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cet élève ?')";>Supprimer</a>
          {else}
            Modifier -
            <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')";>Supprimer</a>
          {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<h4>Actions sur les personnes</h4>

{if $ppo->parent.type == 'BU_GRVILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}">Créer un agent de groupes de villes</a></li>
    <li>Ajouter une personne existante ici</li>
  </ul>

{elseif $ppo->parent.type == 'BU_VILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}">Créer un agent de ville</a></li>
    <li>Ajouter une personne existante ici</li>
  </ul>

{elseif $ppo->parent.type == 'BU_ECOLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}">Créer un personnel administratif</a></li>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}">Créer un directeur</a></li>
    <li>Ajouter une personne existante ici</li>
  </ul>

{elseif $ppo->parent.type == 'BU_CLASSE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}">Créer un enseignant</a></li>
    <li><a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}">Créer un élève</a></li>
    <li>Ajouter une personne existante ici</li>
  </ul>
{/if}