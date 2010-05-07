<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Elèves disponibles</h2>

{if $ppo->save neq null}
  <p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
    <strong>Elève ajouté</strong>
  </p>
{/if}

<a href="#" id="filter-displayer">Afficher / Masquer les filtres</a>

<form class="filter-form{if empty($ppo->listFilters)} hidden{/if}" id="students-list-filter" name="students" action="{copixurl dest="|filterExistingStudents"}" method="post">
 
  <input type="hidden" name="parentId" id="parentId" value="{$ppo->nodeId}" />
  <input type="hidden" name="parentType" id="parentType" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="0" />
  
  <fieldset class="filter">
    <legend>Filtres</legend>
    
     <div class="field">
      <label for="lastname">Nom :</label>
      <input type="text" class="form" id="lastname" name="lastname" value="{$ppo->listFilters.lastname}" />
      
      <label for="firstname">Prénom :</label>
      <input type="text" class="form" id="firstname" name="firstname" value="{$ppo->listFilters.firstname}" />
      
      <input type="checkbox" name="withAssignment" id="withAssignment" value="1"{if isset ($ppo->listFilters.withAssignment)} checked="checked" {/if} />
      <label for="withAssignment">Avec affectations</label>
      
      <p id="assignment-filters">
        <span id="groupcity-filter">
          {copixzone process=gestionautonome|filterGroupCity selected=$ppo->listFilters.groupcity}
        </span>
      
        <span id="city-filter">
          {if $ppo->listFilters.groupcity}
            {copixzone process=gestionautonome|filterCity selected=$ppo->listFilters.city city_group_id=$ppo->listFilters.groupcity}
          {/if}
        </span>

        <span id="school-filter">
          {if $ppo->listFilters.city}
            {copixzone process=gestionautonome|filterSchool selected=$ppo->listFilters.school city_id=$ppo->listFilters.city}
          {/if}
        </span>
        
        <span id="class-filter">
          {if $ppo->listFilters.school}
            {copixzone process=gestionautonome|filterClass selected=$ppo->listFilters.class school_id=$ppo->listFilters.school}
          {/if}
        </span>
      </p>
    </div>
  </fieldset>

  <ul class="actions">
    <input type="submit" value="Filtrer" class="button" />
  </ul>
</form>

<form name="add_existing_students" id="add_existing_students" action="{copixurl dest="|validateExistingStudentsAdd"}" method="POST" enctype="multipart/form-data">
  
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="0" />
  
  {if $ppo->students neq null}
    <p class="items-count">{$ppo->students|@count} élèves</p> 
    <table class="liste">
      <tr>
        <th class="liste_th"></th>
        <th class="liste_th">Nom</th>
        <th class="liste_th">Prénom</th>
        <th class="liste_th">Login</th>
        <th class="liste_th">Niveau</th>
        <th class="liste_th"></th>
      </tr>
      {foreach from=$ppo->students key=k item=student}
        <tr class="list_line{math equation="x%2" x=$k}">
          <td>
            {if $student->id_sexe eq 0}
              <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Garçon" />
            {else}                                                                 
              <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Fille" />
            {/if}
          </td>
          <td>{$student->nom}</td>
          <td>{$student->prenom1}</td>
          <td>{$student->login_dbuser}</td>
          <td>
            <select class="form" name="level-{$student->idEleve}">
              {html_options values=$ppo->levelIds output=$ppo->levelNames}
        	  </select>
          </td>
          <td>
            <input type="checkbox" class="form" name="studentIds[]" value="{$student->idEleve}" />
          </td>
        </tr>
      {/foreach}
      <tr class="liste_footer">
    		<td colspan="5"></td>
    	</tr>
    </table>
    
    <ul class="actions">
      <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
    	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
    </ul>
  {else} 
    <p class="items-count">
      Pas d'élèves disponibles
    </p>
    
    <ul class="actions">
      <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
    </ul>
  {/if}
</form> 

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button(); 

    jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });

    jQuery('#filter-displayer').click(function() {

      jQuery('#students-list-filter').toggleClass('hidden');
    });
    
    jQuery('#school').live('change', function(){

      jQuery('#class-filter').empty();
      
      var schoolId = jQuery('#school').val();
      if (schoolId != '') {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
          global: true,
          type: "GET",
          data: ({school_id: schoolId}),
          success: function(html){

            jQuery('#class-filter').append(html);
          }
        });
      }
    });

    jQuery('#groupcity').live('change', function(){

      jQuery('#city-filter').empty();
      jQuery('#school-filter').empty();
      jQuery('#class-filter').empty();
      
      var cityGroupId = jQuery('#groupcity').val();
      if (cityGroupId != '') {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_group_id: cityGroupId}),
          success: function(html){

            jQuery("#city-filter").append(html);
          }
        });
      }
    });

    jQuery('#city').live('change', function(){
      
      jQuery('#school-filter').empty();
      jQuery('#class-filter').empty();
      
      var cityId = jQuery('#city').val();
      if (cityId != '') {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_id: cityId}),
          success: function(html){

            jQuery('#school-filter').append(html);
          }
        });
      }
    });
  });
//]]> 
</script>
{/literal}