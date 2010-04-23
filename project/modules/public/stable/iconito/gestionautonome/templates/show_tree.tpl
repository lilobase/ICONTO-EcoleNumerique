<p>Ce module vous permet de gérer les villes, écoles, classes et les personnes associées (agents, personnel de l’education nationale, etc.)</p>

{if $ppo->save neq null}
  <p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
    <strong>Modification effectuée</strong>
  </p>
{/if}

<div id="tree">
  <h4>POSITIONNEZ-VOUS DANS LA STRUCTURE</h4>
  
  {copixzone process=gestionautonome|showTree root=$ppo->root targetId=$ppo->targetId targetType=$ppo->targetType path=$ppo->path}    
  
  <div id="tree-actions"> 
    {if $ppo->targetId neq null && $ppo->targetType neq null}
      {copixzone process=gestionautonome|TreeActions nodeId=$ppo->targetId nodeType=$ppo->targetType}
    {else}
      {copixzone process=gestionautonome|TreeActions nodeId=$ppo->root.id nodeType=$ppo->root.type}
    {/if}                           
  </div>         
</div>

<div id="column-data">
  {if $ppo->targetId neq null && $ppo->targetType neq null}
    {copixzone process=gestionautonome|PersonsData nodeId=$ppo->targetId nodeType=$ppo->targetType}
  {else}
    {copixzone process=gestionautonome|PersonsData nodeId=$ppo->root.id nodeType=$ppo->root.type}
  {/if}
</div>      

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.success').error();
  });
  
//]]> 
</script>
{/literal}