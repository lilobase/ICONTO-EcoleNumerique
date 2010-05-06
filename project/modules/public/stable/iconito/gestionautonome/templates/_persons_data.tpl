{if $ppo->parent.nom}
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
          {foreach from=$ppo->students item=student}
            <tr>
              <td>
                {if $student->id_sexe eq 0}
                  <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Garçon" />
                {else}                                                                 
                  <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Fille" />
                {/if}
              </td>
              <td>Elève</td>
              <td>{$student->nom}</td>
              <td>{$student->prenom1}</td>
              <td>{$student->login}</td>
              <td class="actions">
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|student|update@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier l'élève" /></a>
                {/if}
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|student|update@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('Etes-vous sur de vouloir retirer cet élève ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Ne plus affecter cet élève à cette classe" /></a>
                {/if}
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|student|delete@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('Etes-vous sur de vouloir supprimer cet élève ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cet élève" /></a>
                {/if}
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
          {foreach from=$ppo->persons item=person}
            <tr>
              <td>
                {if $person->id_sexe eq 0}
                  <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
                {else}                                                                 
                  <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
                {/if}
              </td>
              <td>{$person->nom_role}</td>
              <td>{$person->nom}</td>
              <td>{$person->prenom1}</td>
              <td>{$person->login_dbuser}</td>
              <td class="actions">
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|teacher|update@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier la personne" /></a>
                {/if}
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|teacher|update@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$item->bu_type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Supprimer le rôle de cette personne" /></a>
                {/if}
                {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|teacher|update@gestionautonome")}
                  <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cette personne" /></a>
                {/if}
              </td>
            </tr>
          {/foreach}  
        </table>
      {else}
        <i>Aucun enseignant...</i>
      {/if} 
    </div>
  </div>
  {elseif $ppo->persons neq null}

    <table class="liste">
      <tr>
        <th class="liste_th"></th>
        <th class="liste_th">Type</th>
        <th class="liste_th">Nom</th>
        <th class="liste_th">Prénom</th>
        <th class="liste_th">Login</th>
        <th class="liste_th"></th>
      </tr>
      {foreach from=$ppo->persons item=person}
        <tr>
          <td>
            {if $person->id_sexe eq 0}
              <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
            {/if}
          </td>
          <td>{$person->nom_role}</td>
          <td>{$person->nom}</td>
          <td>{$person->prenom1}</td>
          <td>{$person->login_dbuser}</td>
          <td class="actions"> 
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $ppo->user->testCredential ("module:cities_group|`$ppo->parent.id`|cities_group_agent|update@gestionautonome")) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $ppo->user->testCredential ("module:city|`$ppo->parent.id`|city_agent|update@gestionautonome"))
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ADM' && $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|update@gestionautonome")) 
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ENS' && $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|update@gestionautonome")))}

              <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier la personne" /></a>
              <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixresource path="../gestionautonome/supprimer-role.gif"}" title="Supprimer le rôle de cette personne" /></a>
            {/if}
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $ppo->user->testCredential ("module:cities_group|`$ppo->parent.id`|cities_group_agent|delete@gestionautonome")) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $ppo->user->testCredential ("module:city|`$ppo->parent.id`|city_agent|delete@gestionautonome"))
              || ($person->bu_type == 'USER_ADM' && $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|delete@gestionautonome")) 
              || ($person->bu_type == 'USER_ENS' && $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|delete@gestionautonome")))}
               
              <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cette personne" /></a>
            {/if}
            </td>
        </tr>
      {/foreach}
    </table>
  {/if}                        

  <h4>ACTIONS SUR LES PERSONNES</h4>

  {if $ppo->parent.type == 'BU_GRVILLE'}
    <ul class="actions">
      {if $ppo->user->testCredential ("module:cities_group|`$ppo->parent.id`|cities_group_agent|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Créer un agent de groupes de villes</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button">Affecter une personne existante ici</a></li>
      {/if}
    </ul>

  {elseif $ppo->parent.type == 'BU_VILLE'}
    <ul class="actions">
      {if $ppo->user->testCredential ("module:city|`$ppo->parent.id`|city_agent|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Créer un agent de ville</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button">Affecter une personne existante ici</a></li>
      {/if}
    </ul>

  {elseif $ppo->parent.type == 'BU_ECOLE'}
    <ul class="actions">
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Créer un personnel administratif</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Affecter un personnel administratif existant ici</a></li>
      {/if}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|create@gestionautonome")} 
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Créer un directeur</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Affecter un directeur existant ici</a></li>
      {/if}
    </ul>

  {elseif $ppo->parent.type == 'BU_CLASSE'}
    <ul class="actions">
      {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|teacher|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Créer un enseignant</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button">Affecter une personne existante ici</a></li>
      {/if}
      {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|student|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Créer un élève</a></li>
        <li><a href="{copixurl dest="gestionautonome||addMultipleStudents" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Ajouter une liste d'élèves</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Affecter des élèves venant d'une autre classe</a></li> 
      {/if}
      {if $ppo->user->testCredential ("module:classroom|`$ppo->parent.id`|student|update@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||changeStudentsAffect" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Changer d'affectation plusieurs élèves</a></li>
      {/if}
    </ul>
  {/if}
{else}
  <p>
    Aucun élément sélectionné dans la structure.
  </p>  
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