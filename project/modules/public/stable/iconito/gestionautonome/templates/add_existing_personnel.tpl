<p class="breadcrumbs">{$ppo->breadcrumbs}</p> 

<h2>Personnes disponibles</h2>

{if $ppo->save neq null}
  <p class="mesgSuccess">Personne ajoutée</p>
{/if}

<a href="#" id="filter-displayer">Afficher / Masquer les filtres</a>
<form class="filter-form{if empty($ppo->listFilters)} hidden{/if}" id="persons-list-filter" name="persons" action="{copixurl dest="|filterExistingPersonnel"}" method="post">
 
  <input type="hidden" name="parentId" id="parentId" value="{$ppo->nodeId}" />
  <input type="hidden" name="parentType" id="parentType" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="{$ppo->role}" />
  
  <fieldset>
    <legend>Filtres</legend>
    
     <div class="field">
      <label for="lastname">Nom :</label>
      <input type="text" class="form" id="lastname" name="lastname" value="{$ppo->listFilters.lastname|escape}" />
      
      <label for="firstname">Prénom :</label>
      <input type="text" class="form" id="firstname" name="firstname" value="{$ppo->listFilters.firstname|escape}" />
      
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

  <div class="submit">
    <input type="submit" value="Filtrer" class="button button-search" />
  </div>
</form>

<form name="add_existing_persons" id="add_existing_persons" action="{copixurl dest="|validateExistingPersonsAdd"}" method="POST" enctype="multipart/form-data">
  
  <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
  <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
  <input type="hidden" name="role" id="role" value="{$ppo->role}" />
  
  {if $ppo->persons neq null}
    <p class="items-count">{$ppo->persons|@count} personnes</p> 
    <table>
      <tr>
        <th>Sexe</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Identifiant</th>
        <th>Actions</th>
      </tr>
      {foreach from=$ppo->persons key=k item=person}
        <tr class="{if $k%2 eq 0}even{else}odd{/if}">
          <td class="center">
              {if $person->id_sexe eq 1}
                  <img src="{copixurl}themes/default/images/icon-16/user-male.png" title="Homme" alt="Homme" />
              {else}                                                                 
                  <img src="{copixurl}themes/default/images/icon-16/user-female.png" title="Femme" alt="Femme" />
              {/if}
          </td>
          <td><label for="person{$person->numero}">{$person->nom|escape}</label></td>
          <td><label for="person{$person->numero}">{$person->prenom1|escape}</label></td>
          <td>{$person->login_dbuser}</td>
          <td class="actions">
            <input type="checkbox" class="form" id="person{$person->numero}" name="personIds[]" value="{$person->numero}" />
          </td>
        </tr>
      {/foreach}
      <tr class="liste_footer">
    		<td colspan="5"></td>
    	</tr>
    </table>
    
    <div class="submit">
        <a href="{if $ppo->nodeType eq 'BU_ECOLE'}{copixurl dest=gestionautonome||showTree tab=1 notxml=true}{else}{copixurl dest=gestionautonome||showTree notxml=true}{/if}" class="button button-cancel">Annuler</a>
    	<input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
    </div>
  {else} 
    <p class="items-count">
      Pas de personnes disponibles
    </p>
    
    <div class="submit">
        <a href="{if $ppo->nodeType eq 'BU_ECOLE'}{copixurl dest=gestionautonome||showTree tab=1 notxml=true}{else}{copixurl dest=gestionautonome||showTree notxml=true}{/if}" class="button button-cancel">Annuler</a>
    </div>
  {/if}
</form> 

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	
 	  if (jQuery('#withAssignment:checked').val()) {
 	    
 	    jQuery('#assignment-filters').toggleClass('hidden');
 	  }
 	  
 	  jQuery('#withAssignment').change(function() {

      jQuery('#assignment-filters').toggleClass('hidden');
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
