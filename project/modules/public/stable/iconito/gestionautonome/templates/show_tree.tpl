<p>Ce module vous permet de gérer les villes, écoles, classes et les personnes associées (agents, personnel de l’education nationale, etc.)</p>

{if $ppo->save neq null}
  <p class="success">Modification effectuée</p>
{/if}

<div id="tree">
  <h4>Positionnez-vous dans la structure</h4>
  
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

