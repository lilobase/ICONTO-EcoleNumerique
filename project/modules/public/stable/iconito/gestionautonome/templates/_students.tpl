<h4>Responsable de ces élèves</h4>

{if $ppo->students|@count > 0}
  <table>
    <tr>
      <th>Sexe</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Login</th>
      <th>Relation</th>
      <th>Actions</th>
    </tr>
    {foreach from=$ppo->students key=k item=item}
      <tr>
        <td class="center">
            {if $item->id_sexe eq 1}
                <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />
            {else}                                                                 
                <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />
            {/if}
        </td>
        <td>{$item->nom}</td>
        <td>{$item->prenom1}</td>
        <td>{$item->login}</td>
        <td>{$item->link}</td>
        <td class="actions">
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
            <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier le responsable" /></a>
            <a href="{copixurl dest=gestionautonome|default|removePersonInCharge nodeId=$ppo->nodeId personId=$ppo->personId studentId=$item->idEleve'}" class="remove-link"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Ne plus affecter ce responsable à cet élève" /></a>
          {/if}
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
            <a href="{copixurl dest=gestionautonome|default|deleteStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId target='personInCharge}" class="delete-person"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer ce responsable" /></a>
          {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{else}
  <i>Aucun élève...</i>
{/if}