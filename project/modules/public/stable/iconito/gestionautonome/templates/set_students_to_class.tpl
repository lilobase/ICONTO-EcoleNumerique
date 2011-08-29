{assign var="school" value=$ppo->nodeInfos.parent.ALL}

<p class="breadcrumbs">{$ppo->breadcrumbs}</p>
<h2>Affectation de classe</h2>

<form action="{copixurl dest="gestionautonome||setStudentsToClass"}" method="get" id="filter-form">
  <div id="fromClass" class="filterClass">
      <h3>Classe d'origine</h3>
      <dl>
        <dt>Année scolaire :</dt>
          <dd>{$ppo->currentGrade->annee_scolaire}</dd>
        <dt>Groupe de ville :</dt>
          <dd>Les villes</dd>
        <dt>Ville :</dt>
          <dd>{$school->vil_nom}</dd>
        <dt>Ecole :</dt>
          <dd>{$school->eco_nom}</dd>
        <dt>Classe (niveau) :</dt>
          <dd>
            <select name="sourceClassroomId" id="source-classroom-id">
              {foreach from=$ppo->sourceClassrooms item=sourceClassroom}
                <option value="{$sourceClassroom->id}"{if $sourceClassroom->id == $ppo->sourceClassroom->id} selected="selected"{/if}>{$sourceClassroom}</option>
              {/foreach}
            </select>
            <input type="submit" value="Filtrer" />
          </dd>
      </dl>
  </div>
  
  <div id="toClass" class="filterClass">
      <h3>Classe de destination</h3>
      <dl>
        <dt>Année scolaire :</dt>
          <dd>
            <select name="gradeId" id="grade-id">
              {foreach from=$ppo->grades item=grade}
                {if $ppo->currentGrade->id_as < $grade->id_as}
                  <option value="{$grade->id_as}"{if $ppo->nextGrade->id_as == $grade->id_as} selected="selected"{/if}>{$grade->annee_scolaire}</option>
                {/if}
              {/foreach}
            </select>
            <input type="submit" value="Rafaîchir" id="refresh-grade" />
          </dd>
        <dt>Groupe de ville :</dt>
          <dd>Les villes</dd>
        <dt>Ville :</dt>
          <dd>{$school->vil_nom}</dd>
        <dt>Ecole :</dt>
          <dd>{$school->eco_nom}</dd>
        <dt>Classe (niveau) :</dt>
          <dd>
            <select name="nodeId" id="destination-classroom-id">
              <option value="">&nbsp;</option>
              {foreach from=$ppo->destinationClassrooms item=destinationClassroom}
                <option value="{$destinationClassroom->id}"{if $destinationClassroom->id == $ppo->nodeId} selected="selected"{/if}>{$destinationClassroom}</option>
              {/foreach}
            </select>
          </dd>
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
    {copixzone process=gestionautonome|studentsToAssign destinationClassroom=$ppo->destinationClassroom sourceClassroom=$ppo->sourceClassroom currentGrade=$ppo->currentGrade nextGrade=$ppo->nextGrade}
</div>

<a href="{copixurl dest=gestionautonome||showTree}" class="button button-back">Retour</a>

{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
 	  jQuery("#filter-form input[type='submit']").hide();
 	  
 	  jQuery("#grade-id").change(function(){
 	    jQuery.ajax({
        url: {/literal}"{copixurl dest=gestionautonome|default|refreshClassroomSelector schoolId=$ppo->sourceClassroom->ecole}"{literal},
        global: true,
        type: "GET",
        data: ({gradeId: $(this).val(), withEmpty: true}),
        success: function(options){
          jQuery("#students-selector").empty();
          jQuery("#destination-classroom-id").empty();
          jQuery("#destination-classroom-id").append(options)
        }
      });
      
      return false;
 	  });
 	  
 	  jQuery("#destination-classroom-id, #source-classroom-id").change(function(){
 	    jQuery("#filter-form").submit();
 	  });
 	  
 	  jQuery("#filter-form").submit(function(e){
 	    jQuery.ajax({
        url: {/literal}"{copixurl dest=gestionautonome|default|refreshStudentsToAssign}"{literal},
        global: true,
        type: "GET",
        data: {destinationClassroomId: jQuery("#destination-classroom-id").val(), sourceClassroomId: jQuery("#source-classroom-id").val(), nextGradeId: jQuery("#grade-id").val(), currentGradeId: "{/literal}{$ppo->currentGrade->id_as}"{literal}},
        success: function(list){
          jQuery("#students-selector").empty();
          jQuery("#students-selector").append(list)
        }
      });
      
 	    return false;
 	  });
  });
//]]> 
</script>
{/literal}