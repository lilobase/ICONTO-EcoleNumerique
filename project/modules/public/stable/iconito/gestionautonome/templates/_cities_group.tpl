{foreach from=$ppo->citiesGroups key=key item=citiesGroup}
  <li>
    {if in_array($citiesGroup->id_grv, $ppo->nodes)}
      {assign var=is_expanded value=true}
    {else}
      {assign var=is_expanded value=false}
    {/if}
    <a href="#" class="toggle-node{if $is_expanded} expand{/if}"><span>+</span></a>
    <a href="#" id="cities-group-{$citiesGroup->id_grv}" class="node after-expand"><span>{$citiesGroup->nom_groupe|escape}</span></a>
    
    <ul class="tree">
      {if $is_expanded}
        {copixzone process=gestionautonome|city cities_group_id=$citiesGroup->id_grv}
      {/if}
    </ul>
  </li>
{/foreach}
