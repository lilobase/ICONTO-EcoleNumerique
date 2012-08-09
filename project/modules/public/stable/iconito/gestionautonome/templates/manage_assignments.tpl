{assign var="school" value=$ppo->nodeInfos.parent.ALL}

<p class="breadcrumbs">{$ppo->breadcrumbs}</p>
<h2>{i18n key="gestionautonome|gestionautonome.message.assignementchange}</h2>

<form action="{copixurl dest="gestionautonome||filterAndDisplayAssignments"}" method="post" id="filter-form">
  <input type="hidden" name="node_id" value="{$ppo->nodeId}" />
  <div id="origin" class="filterClass">
    <h3>{i18n key="gestionautonome|gestionautonome.message.origin}</h3>
    <div class="field" id="origin-grade">
      <label>{i18n key="gestionautonome|gestionautonome.message.schoolyear}</label>
      <select name="origin_grade">
        {foreach from=$ppo->grades item=grade}
          <option value="{$grade->id_as}"{if $ppo->filters.originGrade == $grade->id_as} selected="selected"{/if}>{$grade->anneeScolaire}</option>
        {/foreach}
      </select>
      <input type="submit" value="Rafaîchir" id="refresh-grade" />
    </div>
    
    {if $ppo->user->testCredential ('basic:admin') || $ppo->user->isDirector}
      <div class="field" id="origin-citygroup">
        {copixzone process=gestionautonome|filterGroupCity selected=$ppo->filters.originCityGroup with_label=true name=origin_citygroup with_empty=false}
      </div>
      <div class="field" id="origin-city">
        {copixzone process=gestionautonome|filterCity selected=$ppo->filters.originCity city_group_id=$ppo->filters.originCityGroup name=origin_city with_label=true with_empty=false}
      </div>
      <div class="field" id="origin-school">
        {copixzone process=gestionautonome|filterSchool selected=$ppo->filters.originSchool city_id=$ppo->filters.originCity name=origin_school with_label=true with_empty=false}
      </div>
    {else}
      <div class="field" id="origin-school">
        {customi18n key="gestionautonome|gestionautonome.message.%%Structure%%" catalog=$ppo->vocabularyCatalog->id_vc}
        {$ppo->filters.schoolName}
        <input type="hidden" name="origin_school" value="{$ppo->filters.originSchool}" />
      </div>
    {/if}
    <div class="field" id="origin-class">
      {copixzone process=gestionautonome|filterClass selected=$ppo->filters.originClassroom school_id=$ppo->filters.originSchool with_label=true grade=$ppo->filters.originGrade name=origin_classroom with_empty=true label_empty="Toutes"}
    </div>
    <div class="field" id="origin-level">
      {copixzone process=gestionautonome|filterClassLevel selected=$ppo->filters.originLevel school_id=$ppo->filters.originSchool classroom_id=$ppo->filters.originClassroom with_label=true grade=$ppo->filters.originGrade name=origin_level with_empty=true label_empty="Tous"}
    </div>
    <div class="field" id="origin-usertype">
      <label>{i18n key="gestionautonome|gestionautonome.message.type"}</label>
      <select class="form" name="origin_usertype">
        <option value="USER_ELE" label="Elève"{if $ppo->filters.originUserType eq "USER_ELE"} selected="selected"{/if}>Elève</option>
        <option value="USER_ENS" label="Enseignant"{if $ppo->filters.originUserType eq "USER_ENS"} selected="selected"{/if}>Enseignant</option>
      </select>
    </div>
    <div class="field" id="origin-lastname">
      <label>{i18n key="gestionautonome|gestionautonome.message.lastname"}</label>
      <input type="text" name="origin_lastname" value="{$ppo->filters.originLastname}" />
    </div>
    <div class="field" id="origin-firstname">
      <label>{i18n key="gestionautonome|gestionautonome.message.firstname"}</label>
      <input type="text" name="origin_firstname" value="{$ppo->filters.originFirstname}" />
    </div>
  </div>
  
  <div id="destination" class="filterClass">
    <h3>{i18n key="gestionautonome|gestionautonome.message.destination}</h3>
    <div class="field" id="destination-grade">
      <label>{i18n key="gestionautonome|gestionautonome.message.schoolyear} :</label>
      <select name="destination_grade" id="destination-grade">
        {foreach from=$ppo->grades item=grade}
          {if $grade->id_as >= $ppo->currentGrade->id_as}
            <option value="{$grade->id_as}"{if $ppo->filters.destinationGrade == $grade->id_as} selected="selected"{/if}>{$grade->anneeScolaire}</option>
          {/if}
        {/foreach}
      </select>
      <input type="submit" value="Rafaîchir" id="refresh-grade" />
    </div>
    {if $ppo->user->testCredential ('basic:admin') || $ppo->user->isDirector}
      <div class="field" id="destination-citygroup">
        {copixzone process=gestionautonome|filterGroupCity selected=$ppo->filters.destinationCityGroup with_label=true name=destination_citygroup with_empty=false}
      </div>
      <div class="field" id="destination-city">
        {copixzone process=gestionautonome|filterCity selected=$ppo->filters.destinationCity city_group_id=$ppo->filters.destinationCityGroup name=destination-citygroup with_label=true name=destination_city with_empty=false}
      </div>
      <div class="field" id="destination-school">
        {copixzone process=gestionautonome|filterSchool selected=$ppo->filters.destinationSchool city_id=$ppo->filters.destinationCity name=destination-city with_label=true with_empty=false name=destination_school}
      </div>
    {else}
      <div class="field" id="destination-school">
        {customi18n key="gestionautonome|gestionautonome.message.%%Structure%%" catalog=$ppo->vocabularyCatalog->id_vc}
        {$ppo->filters.schoolName}
        <input type="hidden" name="destination_school" value="{$ppo->filters.destinationSchool}" />
      </div>
    {/if}
    <div class="field" id="destination-class">
      {copixzone process=gestionautonome|filterClass selected=$ppo->filters.destinationClassroom school_id=$ppo->filters.destinationSchool grade=$ppo->filters.destinationGrade name=destination-school with_label=true with_empty=true label_empty="Toutes" name=destination_classroom}
    </div>
    <div class="field" id="destination-level">
      {copixzone process=gestionautonome|filterClassLevel selected=$ppo->filters.destinationLevel school_id=$ppo->filters.destinationSchool classroom_id=$ppo->filters.destinationClassroom with_label=true grade=$ppo->filters.destinationGrade name=destination_level with_empty=true label_empty="Tous"}
    </div>
   </div>
</form>

<div id="assignments">
  {copixzone process=gestionautonome|manageAssignments nodeId=$ppo->nodeId}
</div>

<a href="{copixurl dest=gestionautonome||showTree}" class="button button-back">Retour</a>

{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
    
 	  <!-- On cache le bouton de soumission du formulaire -->
 	  jQuery('#filter-form input[type="submit"]').hide();
 	  
 	  <!-- Modification de l'année scolaire pour la classe d'origine, rafraichissement de la liste des classes -->
 	  jQuery('#origin select[name="origin_grade"]').change(function(){
 	    
 	    jQuery('#origin-class').empty();
      jQuery('#assignments').empty();
      
      if (jQuery('#origin [name="origin_school"]').val()) {
        
        jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshClassFilter}"{literal},
          global: true,
          type: "GET",
          data: ({grade: $(this).val(), school_id: jQuery('#origin [name="origin_school"]').val(), with_label: 1, with_empty: 1, label_empty: "Toutes", name: "origin_classroom"}),
          success: function(html){
            jQuery('#origin-class').append(html);
            jQuery('#origin select[name="origin_classroom"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
      
      return false;
 	  });
 	  
 	  <!-- Modification de l'année scolaire pour la classe de destination, rafraichissement de la liste des classes -->
 	  jQuery('#destination select[name="destination_grade"]').change(function(){
 	    
 	    jQuery('#destination-class').empty();
 	    jQuery('#assignments').empty();
 	      
 	    if (jQuery('#destination [name="destination_school"]').val()) {
      
        jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshClassFilter}"{literal},
          global: true,
          type: "GET",
          data: ({grade: $(this).val(), school_id: jQuery('#destination [name="destination_school"]').val(), with_label: 1, with_empty: 1, label_empty: "Toutes", name: "destination_classroom"}),
          success: function(html){
            jQuery('#destination-class').append(html);
            jQuery('#destination select[name="destination_classroom"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
      
      return false;
 	  });
 	  
 	  <!-- Soumission du formulaire -->
 	  jQuery('#origin select[name="origin_level"], #destination select[name="destination_level"], #origin select[name="origin_usertype"], #origin input[name="origin_lastname"], #origin input[name="origin_firstname"]').live('change', function(){
 	    
 	    jQuery('#filter-form').submit();
 	  });
 	  
 	  <!-- Soumission du formulaire, mise à jour de la liste des élèves -->
 	  jQuery('#filter-form').submit(function(e){
 	    
 	    jQuery('#assignments').empty();
      jQuery('#assignments').html('<p class="center">Chargement en cours...</p>');
      
 	    jQuery.ajax({
        url: {/literal}"{copixurl dest=gestionautonome|default|filterAndDisplayAssignments}"{literal},
        global: true,
        type: "GET",
        data: jQuery('#filter-form').serialize(),
        success: function(list){
          jQuery('#assignments').empty();
          jQuery('#assignments').append(list);
        }
      });
      
 	    return false;
 	  });
 	  
 	  <!-- Modification du groupe de ville d'origine, rafraichissement de la liste des villes -->
 	  jQuery('#origin select[name="origin_groupcity"]').live('change', function(){

      jQuery('#origin-city').empty();
      jQuery('#origin-school').empty();
      jQuery('#origin-class').empty();
      jQuery('#assignments').empty();
      
      var cityGroupId = jQuery('#origin select[name="origin_groupcity"]').val();
      if (cityGroupId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_group_id: cityGroupId, with_label: 1, name: "origin_city", with_empty: 0}),
          success: function(html){

            jQuery('#origin-city').append(html);
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification de la ville d'origine, rafraichissement de la liste des écoles -->
    jQuery('#origin select[name="origin_city"]').live('change', function(){
      
      jQuery('#origin-school').empty();
      jQuery('#origin-class').empty();
      jQuery('#assignments').empty();
      
      var cityId = jQuery('#origin select[name="origin_city"]').val();
      if (cityId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_id: cityId, with_label: 1, with_empty: 0, name: "origin_school"}),
          success: function(html){

            jQuery('#origin-school').append(html);
            jQuery('#origin select[name="origin_school"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification de l'école d'origine, rafraichissement de la liste des classes et des élèves -->
 	  jQuery('#origin select[name="origin_school"]').live('change', function(){
 	    
      jQuery('#origin-class').empty();
      jQuery('#origin-level').empty();
      jQuery('#assignments').empty();
      
      var schoolId = jQuery('#origin select[name="origin_school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
          global: true,
          type: "GET",
          data: ({school_id: schoolId, with_label: 1, grade: jQuery('#origin select[name="originGrade"]').val(), with_empty: 1, label_empty: "Toutes", name: "origin_classroom"}),
          success: function(html){

            jQuery('#origin-class').append(html);
            jQuery('#origin select[name="origin_classroom"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification de la classe d'origine, rafraichissement de la liste des niveaux et des élèves -->
 	  jQuery('#origin select[name="origin_classroom"]').live('change', function(){

      jQuery('#origin-level').empty();
      jQuery('#assignments').empty();
      
      var classroomId = jQuery('#origin select[name="origin_classroom"]').val();
      var schoolId = jQuery('#origin [name="origin_school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassLevelFilter}'{literal},
          global: true,
          type: "GET",
          data: ({classroom_id: classroomId, school_id: schoolId, with_label: 1, grade: jQuery('#origin select[name="originGrade"]').val(), with_empty: 1, label_empty: "Tous", name: "origin_level"}),
          success: function(html){

            jQuery('#origin-level').append(html);
            jQuery('#origin select[name="origin_level"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification du groupe de ville de destination, rafraichissement de la liste des villes -->
    jQuery('#destination select[name="destination_citygroup"]').live('change', function(){

      jQuery('#destination-city').empty();
      jQuery('#destination-school').empty();
      jQuery('#destination-class').empty();
      
      var cityGroupId = jQuery('#destination select[name="destination_citygroup"]').val();
      if (cityGroupId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_group_id: cityGroupId, with_label: 1, name: "destination_city", with_empty: 0}),
          success: function(html){

            jQuery('#destination-city').append(html);
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });

    <!-- Modification de la ville de destination, rafraichissement de la liste des écoles -->
    jQuery('#destination select[name="destination_city"]').live('change', function(){
      
      jQuery('#destination-school').empty();
      jQuery('#destination-class').empty();
      
      var cityId = jQuery('#destination select[name="destination_city"]').val();
      if (cityId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_id: cityId, with_label: 1, with_empty: 0, name: "destination_school"}),
          success: function(html){

            jQuery('#destination-school').append(html);
            jQuery('#destination select[name="destination_school"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification de l'école de destination, rafraichissement de la liste des classes -->
 	  jQuery('#destination select[name="destination_school"]').live('change', function(){
 	    
      jQuery('#destination-class').empty();
      
      var schoolId = jQuery('#destination select[name="destination_school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
          global: true,
          type: "GET",
          data: ({school_id: schoolId, with_label: 1, grade: jQuery('#destination select[name="destination_grade"]').val(), with_empty: 1, label_empty: "Toutes", name: "destination_classroom"}),
          success: function(html){

            jQuery('#destination-class').append(html);
            jQuery('#destination select[name="destination_classroom"]').trigger('change');
          }
        });
      }
      else {
        
        jQuery('#filter-form').submit();
      }
    });
    
    <!-- Modification de la classe de destination, rafraichissement de la liste des niveaux de la classe -->
 	  jQuery('#destination select[name="destination_classroom"]').live('change', function(){
 	    
      jQuery('#destination-level').empty();
      jQuery('#assignments').empty();
      
      var classroomId = jQuery('#destination select[name="destination_classroom"]').val();
      var schoolId = jQuery('#destination [name="destination_school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassLevelFilter}'{literal},
          global: true,
          type: "GET",
          data: ({classroom_id: classroomId, school_id: schoolId, grade: jQuery('#destination select[name="destination_grade"]').val(), with_label: 1, with_empty: 1, label_empty: "Tous", name: "destination_level"}),
          success: function(html){

            jQuery('#destination-level').append(html);
          }
        });
      }
      
      jQuery('#filter-form').submit();
    });
    
    <!-- Modification du niveau de la classe -->
 	  jQuery('#destination select[name="destination_level"]').live('change', function(){
      
      jQuery('#filter-form').submit();
    });  
  });
//]]> 
</script>
{/literal}