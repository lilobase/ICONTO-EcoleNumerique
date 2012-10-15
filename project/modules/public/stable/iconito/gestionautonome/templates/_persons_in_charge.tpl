<h4>{customi18n key="gestionautonome|gestionautonome.message.responsableof%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</h4>

{if count($ppo->persons) > 0}
  <table>
    <tr>
      <th>Sexe</th> 
      <th>Identifiant</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Actions</th>
    </tr>
    {assign var=index value=1}
    {foreach from=$ppo->persons item=item}
      <tr class="{if $index%2 eq 0}odd{else}even{/if}">
        <td class="center">
          {if $item->res_id_sexe eq 1}
            <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
          {else}                                                                 
            <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
          {/if}
        </td>
        <td>{$item->getLoginAccount()|escape}</td>
        <td>{$item->res_nom|escape}</td>
        <td>{$item->res_prenom1|escape}</td>
        <td class="actions"> 
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|update@gestionautonome")}
            <a href="{copixurl dest="gestionautonome||updatePersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId personId=$item->res_numero}"><img src="{copixurl}themes/default/images/icon-16/action-update.png" title="Modifier le responsable" /></a>
            <a href="{copixurl dest=gestionautonome|default|removePersonInCharge nodeId=$ppo->nodeId personId=$item->res_numero studentId=$ppo->studentId}" class="remove-link"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="Ne plus affecter ce responsable à cet élève" /></a>
          {/if}
          {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|delete@gestionautonome")}
            <a href="{copixurl dest=gestionautonome|default|deletePersonInCharge nodeId=$ppo->nodeId personId=$item->res_numero studentId=$ppo->studentId}" class="delete-person"><img src="{copixurl}themes/default/images/icon-16/action-delete.png" title="Supprimer ce responsable" /></a>
          {/if}
        </td>
      </tr>
      {assign var=index value=$index+1}
    {/foreach}
  </table>
{/if}

<div class="submit">
  {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|create@gestionautonome")}
    <a href="{copixurl dest="gestionautonome||createPersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__structure_element_responsable%%for%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
  {/if}
  {if $ppo->personInChargeLinkingEnabled}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|person_in_charge|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||addExistingPersonInCharge" nodeId=$ppo->nodeId nodeType=$ppo->nodeType studentId=$ppo->studentId}" class="button button-next">{customi18n key="gestionautonome|gestionautonome.message.affect%%indefinite__structure_element_responsable%%for%%definite__structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
  {/if}
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){

 	  jQuery('.remove-link').click(function() {
 	   
 	   if (confirm('Etes-vous sur de vouloir retirer cette affectation ?')) {
 	     
 	     jQuery.ajax({
         url: jQuery(this).attr('href'),
         global: true,
         type: "GET",
         success: function(html){
           jQuery('#persons-in-charge').empty();
           jQuery("#persons-in-charge").append(html);
         }
       }).responseText;
 	   }
 	   
 	   return false;
 	  });
 	  
 	  jQuery('.delete-person').click(function() {
 	   
 	   if (confirm('Etes-vous sur de vouloir supprimer ce responsable ?')) {
 	     
 	     jQuery.ajax({
         url: jQuery(this).attr('href'),
         global: true,
         type: "GET",
         success: function(html){
           jQuery('#persons-in-charge').empty();
           jQuery("#persons-in-charge").append(html);
         }
       }).responseText;
 	   }
 	   
 	   return false;
 	  });
  });
//]]> 
</script>
{/literal}
