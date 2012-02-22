{if $ppo->nodeType == 'BU_GRVILLE'}
    {if $ppo->user->testCredential ("module:cities_group|`$ppo->nodeId`|city|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__city%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}

{elseif $ppo->nodeType == 'BU_VILLE'}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|school|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createSchool" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__structure%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">{customi18n key="gestionautonome|gestionautonome.message.modify%%definite__city%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
    {if $ppo->user->testCredential ("module:city|`$ppo->nodeId`|city|delete@gestionautonome")}  
      <a href="{copixurl dest="gestionautonome||deleteCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('{php}echo addslashes(CopixCustomI18N::get('gestionautonome|gestionautonome.message.confirmdelete%%definite__city%%', array('catalog' => $ppo->vocabularyCatalog->id_vc))){/php}')" class="button button-delete">{customi18n key="gestionautonome|gestionautonome.message.delete%%definite__city%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}

{elseif $ppo->nodeType == 'BU_ECOLE'}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|classroom|create@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||createClass" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button button-add">{customi18n key="gestionautonome|gestionautonome.message.create%%indefinite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">{customi18n key="gestionautonome|gestionautonome.message.modify%%definite__structure%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
    {if $ppo->user->testCredential ("module:school|`$ppo->nodeId`|school|delete@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||deleteSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('{php}echo addslashes(CopixCustomI18N::get('gestionautonome|gestionautonome.message.confirmdelete%%definite__structure%%', array('catalog' => $ppo->vocabularyCatalog->id_vc))){/php}')" class="button button-delete">{customi18n key="gestionautonome|gestionautonome.message.delete%%definite__structure%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}

{elseif $ppo->nodeType == 'BU_CLASSE'}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|update@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||updateClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button button-update">{customi18n key="gestionautonome|gestionautonome.message.modify%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}
    {if $ppo->user->testCredential ("module:classroom|`$ppo->nodeId`|classroom|delete@gestionautonome")}
      <a href="{copixurl dest="gestionautonome||deleteClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('{php}echo addslashes(CopixCustomI18N::get('gestionautonome|gestionautonome.message.confirmdelete%%definite__structure_element%%', array('catalog' => $ppo->vocabularyCatalog->id_vc))){/php}')" class="button button-delete">{customi18n key="gestionautonome|gestionautonome.message.delete%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}</a>
    {/if}

{/if}
