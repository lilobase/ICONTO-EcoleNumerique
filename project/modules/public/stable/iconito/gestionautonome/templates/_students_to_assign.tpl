{if count($ppo->students) > 0}
  {foreach from=$ppo->sourceLevels item=level}
    <input type="checkbox" value="{$level->id_n}" id="level_{$level->id_n}" class="check-students-by-levels" /><label for="level_{$level->id_n}">{$level}</label>
  {/foreach}
  <form action="{copixurl dest="gestionautonome||setStudentsToClass" nodeId=$ppo->sourceClassroom->id destinationClassroomId=$ppo->destinationClassroom->id oldGradeId=$ppo->oldGrade->id_as nextGradeId=$ppo->nextGrade->id_as}" method="post" id="setting-form">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" name="check_all" id="check-all" /></th>
          <th>Ancien niveau</th>
          <th>Nom</th>
          <th>Prénom</th>
          {if $ppo->destinationLevels neq null}
          <th>Nouveau niveau</th>
          {/if}
        </tr>
      </thead>
      <tbody>
        {assign var=index value=1}
        {foreach from=$ppo->students item=student}
          <tr class="{if $index%2 eq 0}odd{else}even{/if}">
            <td class="center"><input type="checkbox" name="ids[]" id="id_{$student->id}" value="{$student->id}" class="level_{$student->niveauId}" /></td>
            <td><label for="id_{$student->id}">{$student->niveau}</label></td>
            <td><label for="id_{$student->id}">{$student->nom}</label></td>
            <td><label for="id_{$student->id}">{$student->prenom}</label></td>
            {if $ppo->destinationLevels neq null}
            <td>
              <select name="level_{$student->id}">
                {foreach from=$ppo->destinationLevels item=level}
                  <option value="{$level->id_n}">{$level}</option>
                {/foreach}
              </select>
            </td>
            {/if}
          </tr>
          {assign var=index value=$index+1}
        {/foreach}
      </tbody>
    </table>
    <div class="submit"><input type="submit" value="Affecter" class="button button-confirm" /></div>
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
   	  
   	  {/literal}{if $ppo->destinationClassroom eq null}{literal}
   	  jQuery("#setting-form").submit(function(){
   	  
   	    alert('Vous devez sélectionner une classe de destination');
   	    return false;
   	  });
   	  {/literal}{/if}{literal}
    });
  //]]> 
  </script>
  {/literal}
{else}
  <p class="center"><strong>Aucun élève à affecter.</strong></p>
{/if}