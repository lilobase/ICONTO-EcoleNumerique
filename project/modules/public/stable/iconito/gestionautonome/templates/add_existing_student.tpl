<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Elèves disponibles</h2>

{if $ppo->save neq null}
  <p class="mesgSuccess">Elève ajouté</p>
{/if}

<a href="#" id="filter-displayer">Afficher / Masquer les filtres</a>

<form class="filter-form{if empty($ppo->listFilters)} hidden{/if}" id="students-list-filter" name="students" action="{copixurl dest="|filterExistingStudents"}" method="post">
 
  <input type="hidden" name="parentId" id="parentId" value="{$ppo->nodeId}" />
  <input type="hidden" name="parentType" id="parentType" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="0" />
  
  <fieldset>
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

  <div class="submit">
    <input type="submit" value="Filtrer" class="button button-search" />
  </div>
</form>

<form name="add_existing_students" id="add_existing_students" action="{copixurl dest="|validateExistingStudentsAdd"}" method="POST" enctype="multipart/form-data">
  
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="0" />
  
  {if $ppo->students neq null}
    <p class="items-count">{$ppo->students|@count} élèves</p> 
    <table>
      <tr>
        <th>Sexe</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Identifiant</th>
        <th>Niveau</th>
        <th>Actions</th>
      </tr>
      {foreach from=$ppo->students key=k item=student}
        <tr class="{if $k%2 eq 0}even{else}odd{/if}">
          <td class="sexe">
            {if $student->id_sexe eq 1}
                <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Garçon" alt="Garçon" />
            {else}                                                                 
                <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Fille" alt="Fille" />
            {/if}
          </td>
          <td>{$student->nom}</td>
          <td>{$student->prenom1}</td>
          <td>{$student->login_dbuser}</td>
          <td class="center">
            <select class="form" name="level-{$student->idEleve}">
              {html_options values=$ppo->levelIds output=$ppo->levelNames}
        	  </select>
          </td>
          <td class="actions">
            <input type="checkbox" class="form" name="studentIds[]" value="{$student->idEleve}" />
          </td>
        </tr>
      {/foreach}
    </table>
    
    <div class="submit">
        <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
    	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
    </div>
  {else} 
    <p class="items-count">
      Pas d'élèves disponibles
    </p>
    
    <div class="submit">
        <a href="{copixurl dest=gestionautonome||showTree}" class="button button-cancel">Annuler</a>
    </div>
  {/if}
</form> 

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
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