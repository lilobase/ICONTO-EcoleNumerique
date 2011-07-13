{if $ppo->parent.nom}
  <h4>PERSONNES DANS : {$ppo->parent.nom}</h4>

  {if $ppo->type eq 'BU_CLASSE'}
  
    {assign var='hasCredentialStudentUpdate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|student|update@gestionautonome")}
    {assign var='hasCredentialStudentDelete' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|student|delete@gestionautonome")}
    
    {assign var='hasCredentialTeacherUpdate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|teacher|update@gestionautonome")}
    {assign var='hasCredentialTeacherDelete' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|teacher|delete@gestionautonome")}
    
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
              <th class="liste_th">Identifiant</th>
              <th class="liste_th"></th>
            </tr>
            {foreach from=$ppo->students item=student}
              <tr>
                <td>
                  {if $student->id_sexe eq 1}
                    <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />
                  {else}                                                                 
                    <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />
                  {/if}
                </td>
                <td>Elève</td>
                <td>{$student->nom}</td>
                <td>{$student->prenom1}</td>
                <td>{$student->login}</td>
                <td class="actions">
                  {if $hasCredentialStudentUpdate}
                    <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier l'élève" /></a>
                    <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('Etes-vous sur de vouloir retirer cet élève ?')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Ne plus affecter cet élève à cette classe" /></a>
                  {/if}
                  {if $hasCredentialStudentDelete}
                    <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('Etes-vous sur de vouloir supprimer cet élève ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cet élève" /></a>
                  {/if}
                </td>
              </tr>
            {/foreach}
          </table>
          
          <p class="students-count">Nombre d'élèves dans la classe : {$ppo->students|@count}</p>
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
              <th class="liste_th">Identifiant</th>
              <th class="liste_th"></th>
            </tr>
            {foreach from=$ppo->persons item=person}
              <tr>
                <td>
                  {if $person->id_sexe eq 1}
                    <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
                  {else}                                                                 
                    <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
                  {/if}
                </td>
                <td>{$person->nom_role}</td>
                <td>{$person->nom}</td>
                <td>{$person->prenom1}</td>
                <td>{$person->login_dbuser}</td>
                <td class="actions">
                  {if $hasCredentialTeacherUpdate}
                    <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixurl}themes/default/images/icon-16/action-update.png}" title="Modifier la personne" /></a>
                    <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$item->bu_type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Supprimer le rôle de cette personne" /></a>
                  {/if}
                  {if $hasCredentialTeacherDelete}
                    <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cette personne" /></a>
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
    
    {assign var='hasCredentialCitiesGroupAgentUpdate'     value=$ppo->user->testCredential("module:cities_group|`$ppo->parent.id`|cities_group_agent|update@gestionautonome")}
    {assign var='hasCredentialCitiesGroupAgentDelete'     value=$ppo->user->testCredential("module:cities_group|`$ppo->parent.id`|cities_group_agent|delete@gestionautonome")}
    
    {assign var='hasCredentialCityAgentUpdate'            value=$ppo->user->testCredential("module:city|`$ppo->parent.id`|city_agent|update@gestionautonome")}
    {assign var='hasCredentialCityAgentDelete'            value=$ppo->user->testCredential("module:city|`$ppo->parent.id`|city_agent|delete@gestionautonome")}
    
    {assign var='hasCredentialAdministrationStaffUpdate'  value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|administration_staff|update@gestionautonome")}
    {assign var='hasCredentialAdministrationStaffDelete'  value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|administration_staff|delete@gestionautonome")}
    
    {assign var='hasCredentialPrincipalUpdate'            value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|principal|update@gestionautonome")}
    {assign var='hasCredentialPrincipalDelete'            value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|principal|delete@gestionautonome")}
    
    <table class="liste">
      <tr>
        <th class="liste_th"></th>
        <th class="liste_th">Type</th>
        <th class="liste_th">Nom</th>
        <th class="liste_th">Prénom</th>
        <th class="liste_th">Identifiant</th>
        <th class="liste_th"></th>
      </tr>
      {foreach from=$ppo->persons item=person}
        <tr>
          <td>
            {if $person->id_sexe eq 1}
              <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
            {else}                                                                 
              <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
            {/if}
          </td>
          <td>{$person->nom_role}</td>
          <td>{$person->nom}</td>
          <td>{$person->prenom1}</td>
          <td>{$person->login_dbuser}</td>
          <td class="actions"> 
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCitiesGroupAgentUpdate) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCityAgentUpdate)
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ADM' && $hasCredentialAdministrationStaffUpdate) 
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ENS' && $hasCredentialPrincipalUpdate))}

              <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier la personne" /></a>
              <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Supprimer le rôle de cette personne" /></a>
            {/if}
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCitiesGroupAgentDelete) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCityAgentDelete)
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ADM' && $hasCredentialAdministrationStaffDelete) 
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ENS' && $hasCredentialPrincipalDelete))}
               
              <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cette personne" /></a>
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
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|create@gestionautonome")} 
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Créer un directeur</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button">Affecter un directeur existant ici</a></li>
      {/if}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|create@gestionautonome")}
        <li><a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Créer un personnel administratif</a></li>
        <li><a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button">Affecter un personnel administratif existant ici</a></li>
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