{foreach from=$ppo->cities key=key item=city}
  <li>
    {if in_array($city->id_vi, $ppo->nodes)}
      {assign var=is_expanded value=true}
    {else}
      {assign var=is_expanded value=false}
    {/if}
    <a href="#" class="toggle-node{if $is_expanded} expand{/if}"><span>+</span></a>
    <a href="#" id="city-{$city->id_vi}" class="node after-expand"><span>{$city->nom|escape}</span></a>
    
    <ul class="tree">
      {if $is_expanded}
        {copixzone process=gestionautonome|school city_id=$city->id_vi}
      {/if}
    </ul>
  </li>
{/foreach}

