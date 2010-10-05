<h4>Responsables de cet élève</h4>

{if count($ppo->persons) > 0}
  <table class="liste">
    <tr>
      <th class="liste_th"></th> 
      <th class="liste_th">login</th>
      <th class="liste_th">nom</th>
      <th class="liste_th">prenom</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->persons item=item}
      <tr>
        <td>
          {if $item->res_id_sexe eq 1}
            <img src="{copixresource path="img/gestionautonome/sexe-m.gif"}" title="Homme" />
          {else}                                                                 
            <img src="{copixresource path="img/gestionautonome/sexe-f.gif"}" title="Femme" />
          {/if}
        </td>
        <td>{$item->getLoginAccount()}</td>
        <td>{$item->res_nom}</td>
        <td>{$item->res_prenom1}</td>
        <td class="actions"> 
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
            <a href="{copixurl dest="gestionautonome||updatePersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId personId=$item->res_numero}"><img src="{copixresource path="img/gestionautonome/edit_item.png"}" title="Modifier le responsable" /></a>
            <a href="#" onclick="return confirm('Etes-vous sur de vouloir retirer cette affectation ?');removePerson({$item->res_numero}, {$ppo->studentId})";><img src="{copixresource path="img/gestionautonome/supprimer-role.gif"}" title="Ne plus affecter ce responsable à cet élève" /></a>
          {/if}
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
            <a href="#" onclick="return confirm('Etes-vous sur de vouloir supprimer ce responsable ?');deletePerson({$item->res_numero}, {$ppo->studentId})";><img src="{copixresource path="img/gestionautonome/trash.png"}" title="Supprimer ce responsable" /></a>
          {/if}
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<ul class="actions">
  {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|create@gestionautonome")}
    <li><a href="{copixurl dest="gestionautonome||createPersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId}" class="button">Ajouter un responsable</a></li>
  {/if}
</ul>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  
    function removePerson(idPerson, idStudent) {
         
      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|removePersonInCharge}'{literal},
        global: true,
        type: "GET",
        data: ({personId: idPerson, studentId: idStudent, nodeId: {/literal}{$ppo->nodeId}{literal}}),
        success: function(html){
          jQuery('#persons-in-charge').empty();
          jQuery("#persons-in-charge").append(html);
        }
      }).responseText;
      
      return false;
    }
  
    function deletePerson(idPerson, idStudent) {
         
      jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|deletePersonInCharge}'{literal},
        global: true,
        type: "GET",
        data: ({personId: idPerson, studentId: idStudent, nodeId: {/literal}{$ppo->nodeId}{literal}}),
        success: function(html){
          jQuery('#persons-in-charge').empty();
          jQuery("#persons-in-charge").append(html);
        }
      }).responseText;
      
      return false;
    }
  });
//]]> 
</script>
{/literal}