<h2>Gestion des années scolaires</h2>

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
        <td>{$grade->dateDebut|datei18n}</td>
        <td>{$grade->dateFin|datei18n}</td>
        <td>{if $grade->current eq '1'}Oui{/if}</td>
        <td>
          {if $grade->current neq 1}<a href="{copixurl dest="gestionautonome||setCurrentGrade" gradeId=$grade->id_as}"><img src="{copixresource path="img/gestionautonome/icon_tick.gif"}" title="Indiquer comme l'année courante" /></a>{else}<img src="{copixresource path="img/gestionautonome/blank.gif"}" />{/if}
          <a href="{copixurl dest="gestionautonome||deleteGrade" gradeId=$grade->id_as}" onclick="return confirm('Etes-vous sur de vouloir supprimer cette année scolaire ?')"><img src="{copixresource path="img/gestionautonome/trash.png"}" title="Supprimer cette année scolaire" /></a>
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