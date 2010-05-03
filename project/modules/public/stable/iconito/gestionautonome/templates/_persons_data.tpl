<h4>Personnes dans : {$ppo->parent.nom}</h4>

{if $ppo->type eq 'BU_CLASSE'}
<div id="tabs">
  <ul>
    <li><a href="#students-data"><span>Elèves</span></a></li>
    <li><a href="#persons-data"><span>Enseignants</span></a></li>
  </ul>
      
  <div id="students-data">
    {if $ppo->students neq null}
      <table class="liste">
        <tr>
          <th class="liste_th"></th>
          <th class="liste_th">Type</th>
          <th class="liste_th">Nom</th>
          <th class="liste_th">Prénom</th>
          <th class="liste_th">Login</th>
          <th class="liste_th"></th>
        </tr>
        {foreach from=$ppo->students item=item}
          <tr>
            <td>
              {if $item.sexe eq 0}
                <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Garçon" />
              {else}                                                                 
                <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Fille" />
              {/if}
            </td>
            <td>{$item.type_nom}</td>
            <td>{$item.nom}</td>
            <td>{$item.prenom}</td>
            <td>{$item.login}</td>
            <td class="actions">
              <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier l'élève" /></a>
              <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir retirer cet élève ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Ne plus affecter cet élève à cette classe" /></a>
              <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cet élève ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cet élève" /></a>
            </td>
          </tr>
        {/foreach}
      </table>
      {else}
        <i>Aucun élève...</i>
      {/if} 
  </div> 
  <div id="persons-data">
    {if $ppo->persons neq null}
      <table class="liste">
        <tr>
          <th class="liste_th"></th>
          <th class="liste_th">Type</th>
          <th class="liste_th">Nom</th>
          <th class="liste_th">Prénom</th>
          <th class="liste_th">Login</th>
          <th class="liste_th"></th>
        </tr>
        {foreach from=$ppo->persons item=item}
          <tr>
            <td>
              {if $item.sexe eq 0}
                <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
              {else}                                                                 
                <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
              {/if}
            </td>
            <td>{$item.type_nom}</td>
            <td>{$item.nom}</td>
            <td>{$item.prenom}</td>
            <td>{$item.login}</td>
            <td class="actions">
              <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id type=$item.type}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier la personne" /></a>
              <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id userId=$item.user_id type=$item.type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Supprimer le rôle de cette personne" /></a>
              <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cette personne" /></a>
            </td>
          </tr>
        {/foreach}  
      </table>
    {else}
      <i>Aucun enseignant...</i>
    {/if} 
  </div>
</div>
{elseif $ppo->childs neq null}

  <table class="liste">
    <tr>
      <th class="liste_th"></th>
      <th class="liste_th">Type</th>
      <th class="liste_th">Nom</th>
      <th class="liste_th">Prénom</th>
      <th class="liste_th">Login</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->childs item=item}
      <tr>
        <td>
          {if $item.sexe eq 0}
            <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}
        </td>
        <td>{if $ppo->parent.type == 'BU_ECOLE' AND $item.type_nom == 'Enseignant'}Directeur{else}{$item.type_nom}{/if}</td>
        <td>{$item.nom}</td>
        <td>{$item.prenom}</td>
        <td>{$item.login}</td>
        <td class="actions">
          <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id type=$item.type}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier la personne" /></a>
          <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id userId=$item.user_id type=$item.type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Supprimer le rôle de cette personne" /></a>
          <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$item.id}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cette personne" /></a>
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<h4>ACTIONS SUR LES PERSONNES</h4>

{if $ppo->parent.type == 'BU_GRVILLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Créer un agent de groupes de villes</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Affecter une personne existante ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_VILLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Créer un agent de ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Affecter une personne existante ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_ECOLE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Créer un personnel administratif</a></li>
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Créer un directeur</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Affecter un personnel administratif existant ici</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Affecter un directeur existant ici</a></li>
  </ul>

{elseif $ppo->parent.type == 'BU_CLASSE'}
  <ul class="actions">
    <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Créer un enseignant</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Affecter une personne existante ici</a></li>
    <li><a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Créer un élève</a></li>
    <li><a href="{copixurl dest="gestionautonome||addMultipleStudents" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Ajouter une liste d'élèves</a></li>
    <li><a href="{copixurl dest="gestionautonome||addExistingStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Affecter des élèves venant d'une autre classe</a></li> 
    <li><a href="{copixurl dest="gestionautonome||changeStudentsAffect" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Changer d'affectation plusieurs élèves</a></li>
  </ul>
{/if}

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
 	  jQuery("#tabs").tabs();
 	  if ({/literal}{$ppo->tab}{literal}) {
 	    
 	    jQuery("#tabs").tabs({ selected: {/literal}{$ppo->tab}{literal}});
 	  }
  });
  
//]]> 
</script>
{/literal}