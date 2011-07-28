{if count($ppo->students) > 0}
  {foreach from=$ppo->sourceLevels item=level}
    <input type="checkbox" value="{$level->id_n}" id="level_{$level->id_n}" class="check-students-by-levels" /><label for="level_{$level->id_n}">{$level}</label>
  {/foreach}
  <form action="{copixurl dest="gestionautonome||setStudentsToClass" nodeId=$ppo->destinationClassroom->id sourceClassroomId=$ppo->sourceClassroom->id gradeId=$ppo->previousGrade->id_as}" method="post" id="setting-form">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" name="check_all" id="check-all" /></th>
          <th>Prénom</th>
          <th>Nom</th>
          <th>Ancien niveau</th>
          <th>Nouveau niveau</th>
        </tr>
      </thead>
      <tbody>
        {foreach from=$ppo->students item=student}
          <tr>
            <td><input type="checkbox" name="ids[]" id="id_{$student->id}" value="{$student->id}" class="level_{$student->niveauId}" /></td>
            <td><label for="id_{$student->id}">{$student->prenom}</label></td>
            <td><label for="id_{$student->id}">{$student->nom}</label></td>
            <td>{$student->niveau}</td>
            <td>
              <select name="level_{$student->id}">
                {foreach from=$ppo->destinationLevels item=level}
                  <option value="{$level->id_n}">{$level}</option>
                {/foreach}
              </select>
            </td>
          </tr>
        {/foreach}
      </tbody>
    </table>
    <input type="submit" value="Affecter" />
  </form>
  
  {literal}
  <script type="text/javascript">
  //<![CDATA[
    jQuery(document).ready(function(){
   	  jQuery("#check-all").click(function(){
   	    if ($(this).is(":checked")){
   	      jQuery("input").attr("checked", "checked");
   	    }
   	    else{
   	      jQuery("input").removeAttr("checked");
   	    }
   	  });
   	  
   	  jQuery("input.check-students-by-levels").click(function(){
   	    if ($(this).is(":checked")){
   	      jQuery("input."+$(this).attr("id")).attr("checked", "checked");
   	    }
   	    else{
   	      jQuery("input."+$(this).attr("id")).removeAttr("checked");
   	    }
   	    controlMainCheckboxState();
   	  });
   	  
   	  jQuery("input[name='ids[]']").click(function(){
   	    var myclass = $(this).attr("class");
   	    if (jQuery("input."+myclass).size() == jQuery("input."+myclass+":checked").size()){
   	      jQuery("#"+myclass).attr("checked", "checked");
   	    }
   	    else{
   	      jQuery("#"+myclass).removeAttr("checked");
   	    }
   	    controlMainCheckboxState();
   	  });
   	  
   	  function controlMainCheckboxState(){
   	    if (jQuery("input[name='ids[]']").size() == jQuery("input[name='ids[]']:checked").size()){
   	      jQuery("#check-all").attr("checked", "checked");
   	    }
   	    else{
   	      jQuery("#check-all").removeAttr("checked");
   	    }
   	  }
    });
  //]]> 
  </script>
  {/literal}
{else}
  <p>Aucun élève à affecter.</p>
{/if}