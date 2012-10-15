<h3>{customi18n key="gestionautonome|gestionautonome.message.responsableof%%indefinite__structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</h3>

{if $ppo->students|@count > 0}
  <table>
    <tr>
      <th>Sexe</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Identifiant</th>
      <th>Relation</th>
      <th>Actions</th>
    </tr>
    {assign var=index value=1}
    {foreach from=$ppo->students key=k item=item}
      <tr class="{if $index%2 eq 0}odd{else}even{/if}">
        <td class="center">
            {if $item->id_sexe eq 1}
                <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />
            {else}                                                                 
                <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />
            {/if}
        </td>
        <td>{$item->nom|escape}</td>
        <td>{$item->prenom1|escape}</td>
        <td>{$item->login|escape}</td>
        <td>{$item->link|escape}</td>
        <td class="actions">
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
            <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier le responsable" /></a>
            <a href="{copixurl dest=gestionautonome|default|removePersonInCharge nodeId=$ppo->nodeId personId=$ppo->personId studentId=$item->idEleve'}" class="remove-link"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.donotassignresponsableto%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}Ne plus affecter ce responsable à cet élève" /></a>
          {/if}
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
            <a href="{copixurl dest=gestionautonome|default|deleteStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId target='personInCharge}" class="delete-person"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer ce responsable" /></a>
          {/if}
        </td>
      </tr>
      {assign var=index value=$index+1}
    {/foreach}
  </table>
{else}
  <i>{customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</i>
{/if}
