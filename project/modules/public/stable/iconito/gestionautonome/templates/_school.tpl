{foreach from=$ppo->schools key=key item=school}
  <li>
    {if in_array($school->numero, $ppo->nodes)}
      {assign var=is_expanded value=true}
    {else}
      {assign var=is_expanded value=false}
    {/if}
    <a href="#" class="toggle-node{if $is_expanded} expand{/if}"><span>+</span></a>
    <a href="#" id="school-{$school->numero}" class="node after-expand"><span>{$school->nom|escape}</span></a>
    
    <ul class="tree">
      {if $is_expanded}
        {copixzone process=gestionautonome|classroom school_id=$school->numero}
      {/if}
    </ul>
  </li>
{/foreach}
