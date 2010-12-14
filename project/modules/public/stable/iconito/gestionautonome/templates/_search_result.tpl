<p class="search-result-count">
  {if $ppo->total == 0}
    Aucun résultat trouvé !
  {elseif $ppo->total == 1}
    1 résultat trouvé !
  {elseif $ppo->total == 1}
    {$ppo->total} résultats trouvés !
  {/if}
</p>

{copixzone process=gestionautonome|citiesGroup}

{literal}
  <script type="text/javascript">
  //<![CDATA[
  
    $(document).ready(function(){

      {/literal}
        {foreach from=$ppo->matchedNodes.cities_groups item=cities_group_id}
          {literal}
            jQuery('#cities-group-'+{/literal}{$cities_group_id}{literal}).addClass('current');
          {/literal}
        {/foreach}
        
        {foreach from=$ppo->matchedNodes.cities item=city_id}
          {literal}
            jQuery('#city-'+{/literal}{$city_id}{literal}).addClass('current');
          {/literal}
        {/foreach}
        
        {foreach from=$ppo->matchedNodes.schools item=school_id}
          {literal}
            jQuery('#school-'+{/literal}{$school_id}{literal}).addClass('current');
          {/literal}
        {/foreach}
        
        {foreach from=$ppo->matchedNodes.classrooms item=classroom_id}
          {literal}
            jQuery('#classroom-'+{/literal}{$classroom_id}{literal}).addClass('current');
          {/literal}
        {/foreach}
      {literal}
    });

  //]]>
  </script> 
{/literal}