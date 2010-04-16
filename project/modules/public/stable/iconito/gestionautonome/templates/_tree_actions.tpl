<h4>Actions sur la structure</h4>

{if $ppo->nodeType == 'BU_GRVILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}">Créer une ville</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_VILLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createSchool" parentId=$ppo->nodeId parentType=$ppo->nodeType}">Créer une école dans cette ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||updateCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}">Modifier la ville</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteCity" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette ville ?')">Supprimer la ville</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_ECOLE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||createClass" parentId=$ppo->nodeId parentType=$ppo->nodeType}">Créer une classe dans cette école</a></li>
    <li><a href="{copixurl dest="gestionautonome||updateSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}">Modifier l'école</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteSchool" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette école ?')">Supprimer l'école</a></li>
  </ul>

{elseif $ppo->nodeType == 'BU_CLASSE'}
  <ul>
    <li><a href="{copixurl dest="gestionautonome||updateClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}">Modifier la classe</a></li>
    <li><a href="{copixurl dest="gestionautonome||deleteClass" nodeId=$ppo->nodeId nodeType=$ppo->nodeType}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette classe ?')">Supprimer la classe</a></li>
  </ul>
{/if}
