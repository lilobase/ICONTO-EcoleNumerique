<ul class="tree">
  
  <li {if !in_array (array ($ppo->root.type, $ppo->root.id), $ppo->path) } class="collapsed"{/if}>  
    <a href="#" class="expand" onclick="toggleTreeChildren(this, false);return false;"><span>+</span></a>
    <a href="#" onclick="toggleTreeChildren(this, true);showPersonsData('{$ppo->root.type}', {$ppo->root.id});updateTreeActions('{$ppo->root.type}', {$ppo->root.id});return false;" class="after-expand {if $ppo->root.id == $ppo->targetId && $ppo->root.type == $ppo->targetType}current{/if}"><span>{$ppo->root.nom}</span></a>
    
    <ul class="child">
      {copixzone process="gestionautonome|showTreeChildren" node=$ppo->root targetId=$ppo->targetId targetType=$ppo->targetType path=$ppo->path}
    </ul>                              
  </li>
</ul>

{literal}
<script type="text/javascript">
//<![CDATA[ 

  function toggleTreeChildren(elt, highlight) {

    var li = jQuery(elt).parent();
    var span = jQuery(elt).children();
     
    if (highlight) {
      jQuery('.current').toggleClass('current');
      jQuery(elt).addClass ('current');
    }

    if (li.hasClass('collapsed')) {
      
      li.removeClass('collapsed');
      li.addClass('expanded');
    }
    else {
      
      li.removeClass('expanded');
      li.addClass('collapsed');
    } 
  }
  
  function showPersonsData(type, id) {
         
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|displayPersonsData}'{literal},
      global: true,
      type: "GET",
      data: ({nodeId: id, nodeType: type}),
      success: function(html){
        jQuery('#column-data').empty();
        jQuery("#column-data").append(html);
      }
    }).responseText;
  }
  
  function updateTreeActions(type,id) {
    
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|updateTreeActions}'{literal},
      global: true,
      type: "GET",
      data: ({nodeId: id, nodeType: type}),
      success: function(html){
        jQuery('#tree-actions').empty();
        jQuery("#tree-actions").append(html);
      }
    }).responseText;
  }
//]]>
</script> 
{/literal}