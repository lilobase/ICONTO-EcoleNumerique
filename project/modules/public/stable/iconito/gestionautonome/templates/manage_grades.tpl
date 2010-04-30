{if $ppo->grades neq null}
  <table class="liste">
    <tr>
      <th class="liste_th">Identifiant</th>
      <th class="liste_th">Année scolaire</th>
      <th class="liste_th">Date de début</th>
      <th class="liste_th">Date de fin</th>
      <th class="liste_th">Année scolaire actuelle ?</th>
      <th class="liste_th"></th>
    </tr>
    {foreach from=$ppo->grades key=k item=grade}
      <tr class="list_line{math equation="x%2" x=$k}">
        <td>{$grade->id_as}</td>
        <td>{$grade->annee_scolaire}</td>
        <td>{$grade->dateDebut}</td>
        <td>{$grade->dateFin}</td>
        <td>{if $grade->current eq '1'}Oui{/if}</td>
        <td>
          {if $grade->current neq 1}<a href="{copixurl dest="gestionautonome||setCurrentGrade" gradeId=$grade->id_as}"><img src="{copixresource path="../gestionautonome/icon_tick.gif"}" title="Indiquer comme l'année courante" /></a>{else}<img src="{copixresource path="../gestionautonome/blank.gif"}" />{/if}
          <a href="#"><img src="{copixresource path="../gestionautonome/edit_item.png"}" title="Modifier l'année scolaire" /></a>
          <a href="{copixurl dest="gestionautonome||deleteGrade" gradeId=$grade->id_as}"><img src="{copixresource path="../gestionautonome/trash.png"}" title="Supprimer cette année scolaire" /></a>
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