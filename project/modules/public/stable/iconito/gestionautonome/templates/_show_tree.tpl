<ul class="tree">
  
  <li {if !in_array (array ($ppo->root.type, $ppo->root.id), $ppo->path) } class="collapsed"{/if}>  
    <a href="#" onclick="toggleTreeChildren(this);return false;"><span>[+]</span></a>
    <a href="#" onclick="showPersonsData('{$ppo->root.type}', {$ppo->root.id});updateTreeActions('{$ppo->root.type}', {$ppo->root.id});return false;"><span>{$ppo->root.nom}</span></a>
    
    <ul class="child">
      {copixzone process="gestionautonome|showTreeChildren" node=$ppo->root targetId=$ppo->targetId targetType=$ppo->targetType path=$ppo->path}
    </ul>                              
  </li>
</ul>

{literal}
<script type="text/javascript">
//<![CDATA[
  function toggleTreeChildren(elt) {

    var li = $(elt).parent();
    var span = $(elt).children();
    
    if (li.hasClass('collapsed')) {
      
      li.removeClass('collapsed');
      li.addClass('expanded');
      span.text('[-]');
    }
    else {
      
      li.removeClass('expanded');
      li.addClass('collapsed');
      span.text('[+]');
    } 
  }
  
  function showPersonsData(type, id) {
         
    $.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|displayPersonsData}'{literal},
      global: true,
      type: "GET",
      data: ({nodeId: id, nodeType: type}),
      success: function(html){
        $('#column-data').empty();
        $("#column-data").append(html);
      }
    }).responseText;
  }
  
  function updateTreeActions(type,id) {
    
    $.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|updateTreeActions}'{literal},
      global: true,
      type: "GET",
      data: ({nodeId: id, nodeType: type}),
      success: function(html){
        $('#tree-actions').empty();
        $("#tree-actions").append(html);
      }
    }).responseText;
  }
//]]>
</script> 
{/literal}