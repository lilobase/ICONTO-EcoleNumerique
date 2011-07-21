<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Re-génération des mots de passe</h2>

<form name="reset_classroom_passwords" action="{copixurl dest="gestionautonome||resetClassroomPasswords"}" method="post">
  <input type="hidden" name="nodeId" value="{$ppo->nodeId}" />
  <table>
    <thead>
      <tr>
        {if $ppo->hasCredentialStudentUpdate}
          <td><input type="checkbox" name="all_students" id="select-all-students" /></td>
          <th><label for="select-all-students">Elèves</label></th>
        {/if}
        {if $ppo->hasCredentialPersonInChargeUpdate}
          <td><input type="checkbox" name="all_persons_in_charge" id="select-all-persons-in-charge" /></td>
          <th><label for="select-all-persons-in-charge">Parents</label></th>
        {/if}
        {if $ppo->hasCredentialTeacherUpdate}
          <td><input type="checkbox" name="all_teachers" id="select-all-teachers" /></td>
          <th><label for="select-all-teachers">Enseignants</label></th>
        {/if}
      </tr>
    </thead>
    <tbody>
      {section name=myLoop loop=$ppo->counter start=0 step=1}
        <tr>
          {if $ppo->hasCredentialStudentUpdate}
          
            {if $ppo->students[$smarty.section.myLoop.index] neq null}
              {assign var=student value=$ppo->students[$smarty.section.myLoop.index]}
              <td class="students-checkboxes"><input type="checkbox" name="students[]" id="student-{$student->idEleve}" value="{$student->idEleve}" /></td>
              <td><label for="student-{$student->idEleve}">{$student->prenom1} {$student->nom}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {/if}
          {if $ppo->hasCredentialPersonInChargeUpdate}
            {if $ppo->personsInCharge[$smarty.section.myLoop.index] neq null}
              {assign var=personInCharge value=$ppo->personsInCharge[$smarty.section.myLoop.index]}
              <td class="persons-in-charge-checkboxes"><input type="checkbox" name="personsInCharge[]" id="person-in-charge-{$personInCharge->id}" value="{$personInCharge->id}" /></td>
              <td><label for="person-in-charge-{$personInCharge->id}">{$personInCharge->prenom} {$personInCharge->nom}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {/if}
          {if $ppo->hasCredentialTeacherUpdate && $ppo->teachers[$smarty.section.myLoop.index] neq null}
            {if $ppo->teachers[$smarty.section.myLoop.index] neq null}
              {assign var=teacher value=$ppo->teachers[$smarty.section.myLoop.index]}
              <td class="teachers-checkboxes"><input type="checkbox" name="teachers[]" id="teacher-{$teacher->numero}" value="{$teacher->numero}" /></td>
              <td><label for="teacher-{$teacher->numero}">{$teacher->prenom1} {$teacher->nom}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {/if}
        </tr>
      {/section}
    </tbody>
  </table>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="button" type="submit" value="Enregistrer" id="save"  /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
    
    jQuery('#cancel').click(function() {
      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
    
    jQuery('#select-all-students').change(function() {
      jQuery('.students-checkboxes input').attr('checked', $(this).is(':checked'));
    });
    
    jQuery('#select-all-persons-in-charge').change(function() {
      jQuery('.persons-in-charge-checkboxes input').attr('checked', $(this).is(':checked'));
    });
    
    jQuery('#select-all-teachers').change(function() {
      jQuery('.teachers-checkboxes input').attr('checked', $(this).is(':checked'));
    });
  });
//]]> 
</script>
{/literal}