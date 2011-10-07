{assign var="school" value=$ppo->nodeInfos.parent.ALL}

<p class="breadcrumbs">{$ppo->breadcrumbs}</p>
<h2>Affectation de classe</h2>

<form action="{copixurl dest="gestionautonome||setStudentsToClass"}" method="get" id="filter-form">
  <div id="fromClass" class="filterClass">
      <h3>Classe d'origine</h3>
      <dl>
        <dt>Année scolaire :</dt>
          <dd>
            <select name="oldGradeId" id="old-grade-id">
              {foreach from=$ppo->grades item=grade}
                <option value="{$grade->id_as}"{if $ppo->oldGrade->id_as == $grade->id_as} selected="selected"{/if}>{$grade->annee_scolaire}</option>
              {/foreach}
            </select>
            <input type="submit" value="Rafaîchir" id="refresh-grade" />
          </dd>
        <dt>Groupe de ville :</dt>
          <dd id="from-citygroup-filter">{copixzone process=gestionautonome|filterGroupCity selected=$ppo->fromClass.groupcity with_label=false}</dd>
        <dt>Ville :</dt>
          <dd id="from-city-filter">{if $ppo->fromClass.groupcity}{copixzone process=gestionautonome|filterCity selected=$ppo->fromClass.city city_group_id=$ppo->fromClass.groupcity with_label=false}{/if}</dd>
        <dt>Ecole :</dt>
          <dd id="from-school-filter">{if $ppo->fromClass.city}{copixzone process=gestionautonome|filterSchool selected=$ppo->fromClass.school city_id=$ppo->fromClass.city with_label=false}{/if}</dd>
        <dt>Classe (niveau) :</dt>
          <dd id="from-class-filter">{if $ppo->fromClass.school}{copixzone process=gestionautonome|filterClass selected=$ppo->fromClass.class school_id=$ppo->fromClass.school with_label=false grade=$ppo->oldGrade->id_as}{/if}</dd>
      </dl>
  </div>
  
  <div id="toClass" class="filterClass">
      <h3>Classe de destination</h3>
      <dl>
        <dt>Année scolaire :</dt>
          <dd>
            <select name="nextGradeId" id="next-grade-id">
              <option value="">&nbsp;</option>
              {foreach from=$ppo->grades item=grade}
                {if $ppo->currentGrade->id_as <= $grade->id_as}
                  <option value="{$grade->id_as}"{if $ppo->nextGrade->id_as == $grade->id_as} selected="selected"{/if}>{$grade->annee_scolaire}</option>
                {/if}
              {/foreach}
            </select>
            <input type="submit" value="Rafaîchir" id="refresh-grade" />
          </dd>
        <dt>Groupe de ville :</dt>
          <dd id="to-citygroup-filter">
            {if $ppo->toClass.groupcity}
              {copixzone process=gestionautonome|filterGroupCity selected=$ppo->toClass.groupcity with_label=false}
            {else}
              {copixzone process=gestionautonome|filterGroupCity with_label=false}
            {/if}
          </dd>
        <dt>Ville :</dt>
          <dd id="to-city-filter">{if $ppo->toClass.groupcity}{copixzone process=gestionautonome|filterCity selected=$ppo->toClass.city city_group_id=$ppo->toClass.groupcity with_label=false}{/if}</dd>
        <dt>Ecole :</dt>
          <dd id="to-school-filter">{if $ppo->toClass.city}{copixzone process=gestionautonome|filterSchool selected=$ppo->toClass.school city_id=$ppo->toClass.city with_label=false}{/if}</dd>
        <dt>Classe (niveau) :</dt>
          <dd id="to-class-filter">{if $ppo->toClass.school}{copixzone process=gestionautonome|filterClass selected=$ppo->toClass.class school_id=$ppo->toClass.school with_label=false}{/if}</dd>
      </dl>
   </div>
</form>

<div id="students-selector">
  {if $ppo->error}
    <div class="mesgErrors">
      <ul>
    	  <li>{$ppo->error}</li>
      </ul>
    </div>
  {/if}
  {copixzone process=gestionautonome|studentsToAssign destinationClassroom=$ppo->destinationClassroom sourceClassroom=$ppo->sourceClassroom currentGrade=$ppo->currentGrade oldGrade=$ppo->oldGrade nextGrade=$ppo->nextGrade}
</div>

<a href="{copixurl dest=gestionautonome||showTree}" class="button button-back">Retour</a>

{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
 	  jQuery("#filter-form input[type='submit']").hide();
 	  
 	  jQuery("#old-grade-id").change(function(){
 	    
 	    jQuery('#from-class-filter').empty();
      jQuery("#students-selector").empty();
      
      if (jQuery('#fromClass select[name="school"]').val()) {
        
        jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshClassroomSelector}"{literal},
          global: true,
          type: "GET",
          data: ({grade_id: $(this).val(), school_id: jQuery('#fromClass select[name="school"]').val(), with_label: 0}),
          success: function(html){
            jQuery('#from-class-filter').append(html);
          }
        });
      }
      
      return false;
 	  });
 	  
 	  jQuery("#next-grade-id").change(function(){
 	    
 	    jQuery('#to-class-filter').empty();
      
 	    if (!$(this).val()) {
 	      
 	      jQuery('#to-city-filter').empty();
        jQuery('#to-school-filter').empty();
 	    }
 	    else {
 	      
 	      if (jQuery('#toClass select[name="school"]').val()) {
        
          jQuery.ajax({
            url: {/literal}"{copixurl dest=gestionautonome|default|refreshClassroomSelector}"{literal},
            global: true,
            type: "GET",
            data: ({grade_id: $(this).val(), school_id: jQuery('#toClass select[name="school"]').val(), with_label: 0}),
            success: function(html){
              jQuery('#to-class-filter').append(html)
            }
          });
        }
      }
      
      return false;
 	  });
 	  
 	  jQuery('#fromClass select[name="classroom"]').live('change', function(){
 	    
 	    jQuery("#filter-form").submit();
 	  });
 	  
 	  jQuery('#toClass select[name="classroom"]').live('change', function(){
 	    
 	    jQuery("#filter-form").submit();
 	  });
 	  
 	  jQuery("#filter-form").submit(function(e){
 	    
 	    if (jQuery('#fromClass select[name="classroom"]').val()) {
 	      
 	      jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshStudentsToAssign}"{literal},
          global: true,
          type: "GET",
          data: {sourceClassroomId: jQuery('#fromClass select[name="classroom"]').val(), destinationClassroomId: jQuery('#toClass select[name="classroom"]').val(), oldGradeId: jQuery("#old-grade-id").val(), nextGradeId: jQuery("#next-grade-id").val()},
          success: function(list){
            jQuery("#students-selector").empty();
            jQuery("#students-selector").append(list);
          }
        });
 	    }
 	    else {
 	      
 	      jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshStudentsToAssign}"{literal},
          global: true,
          type: "GET",
          data: {sourceClassroomId: jQuery('#fromClass select[name="classroom"]').val(), destinationClassroomId: jQuery('#toClass select[name="classroom"]').val(), oldGradeId: jQuery("#old-grade-id").val(), nextGradeId: jQuery("#next-grade-id").val(), schoolId: jQuery('#fromClass select[name="school"]').val()},
          success: function(list){
            jQuery("#students-selector").empty();
            jQuery("#students-selector").append(list);
          }
        });
 	    }
 	    
      
 	    return false;
 	  });
 	  
 	  jQuery('#fromClass select[name="groupcity"]').live('change', function(){

      jQuery('#from-city-filter').empty();
      jQuery('#from-school-filter').empty();
      jQuery('#from-class-filter').empty();
      jQuery("#students-selector").empty();
      
      var cityGroupId = jQuery('#fromClass select[name="groupcity"]').val();
      if (cityGroupId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_group_id: cityGroupId, with_label: 0}),
          success: function(html){

            jQuery('#from-city-filter').append(html);
          }
        });
      }
    });

    jQuery('#fromClass select[name="city"]').live('change', function(){
      
      jQuery('#from-school-filter').empty();
      jQuery('#from-class-filter').empty();
      jQuery("#students-selector").empty();
      
      var cityId = jQuery('#fromClass select[name="city"]').val();
      if (cityId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_id: cityId, with_label: 0}),
          success: function(html){

            jQuery('#from-school-filter').append(html);
          }
        });
      }
    });
    
 	  jQuery('#fromClass select[name="school"]').live('change', function(){

      jQuery('#from-class-filter').empty();
      jQuery("#students-selector").empty();
      
      var schoolId = jQuery('#fromClass select[name="school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|refreshStudentsToAssign}"{literal},
          global: true,
          type: "GET",
          data: {sourceClassroomId: jQuery('#fromClass select[name="classroom"]').val(), destinationClassroomId: jQuery('#toClass select[name="classroom"]').val(), oldGradeId: jQuery("#old-grade-id").val(), nextGradeId: jQuery("#next-grade-id").val(), schoolId: schoolId},
          success: function(list){
            jQuery("#students-selector").empty();
            jQuery("#students-selector").append(list);
          }
        });
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
          global: true,
          type: "GET",
          data: ({school_id: schoolId, with_label: 0, grade_id: jQuery('#fromClass select[name="oldGradeId"]').val()}),
          success: function(html){

            jQuery('#from-class-filter').append(html);
          }
        });
      }
    });
    
    jQuery('#toClass select[name="groupcity"]').live('change', function(){

      jQuery('#to-city-filter').empty();
      jQuery('#to-school-filter').empty();
      jQuery('#to-class-filter').empty();
      
      var cityGroupId = jQuery('#toClass select[name="groupcity"]').val();
      if (cityGroupId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_group_id: cityGroupId, with_label: 0}),
          success: function(html){

            jQuery('#to-city-filter').append(html);
          }
        });
      }
    });

    jQuery('#toClass select[name="city"]').live('change', function(){
      
      jQuery('#to-school-filter').empty();
      jQuery('#to-class-filter').empty();
      
      var cityId = jQuery('#toClass select[name="city"]').val();
      if (cityId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
          global: true,
          type: "GET",
          data: ({city_id: cityId, with_label: 0}),
          success: function(html){

            jQuery('#to-school-filter').append(html);
          }
        });
      }
    });
    
 	  jQuery('#toClass select[name="school"]').live('change', function(){
 	    
      jQuery('#to-class-filter').empty();
      
      var schoolId = jQuery('#toClass select[name="school"]').val();
      if (schoolId) {
        
        jQuery.ajax({
          url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
          global: true,
          type: "GET",
          data: ({school_id: schoolId, with_label: 0, grade_id: jQuery('#toClass select[name="nextGradeId"]').val()}),
          success: function(html){

            jQuery('#to-class-filter').append(html);
          }
        });
      }
    });
  });
//]]> 
</script>
{/literal}