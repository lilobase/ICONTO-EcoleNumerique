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
    {copixzone process=gestionautonome|showTree root=$ppo->root targetId=$ppo->targetId targetType=$ppo->targetType path=$ppo->path grade=$ppo->grade}    
  </ul>
  
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
    {copixzone process=gestionautonome|PersonsData nodeId=$ppo->targetId nodeType=$ppo->targetType tab=$ppo->tab}
  {else}
    {copixzone process=gestionautonome|PersonsData nodeId=$ppo->root.id nodeType=$ppo->root.type}
  {/if}
</div>      

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery('#grade').change(function(){
    
    var grade = jQuery('#grade').val();
    
    jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|refreshTree}'{literal},
      global: true,
      type: "GET",
      data: ({root: {/literal}{$ppo->root}{literal}, grade: grade}),
      success: function(html){
        jQuery('.tree').empty();
        jQuery('.tree').append(html);
      }
    }).responseText;
  });
  
//]]> 
</script>
{/literal}