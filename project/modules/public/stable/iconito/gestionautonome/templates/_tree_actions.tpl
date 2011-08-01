{if $ppo->nodeType == 'BU_GRVILLE'}
    {if $ppo->user->testCredential ("module:cities_group|`$ppo->nodeId`|city|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-add">Créer une ville</a>
    {/if}

{elseif $ppo->nodeType == 'BU_VILLE'}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|school|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createSchool" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button button-add">Créer une école dans cette ville</a>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">Modifier la ville</a>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|delete@gestionautonome")}  
      <a href="{copixurl dest="gestionautonome||deleteCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette ville ?')" class="button button-delete">Supprimer la ville</a>
    {/if}

{elseif $ppo->nodeType == 'BU_ECOLE'}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|classroom|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createClass" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button button-add">Créer une classe dans cette école</a>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">Modifier l'école</a>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|delete@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||deleteSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette école ?')" class="button button-delete">Supprimer l'école</a>
    {/if}

{elseif $ppo->nodeType == 'BU_CLASSE'}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">Modifier la classe</a>
    {/if}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|delete@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||deleteClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette classe ?')" class="button button-delete">Supprimer la classe</a>
    {/if}
 

{else}
  <p>
    Sélectionnez un élément dans la structure.
  </p>
{/if}
