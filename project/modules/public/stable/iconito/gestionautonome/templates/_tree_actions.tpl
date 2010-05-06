<h4>ACTIONS SUR LA STRUCTURE</h4>

{if $ppo->nodeType == 'BU_GRVILLE'}
  <ul>
    {if $ppo->user->testCredential ("module:cities_group|`$ppo->nodeId`|city|create@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||createCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Créer une ville</a></li>
    {/if}
  </ul>

{elseif $ppo->nodeType == 'BU_VILLE'}
  <ul>
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|school|create@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||createSchool" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button">Créer une école dans cette ville</a></li>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|update@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||updateCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier la ville</a></li>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|delete@gestionautonome")}  
      <li><a href="{copixurl dest="gestionautonome||deleteCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette ville ?')" class="button">Supprimer la ville</a></li>
    {/if}
  </ul>

{elseif $ppo->nodeType == 'BU_ECOLE'}
  <ul>
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|classroom|create@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||createClass" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button">Créer une classe dans cette école</a></li>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|update@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||updateSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier l'école</a></li>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|delete@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||deleteSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette école ?')" class="button">Supprimer l'école</a></li>
    {/if}
  </ul>

{elseif $ppo->nodeType == 'BU_CLASSE'}
  <ul>
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|update@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||updateClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier la classe</a></li>
    {/if}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|delete@gestionautonome")}
      <li><a href="{copixurl dest="gestionautonome||deleteClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette classe ?')" class="button">Supprimer la classe</a></li>
    {/if}
  </ul>

{else}
  <p>
    Sélectionnez un élément dans la structure.
  </p>
{/if}

{literal}
  <script type="text/javascript">
  //<![CDATA[
  
    jQuery(document).ready(function(){
 	
   	  jQuery('.button').button();
    });
  
  //]]> 
  </script>
{/literal}