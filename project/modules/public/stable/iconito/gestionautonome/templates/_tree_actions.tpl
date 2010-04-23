<h4>ACTIONS SUR LA STRUCTURE</h4>

{if $ppo->nodeType == 'BU_GRVILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Créer une ville</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_VILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createSchool" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button">Créer une école dans cette ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||updateCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier la ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette ville ?')" class="button">Supprimer la ville</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_ECOLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createClass" parentId=$ppo->nodeId parentType=$ppo->nodeType}" class="button">Créer une classe dans cette école</a></li>
    <li><a href="{copixurl dest="gestionautonome||updateSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier l'école</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette école ?')" class="button">Supprimer l'école</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_CLASSE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||updateClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" class="button">Modifier la classe</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette classe ?')" class="button">Supprimer la classe</a></li>
  </ul>
{/if}

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
//]]> 
</script>
{/literal}