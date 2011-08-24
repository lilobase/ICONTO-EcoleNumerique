{if $ppo->parent.nom}
  <h2>{$ppo->parent.nom}</h2>

  {if $ppo->type eq 'BU_CLASSE'}
    {assign var='hasCredentialStudentCreate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|student|create@gestionautonome")}
    {assign var='hasCredentialStudentUpdate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|student|update@gestionautonome")}
    {assign var='hasCredentialStudentDelete' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|student|delete@gestionautonome")}
    
    {assign var='hasCredentialTeacherCreate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|teacher|create@gestionautonome")}
    {assign var='hasCredentialTeacherUpdate' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|teacher|update@gestionautonome")}
    {assign var='hasCredentialTeacherDelete' value=$ppo->user->testCredential("module:classroom|`$ppo->parent.id`|teacher|delete@gestionautonome")}
    
    {assign var='hasCredentialPersonInChargeUpdate' value=$ppo->user->testCredential("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
    {assign var='hasCredentialPersonInChargeDelete' value=$ppo->user->testCredential("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
    
    <div id="tabs">
      <ul>
        <li><a href="#students-data"><span>&Eacute;lèves</span></a></li>
        <li><a href="#persons-data"><span>Enseignants</span></a></li>
        <li><a href="#parents-data"><span>Parents</span></a></li>
      </ul>

      <div id="students-data">
        {if $ppo->students neq null}
          <table>
            <tr>
              <th>Sexe</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Identifiant</th>
              <th>Niveau</th>
              <th>Actions</th>
            </tr>
            {assign var=index value=1}
            {foreach from=$ppo->students item=student}
              <tr class="{if $index%2 eq 0}odd{else}even{/if}">
                <td class="center">
                  {if $student->id_sexe eq 1}
                    <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />
                  {else}                                                                 
                    <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />
                  {/if}
                </td>
                <td>{$student->nom}</td>
                <td>{$student->prenom1}</td>
                <td>{$student->login}</td>
                <td>{$student->niveau_court}</td>
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
              {assign var=index value=$index+1}
            {/foreach}
          </table>
          
          <p class="items-count">{$ppo->students|@count} élèves dans cette classe</p>
          {else}
            <p class="center"><strong>Aucun élève</strong></p>
          {/if} 
      </div> 
      <div id="persons-data">
        {if $ppo->persons neq null}
          <table>
            <tr>
              <th>Sexe</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Identifiant</th>
              <th>Actions</th>
            </tr>
            {assign var=index value=1}
            {foreach from=$ppo->persons item=person}
              <tr class="{if $index%2 eq 0}odd{else}even{/if}">
                <td class="center">
                  {if $person->id_sexe eq 1}
                    <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
                  {else}                                                                 
                    <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
                  {/if}
                </td>
                <td>{$person->nom}</td>
                <td>{$person->prenom1}</td>
                <td>{$person->login_dbuser}</td>
                <td class="actions">
                  {if $hasCredentialTeacherUpdate}
                    <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier la personne" /></a>
                    <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$item->bu_type}" onclick="return confirm('Etes-vous sur de vouloir retirer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Supprimer le rôle de cette personne" /></a>
                  {/if}
                  {if $hasCredentialTeacherDelete}
                    <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cette personne" /></a>
                  {/if}
                </td>
              </tr>
              {assign var=index value=$index+1}
            {/foreach}  
          </table>
        {else}
          <p class="center"><strong>Aucun enseignant</strong></p>
        {/if} 
      </div>
      <div id="parents-data">
        {if $ppo->responsables neq null}
          <table>
            <tr>
              <th>Sexe</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Identifiant</th>
              <th>Actions</th>
            </tr>
            {assign var=index value=1}
            {foreach from=$ppo->responsables item=responsable}
              <tr class="{if $index%2 eq 0}odd{else}even{/if}">
                <td class="center">
                  {if $responsable->sexe eq 1}
                    <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
                  {else}                                                                 
                    <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
                  {/if}
                </td>
                <td>{$responsable->nom}</td>
                <td>{$responsable->prenom}</td>
                <td>{$responsable->login}</td>
                <td class="actions">
                  {if $hasCredentialPersonInChargeUpdate}
                    <a href="{copixurl dest="gestionautonome||updatePersonInCharge" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personId=$responsable->id}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier le responsable" /></a>
                  {/if}
                  {if $hasCredentialPersonInChargeDelete}
                    <a href="{copixurl dest=gestionautonome|default|deletePersonInCharge nodeId=$ppo->parent.id personId=$responsable->id}" class="delete-person"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer ce responsable" /></a>
                  {/if}
                </td>
              </tr>
              {assign var=index value=$index+1}
            {/foreach}  
          </table>
        {else}
          <p class="center"><strong>Aucun parent</strong></p>
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
    
    <table>
      <tr>
        <th>Sexe</th>
        <th>Type</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Identifiant</th>
        <th>Actions</th>
      </tr>
      {assign var=index value=1}
      {foreach from=$ppo->persons item=person}
        <tr class="{if $index%2 eq 0}odd{else}even{/if}">
          <td class="center">
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
        {assign var=index value=$index+1}
      {/foreach}
    </table>
  {/if}

<div id="personsActions">
  {if $ppo->parent.type == 'BU_GRVILLE'}
      {if $ppo->user->testCredential ("module:cities_group|`$ppo->parent.id`|cities_group_agent|create@gestionautonome")}
          <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button button-add">Créer un agent de groupes de villes</a>
          <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button button-next">Affecter une personne</a>
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_VILLE'}
      {if $ppo->user->testCredential ("module:city|`$ppo->parent.id`|city_agent|create@gestionautonome")}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button button-add">Créer un agent de ville</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button button-next">Affecter une personne</a>
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_ECOLE'}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|create@gestionautonome")} 
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button button-add">Créer un directeur</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button button-next">Affecter un directeur</a>
        {assign var=hasCredential value=1}
      {/if}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|create@gestionautonome")}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button button-add">Créer un personnel administratif</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button button-next">Affecter un personnel administratif</a>
        {assign var=hasCredential value=1}
      {/if}
      {if $hasCredential eq 1}
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_CLASSE'}
      {if $hasCredentialTeacherCreate}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button button-add">Ajouter un enseignant</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button button-next">Affecter une personne</a>
        {assign var=hasCredential value=1}
      {/if}
      {if $hasCredentialStudentCreate}
        <a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button button-add">Ajouter un élève</a>
        <a href="{copixurl dest="gestionautonome||setStudentsToClass" nodeId=$ppo->parent.id}" class="button button-next">Affecter des élèves</a>
        <a href="{copixurl dest="gestionautonome||addMultipleStudents" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button button-upload">Importer des élèves</a>
        <a href="{copixurl dest="gestionautonome||addExistingStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Affecter des élèves venant d'une autre classe</a>
        
        {assign var=hasCredential value=1}
      {/if}
      {if $hasCredentialStudentUpdate}
        <a href="{copixurl dest="gestionautonome||changeStudentsAffect" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button">Changer d'affectation plusieurs élèves</a>
      {/if}
      {if $hasCredentialTeacherUpdate || $hasCredentialStudentUpdate || $hasCredentialPersonInChargeUpdate}
        <br /><a href="{copixurl dest="gestionautonome||resetClassroomPasswords" nodeId=$ppo->parent.id}" class="button button-save">Gérer les mots de passe</a>
      {/if}
      {if $hasCredential eq 1}
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}
  {/if}
{else}
  <p>
    Aucun élément sélectionné dans la structure.
  </p>  
{/if}
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery("#tabs").tabs();
 	  if ({/literal}{$ppo->tab}{literal}) {
 	    
 	    jQuery("#tabs").tabs({ selected: {/literal}{$ppo->tab}{literal}});
 	  }
  });
  
//]]> 
</script>
{/literal}