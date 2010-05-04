<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Liste des élèves</h2>

{if $ppo->students neq null}
  <form name="change_students_affect" id="change_students_affect" action="{copixurl dest="|validateChangeStudentsAffect"}" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    
    <table class="liste">
      <tr>
        <th class="liste_th"></th>
        <th class="liste_th">Compte</th> 
        <th class="liste_th">Nom</th>
        <th class="liste_th">Prénom</th> 
        <th class="liste_th">Dernière affectation</th>
        <th class="liste_th">Nouvelle affectation</th>
      </tr>
      {foreach from=$ppo->students key=k item=student}
        <tr class="list_line{math equation="x%2" x=$k}">
          <input type="hidden" name="students[]" value="{$student->idEleve}" />
          <td>
            {if $student->id_sexe eq 0}
              <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
            {/if}
          </td>
          <td>{$student->login}</td>
          <td>{$student->nom}</td>
          <td>{$student->prenom1}</td>
          <td>{$student->niveau_court} - {$student->nom_classe}</td>
          <td>
            <select class="form" name="newAffects[]">
              <option value="">-- pas de changement --</option>
              {html_options values=$ppo->levelIds output=$ppo->levelNames selected=$ppo->level}
        	  </select>
          </td>
          <td></td>
        </tr>
      {/foreach}
      <tr class="liste_footer">
    		<td colspan="8"></td>
    	</tr>
    </table>

    <p>Raccourci pour positionner une nouvelle affectation pour tous les élèves :</p>
    <select class="form" name="allAffect">
      <option value="">-- pas de changement --</option>
      {html_options values=$ppo->levelIds output=$ppo->levelNames}
	  </select>
	  <input class="button" type="button" value="Appliquer" id="allAffect" /> 
    
    <ul class="actions">
      <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
    	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer les nouvelles affectations" /></li>
    </ul>
  </form>
{else}
  <i>Aucun élève</i>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  </ul>                 
{/if}

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
  
  jQuery('#allAffect').click(function () {
    
    var valeur = jQuery('[name|=allAffect] option:selected').val();
    
    jQuery('[name|=newAffects[]]').each(function () {
      
      jQuery(this).val(valeur);     

    });
  });
//]]> 
</script>
{/literal}