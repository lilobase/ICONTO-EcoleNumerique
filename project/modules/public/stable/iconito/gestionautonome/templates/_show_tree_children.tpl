{foreach from=$ppo->child item=item}

  <li id='{$item.type}{$item.id}' class="{if !in_array (array ($item.type, $item.id), $ppo->path)}collapsed{/if}" >
    {if $item.type neq 'BU_CLASSE'}
      <a href="#" class="expand" onclick="toggleTreeChildren(this, false);return false;"><span>[+]</span></a>             
      <a href="#" onclick="toggleTreeChildren(this, true);showPersonsData('{$item.type}', {$item.id});updateTreeActions('{$item.type}', {$item.id});return false;" class="after-expand {if $item.id == $ppo->targetId && $item.type == $ppo->targetType}current{/if}"><span>{$item.nom}</span></a>
    {else}
    <a href="#" onclick="toggleTreeChildren(this, true);showPersonsData('{$item.type}', {$item.id});updateTreeActions('{$item.type}', {$item.id});return false;" class="{if $item.id == $ppo->targetId && $item.type == $ppo->targetType}current{/if}"><span>{$item.nom}</span></a>
    {/if}
    
    <ul class="child">
      {copixzone process=gestionautonome|ShowTreeChildren node=$item targetId=$ppo->targetId targetType=$ppo->targetType path=$ppo->path grade=$ppo->grade}
    </ul>
  </li> 
{/foreach}