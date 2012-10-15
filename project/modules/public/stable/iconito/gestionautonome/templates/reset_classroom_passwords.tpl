<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Gestion des mots de passe</h2>

{if not $ppo->error eq null}
	<div class="mesgErrors">
	  <ul>
		    <li>Vous devez sélectionner au moins un compte.</li>
	  </ul>
	</div>
{/if}

<p>Vous pouvez réinitialiser les mots de passe des personnes rattachées à {customi18n key="gestionautonome|gestionautonome.message.%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc} : </p>
<form name="reset_classroom_passwords" action="{copixurl dest="gestionautonome||resetClassroomPasswords"}" method="post">
  <input type="hidden" name="nodeId" value="{$ppo->nodeId}" />
  <table>
    <thead>
      <tr>
        {if $ppo->hasCredentialStudentUpdate}
          <th><input type="checkbox" name="all_students" id="select-all-students" /></th>
          <th><label for="select-all-students">{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</label></th>
        {/if}
        {if $ppo->hasCredentialPersonInChargeUpdate}
          <th><input type="checkbox" name="all_persons_in_charge" id="select-all-persons-in-charge" /></th>
          <th><label for="select-all-persons-in-charge">{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_Responsables%%" catalog=$ppo->vocabularyCatalog->id_vc}</label></th>
        {/if}
        {if $ppo->hasCredentialTeacherUpdate}
          <th><input type="checkbox" name="all_teachers" id="select-all-teachers" /></th>
          <th><label for="select-all-teachers">{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_Persons%%" catalog=$ppo->vocabularyCatalog->id_vc}</label></th>
        {/if}
      </tr>
    </thead>
    <tbody>
      {assign var=index value=1}
      {section name=myLoop loop=$ppo->counter start=0 step=1}
        <tr class="{if $index%2 eq 0}odd{else}even{/if}">
          {if $ppo->hasCredentialStudentUpdate}
          
            {if $ppo->students[$smarty.section.myLoop.index] neq null}
              {assign var=student value=$ppo->students[$smarty.section.myLoop.index]}
              <td class="students-checkboxes center"><input type="checkbox" name="students[]" id="student-{$student->idEleve}" value="{$student->idEleve}" /></td>
              <td><label for="student-{$student->idEleve}">{$student->nom|escape} {$student->prenom1|escape}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {else}
              <td colspan="2">&nbsp;</td>
          {/if}
          {if $ppo->hasCredentialPersonInChargeUpdate}
            {if $ppo->personsInCharge[$smarty.section.myLoop.index] neq null}
              {assign var=personInCharge value=$ppo->personsInCharge[$smarty.section.myLoop.index]}
              <td class="persons-in-charge-checkboxes center"><input type="checkbox" name="personsInCharge[]" id="person-in-charge-{$personInCharge->id}" value="{$personInCharge->id}" /></td>
              <td><label for="person-in-charge-{$personInCharge->id}">{$personInCharge->nom|escape} {$personInCharge->prenom|escape}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {else}
              <td colspan="2">&nbsp;</td>
          {/if}
          {if $ppo->hasCredentialTeacherUpdate && $ppo->teachers[$smarty.section.myLoop.index] neq null}
            {if $ppo->teachers[$smarty.section.myLoop.index] neq null}
              {assign var=teacher value=$ppo->teachers[$smarty.section.myLoop.index]}
              <td class="teachers-checkboxes center"><input type="checkbox" name="teachers[]" id="teacher-{$teacher->numero}" value="{$teacher->numero}" /></td>
              <td><label for="teacher-{$teacher->numero}">{$teacher->nom|escape} {$teacher->prenom1|escape}</label></td>
            {else}
              <td colspan="2">&nbsp;</td>
            {/if}
          {else}
              <td colspan="2">&nbsp;</td>
          {/if}
        </tr>
        {assign var=index value=$index+1}
      {sectionelse}
      <tr><td colspan="{if $ppo->hasCredentialStudentUpdate}2{if $ppo->hasCredentialPersonInChargeUpdate}4{if $ppo->hasCredentialTeacherUpdate}6{/if}{/if}{/if}">Aucune personne n'est rattachée à {customi18n key="gestionautonome|gestionautonome.message.%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}</td></tr>
      {/section}
    </tbody>
  </table>
  
  <div class="submit">
    <input class="button button-cancel" type="button" value="Annuler" id="cancel" />
  	<input class="button button-confirm" type="submit" value="Réinitialiser" id="save"  />
  </div>
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
