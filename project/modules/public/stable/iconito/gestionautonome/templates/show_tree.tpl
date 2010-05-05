<p>Ce module vous permet de gérer les villes, écoles, classes et les personnes associées (agents, personnel de l’education nationale, etc.)</p>

{if $ppo->save neq null}
  <p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
    <strong>Modification effectuée</strong>
  </p>
{/if}

<div id="tree">
  <h4>POSITIONNEZ-VOUS DANS LA STRUCTURE</h4>
  
  <div class="field">
    <label for="grade" class="form_libelle"> Année scolaire :</label>
    <select class="form" name="grade" id="grade">
      {html_options values=$ppo->gradesIds output=$ppo->gradesNames selected=$ppo->grade}
    </select>
  </div>
  
  <ul class="tree">
    {copixzone process=gestionautonome|citiesGroup}
  </ul>
   
   <div id="tree-actions"> 
     {copixzone process=gestionautonome|TreeActions nodeId=$ppo->targetId nodeType=$ppo->targetType}
   </div>
</div>

<div id="column-data">
  {copixzone process=gestionautonome|PersonsData nodeId=$ppo->targetId nodeType=$ppo->targetType tab=$ppo->tab}
</div>      
