{if $ppo->grades neq null}
  <table class="liste">
    <tr>
      <th class="liste_th">id</th>
      <th class="liste_th">année scolaire</th>
      <th class="liste_th">date début</th>
      <th class="liste_th">date fin</th>
      <th class="liste_th">actuelle ?</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->grades item=grade}
      <tr>
        <td>{$grade->id_as}</td>
        <td>{$grade->annee_scolaire}</td>
        <td>{$grade->dateDebut}</td>
        <td>{$grade->dateFin}</td>
        <td>{$grade->current}</td>
        <td>
          {if $grade->current neq 1}<a href="{copixurl dest="gestionautonome||setCurrentGrade" gradeId=$grade->id_as}">Passer en année courante</a> -{/if}
          Modifier -
          Supprimer
        </td>
      </tr>
    {/foreach}
  </table>
{/if}

<ul class="actions">
  <li><a href="{copixurl dest="gestionautonome||createGrade"}" class="button">Créer une nouvelle année scolaire</a></li>
</ul>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
//]]> 
</script>
{/literal}