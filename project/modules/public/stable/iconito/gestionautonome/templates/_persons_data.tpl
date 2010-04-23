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
        <td>{if $ppo->parent.type == 'BU_ECOLE' AND $item.type_nom == 'Enseignant'}Directeur{else}{$item.type_nom}{/if}</td>
        <td>{$item.nom}</td>
        <td>{$item.prenom}</td>
        <td>{$item.login}</td>
        <td>
          {if $item.type eq 'USER_ELE'}
            <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}"><img src="{copixresource path="img/edit_16x16.gif"}" /></a> -
            <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir retirer cet élève ?')"><img src="{copixresource path="img/tools/trash.png"}" /></a> -
            <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cet élève ?')"><img src="{copixresource path="img/delete_16x16.gif"}" /></a>
          {else}
            <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id type=$item.type}"><img src="{copixresource path="img/edit_16x16.gif"}" /></a> -
            <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixresource path="img/delete_16x16.gif"}" /></a>
          {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<h4>ACTIONS SUR LES PERSONNES</h4>

{if $ppo->parent.type == 'BU_GRVILLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Créer un agent de groupes de villes</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Ajouter une personne existante ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_VILLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Créer un agent de ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Ajouter une personne existante ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_ECOLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Créer un personnel administratif</a></li>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Créer un directeur</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Ajouter un personnel administratif existant ici</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Ajouter un directeur existant ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_CLASSE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Créer un enseignant</a></li>
    <li><a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Créer un élève</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Ajouter une personne existante ici</a></li>
    <li><a href="#" class="button">Ajouter un élève existant ici</a></li>
  </ul>
{/if}

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
//]]> 
</script>
{/literal}