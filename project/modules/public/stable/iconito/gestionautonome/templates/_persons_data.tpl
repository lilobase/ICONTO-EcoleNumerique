{if $ppo->parent.nom}
  <h2>{$ppo->parent.nom}</h2>

  {if $ppo->type eq 'BU_ECOLE'}
    {assign var='hasCredentialStudentUpdate' value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|student|update@gestionautonome")}
    {assign var='hasCredentialTeacherUpdate' value=$ppo->user->testCredential("module:school|`$ppo->parent.id`|teacher|update@gestionautonome")}
  {/if}
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
        <li><a href="#students-data"><span>{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</span></a></li>
        <li><a href="#persons-data"><span>{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</span></a></li>
        <li><a href="#parents-data"><span>{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_Responsables%%" catalog=$ppo->vocabularyCatalog->id_vc}</span></a></li>
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
                <td class="center">{$student->niveau_court}</td>
                <td class="actions">
                  {if $hasCredentialStudentUpdate}
                    <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="{customi18n key="gestionautonome|gestionautonome.message.modify%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                    <a href="{copixurl dest="gestionautonome||removeStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('{customi18n key="gestionautonome.message.confirmremove%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                  {/if}
                  {if $hasCredentialStudentDelete}
                    <a href="{copixurl dest="gestionautonome||deleteStudent" nodeId=$ppo->parent.id nodeType=$ppo->parent.type studentId=$student->idEleve}" onclick="return confirm('{customi18n key="gestionautonome.message.confirmdelete%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="{customi18n key="gestionautonome|gestionautonome.message.delete%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                  {/if}
                </td>
              </tr>
              {assign var=index value=$index+1}
            {/foreach}
          </table>
          
          <p class="items-count">{$ppo->students|@count} {customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%in%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}</p>
          {else}
            <p class="center"><strong>{customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</strong></p>
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
          <p class="center"><strong>{customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</strong></p>
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
          <p class="center"><strong>{customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_responsable%%" catalog=$ppo->vocabularyCatalog->id_vc}</strong></p>
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
          <td>
            {if $person->nom_role == "Directeur"}
              {customi18n key="kernel|kernel.usertypes.%%user_dir%%" catalog=$ppo->vocabularyCatalog->id_vc}
            {else}
              {customi18n key="kernel|kernel.usertypes.%%"|cat:$person->bu_type|lower|cat:"%%" catalog=$ppo->vocabularyCatalog->id_vc}
            {/if}
          </td>
          <td>{$person->nom}</td>
          <td>{$person->prenom1}</td>
          <td>{$person->login_dbuser}</td>
          <td class="actions"> 
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCitiesGroupAgentUpdate) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCityAgentUpdate)
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ADM' && $hasCredentialAdministrationStaffUpdate) 
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ENS' && $hasCredentialPrincipalUpdate))}

              <a href="{copixurl dest="gestionautonome||updatePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier la personne" /></a>
              {if $person->role eq 2}
                <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$person->bu_type}" onclick="return confirm('&Ecirc;tes-vous sûr de vouloir retirer le rôle de directeur à cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Supprimer le rôle de directeur de cette personne" /></a>
              {else}
                {if $person->hasTeacherRoleInSchool eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/action-exit-off.png" title="{customi18n key="gestionautonome|gestionautonome.message.%%definite__structure_element_staff_person%%stillaffectto%%indefinite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc} ({$person->classrooms})" />
                {else}
                  <a href="{copixurl dest="gestionautonome||removePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero userId=$person->id_dbuser type=$person->bu_type}" onclick="return confirm('{customi18n key="gestionautonome|gestionautonome.message.confirmremovepersonfrom%%definite__structure%%" catalog=$ppo->vocabularyCatalog->id_vc}')"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Supprimer le rôle de cette personne" /></a>
                {/if}
              {/if}
            {/if}
            {if (($ppo->parent.type == 'BU_GRVILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCitiesGroupAgentDelete) 
              || ($ppo->parent.type == 'BU_VILLE' && $person->bu_type == 'USER_VIL' && $hasCredentialCityAgentDelete)
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ADM' && $hasCredentialAdministrationStaffDelete) 
              || ($ppo->parent.type == 'BU_ECOLE' && $person->bu_type == 'USER_ENS' && $hasCredentialPrincipalDelete))}
              <a href="{copixurl dest="gestionautonome||deletePersonnel" nodeId=$ppo->parent.id nodeType=$ppo->parent.type personnelId=$person->numero type=$person->bu_type}" onclick="return confirm('&Ecirc;tes-vous sûr de vouloir supprimer cette personne ?')"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer cette personne" /></a>
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
          <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=5}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.affect"}</a>
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_VILLE'}
      {if $ppo->user->testCredential ("module:city|`$ppo->parent.id`|city_agent|create@gestionautonome")}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button button-add">Créer un agent de ville</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=4}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.affect"}</a>
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_ECOLE'}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|principal|create@gestionautonome")} 
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__structure_element_director_staff%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=2}" class="button button-next">{customi18n key="gestionautonome|gestionautonome.message.affect%%indefinite__structure_element_director_staff%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        {assign var=hasCredential value=1}
      {/if}
      {if $ppo->user->testCredential ("module:school|`$ppo->parent.id`|administration_staff|create@gestionautonome")}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button button-add">{customi18n key="gestionautonome.message.create%%indefinite__structure_element_administration_staff%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=3}" class="button button-next">{customi18n key="gestionautonome.message.affect%%indefinite__structure_element_administration_staff%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        {assign var=hasCredential value=1}
      {/if}
      {if $hasCredentialTeacherUpdate || $hasCredentialStudentUpdate}
        {if $ppo->nextGrade}
          <br /><a href="{copixurl dest="gestionautonome||manageAssignments" nodeId=$ppo->parent.id nodeType=BU_ECOLE}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.preparenextgrade"}</a>
        {/if}
      <br /><a href="{copixurl dest="gestionautonome||changeClassroom" nodeId=$ppo->parent.id nodeType=BU_ECOLE}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.changeClassroom"}</a>
      {/if}
      {if $hasCredential eq 1}
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}

  {elseif $ppo->parent.type == 'BU_CLASSE'}
      {if $hasCredentialTeacherCreate}
        <a href="{copixurl dest="gestionautonome||createPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.add%%indefinite__structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        <a href="{copixurl dest="gestionautonome||addExistingPersonnel" parentId=$ppo->parent.id parentType=$ppo->parent.type role=1}" class="button button-next">Affecter un enseignant</a>
      {/if}
      {if $hasCredentialStudentCreate}
        <a href="{copixurl dest="gestionautonome||createStudent" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.add%%indefinite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
      {/if}
      {if $hasCredentialTeacherUpdate || $hasCredentialStudentUpdate}
        {if $ppo->nextGrade}
          <br /><a href="{copixurl dest="gestionautonome||manageAssignments" nodeId=$ppo->parent.id nodeType=BU_CLASSE}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.preparenextgrade"}</a>
        {/if}
      <br /><a href="{copixurl dest="gestionautonome||changeClassroom" nodeId=$ppo->parent.id nodeType=BU_CLASSE}" class="button button-next">{i18n key="gestionautonome|gestionautonome.message.changeClassroom"}</a>
      {/if}
      {if $hasCredentialStudentCreate}
        <h3>Gestion</h3>
        <a href="{copixurl dest="gestionautonome||addMultipleStudents" parentId=$ppo->parent.id parentType=$ppo->parent.type}" class="button button-upload">{customi18n key="gestionautonome|gestionautonome.message.import%%indefinite__structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
        {copixzone process=gestionautonome|getpasswordslist notxml=true}
      {/if}
      {if $hasCredentialTeacherUpdate || $hasCredentialStudentUpdate || $hasCredentialPersonInChargeUpdate}
        <a href="{copixurl dest="gestionautonome||resetClassroomPasswords" nodeId=$ppo->parent.id}" class="button button-save">{i18n key="gestionautonome|gestionautonome.message.managepasswords"}</a>
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