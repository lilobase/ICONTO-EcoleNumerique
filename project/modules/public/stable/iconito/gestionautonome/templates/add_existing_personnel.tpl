<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Personnes disponibles</h2>

{if $ppo->save neq null}
  <p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
    <strong>Personne ajoutée</strong>
  </p>
{/if}

<a href="#" id="filter-displayer">Afficher / Masquer les filtres</a>
<form class="filter-form{if empty($ppo->listFilters)} hidden{/if}" id="persons-list-filter" name="persons" action="{copixurl dest="|filterExistingPersonnel"}" method="post">
 
  <input type="hidden" name="parentId" id="parentId" value="{$ppo->nodeId}" />
  <input type="hidden" name="parentType" id="parentType" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="{$ppo->role}" />
  
  <fieldset class="filter">
    <legend>Filtres</legend>
    
     <div class="field">
      <label for="lastname">Nom :</label>
      <input type="text" class="form" id="lastname" name="lastname" value="{$ppo->listFilters.lastname}" />
      
      <label for="firstname">Prénom :</label>
      <input type="text" class="form" id="firstname" name="firstname" value="{$ppo->listFilters.firstname}" />
      
      <input type="checkbox" name="withAssignment" id="withAssignment" value="1"{if isset ($ppo->listFilters.withAssignment)} checked="checked" {/if} />
      <label for="withAssignment">Avec affectation(s)</label>
      
      <p id="assignment-filters" class="hidden">
        <span id="groupcity-filter">
          {copixzone process=gestionautonome|filterGroupCity selected=$ppo->listFilters.groupcity}
        </span>
        
        <span id="city-filter">
          {if $ppo->listFilters.groupcity}
            {copixzone process=gestionautonome|filterCity selected=$ppo->listFilters.city city_group_id=$ppo->listFilters.groupcity}
          {/if}
        </span>
        
        {if $ppo->role < 4}
          <span id="school-filter">
            {if $ppo->listFilters.city}
              {copixzone process=gestionautonome|filterSchool selected=$ppo->listFilters.school city_id=$ppo->listFilters.city}
            {/if}
          </span>
        {/if}
        
        {if $ppo->role < 3}
          <span id="class-filter">
            {if $ppo->listFilters.school}
              {copixzone process=gestionautonome|filterClass selected=$ppo->listFilters.class school_id=$ppo->listFilters.school}
            {/if}
          </span>
        {/if}
      </p>
    </div>
  </fieldset>

  <ul class="actions">
    <input type="submit" value="Filtrer" class="button" />
  </ul>
</form>

<form name="add_existing_persons" id="add_existing_persons" action="{copixurl dest="|validateExistingPersonsAdd"}" method="POST" enctype="multipart/form-data">
  
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="{$ppo->role}" />
  
  {if $ppo->persons neq null}
    <p class="items-count">{$ppo->persons|@count} personnes</p> 
    <table class="liste">
      <tr>
        <th class="liste_th"></th>
        <th class="liste_th">Nom</th>
        <th class="liste_th">Prénom</th>
        <th class="liste_th">Login</th>
        <th class="liste_th"></th>
      </tr>
      {foreach from=$ppo->persons key=k item=person}
        <tr class="list_line{math equation="x%2" x=$k}">
          <td>
            {if $person->id_sexe eq 1}
              <img src="{copixresource path="../gestionautonome/sexe-m.gif"}" title="Homme" />
            {else}                                                                 
              <img src="{copixresource path="../gestionautonome/sexe-f.gif"}" title="Femme" />
            {/if}
          </td>
          <td>{$person->nom}</td>
          <td>{$person->prenom1}</td>
          <td>{$person->login_dbuser}</td>
          <td>
            <input type="checkbox" class="form" name="personIds[]" value="{$person->numero}" />
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
      Pas de personnes disponibles
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

 	  if (jQuery('#withAssignment:checked').val()) {
 	    
 	    jQuery('#assignment-filters').toggleClass('hidden');
 	  }
 	  
 	  jQuery('#withAssignment').change(function() {

      jQuery('#assignment-filters').toggleClass('hidden');
    });

    jQuery('#cancel').click(function() {
      
      if ({/literal}'{$ppo->nodeType}'{literal} == 'BU_ECOLE') {
        
        document.location.href={/literal}'{copixurl dest=gestionautonome||showTree tab=1 notxml=true}'{literal};
      }
      else {
        
        document.location.href={/literal}'{copixurl dest=gestionautonome||showTree notxml=true}'{literal};
      }
    });

    jQuery('#filter-displayer').click(function() {

      jQuery('#persons-list-filter').toggleClass('hidden');
    });
    
    {/literal}
    {if $ppo->role < 4}
    {literal}
    
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
    
    {/literal}
    {/if}
    {literal}

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