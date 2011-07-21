<h4>Responsable de ces élèves</h4>

{if $ppo->students|@count > 0}
  <table class="liste">
    <tr>
      <th class="liste_th"></th>
      <th class="liste_th">Nom</th>
      <th class="liste_th">Prénom</th>
      <th class="liste_th">Login</th>
      <th class="liste_th">Relation</th>
      <th class="liste_th">&nbsp;</th>
    </tr>
    {foreach from=$ppo->students key=k item=item}
      <tr>
        <td>
          {if $item->id_sexe eq 1}
            <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}  
        </td>
        <td>{$item->nom}</td>
        <td>{$item->prenom1}</td>
        <td>{$item->login}</td>
        <td>{$item->link}</td>
        <td>
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
            <a href="{copixurl dest="gestionautonome||updateStudent" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId}"><img src="{copixresource path="img/gestionautonome/edit_item.png"}" title="Modifier le responsable" /></a>
            <a href="{copixurl dest=gestionautonome|default|removePersonInCharge nodeId=$ppo->nodeId personId=$ppo->personId studentId=$item->idEleve'}" class="remove-link"><img src="{copixresource path="img/gestionautonome/supprimer-role.gif"}" title="Ne plus affecter ce responsable à cet élève" /></a>
          {/if}
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
            <a href="{copixurl dest=gestionautonome|default|deleteStudent nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$item->idEleve personId=$ppo->personId target='personInCharge}" class="delete-person"><img src="{copixresource path="img/gestionautonome/trash.png"}" title="Supprimer ce responsable" /></a>
          {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{else}
  <i>Aucun élève...</i>
{/if}