<div id="persons-to-assign">
  {if count($ppo->originAssignments) > 0}
  <ul>
    {foreach from=$ppo->originAssignments item=assignments key=levelId}
      {foreach from=$assignments item=persons key=classroomId}
        <li class="classroom" data-classroom-id={$classroomId}{if $levelId} data-classroom-level={$levelId}{/if}>
        {if $levelId}
          {assign var='classroomKey' value=$classroomId|cat:'-'|cat:$levelId}
        {else}
          {assign var='classroomKey' value=$classroomId}
        {/if}
        <h3><a href="#" class="{if !isset($ppo->openedClassrooms.origine.$classroomKey)}classroomClosed{else}classroomOpen{/if}" onclick="toggleClassroomState('{copixurl dest=gestionautonome|default|changeManageAssignmentClassroomState}', this, 'origine');return false;">{$ppo->classrooms.$classroomId} {if $levelId}<span class="level">({$ppo->classroomLevels.$levelId})</span>{/if} <span class="count">- {$persons|@count} {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</span></a></h3>
        <div class="class-box" data-classroom-id={$classroomId}>
          {if count($persons) > 0}
            <ul>
              {foreach from=$persons item=person}
                <li data-user-id={$person->user_id} data-user-type={$person->user_type}>
                  {$person->nom} {$person->prenom}
                </li>
              {/foreach}
            </ul>
          {/if}
        </div>
        </li>
      {/foreach}
    {/foreach}
  </ul>
  {else}
    <h3>
        {if $ppo->filters.originUserType eq "USER_ELE"}
          {customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}
        {else}
          {customi18n key="gestionautonome|gestionautonome.message.no%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}
        {/if}
    </h3>
  {/if}
</div>

<div id="assigned-persons">
  <ul>
  {foreach from=$ppo->destinationAssignments item=assignments key=levelId}
    {foreach from=$assignments item=persons key=classroomId}
      <li class="classroom" data-classroom-id={$classroomId}{if $levelId} data-classroom-level={$levelId}{/if}>
      {if $levelId}
        {assign var='classroomKey' value=$classroomId|cat:'-'|cat:$levelId}
      {else}
        {assign var='classroomKey' value=$classroomId}
      {/if}
      <h3><a href="#" class="{if !isset($ppo->openedClassrooms.destination.$classroomKey)}classroomClosed{else}classroomOpen{/if}" onclick="toggleClassroomState('{copixurl dest=gestionautonome|default|changeManageAssignmentClassroomState}', this, 'destination');return false;">{$ppo->classrooms.$classroomId} {if $levelId}<span class="level">({$ppo->classroomLevels.$levelId})</span>{/if}<span class="count"> - {$persons|@count} {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</span></a></h3>
      <div class="class-box">
        {if count($persons) > 0}
          <ul>
            {foreach from=$persons item=person}
              <li data-user-id={$person->user_id} data-user-type={$person->user_type}>
                {$person->nom} {$person->prenom}
                {if $ppo->filters.originUserType eq "USER_ELE"}
                  <a href="" class="remove-person"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                {else}
                  <a href="" class="remove-person"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_staff_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                {/if}
              </li>
            {/foreach}
          </ul>
        {/if}
      </div>
      </li>
    {/foreach}
  {/foreach}
  </ul>
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
    prepareAssignmentsManagementActions(
      {/literal}"{copixurl dest=gestionautonome|default|changeManageAssignmentClassroomState}"{literal},
      {/literal}"{copixurl dest=gestionautonome|default|removeAssignment}"{literal},
      {/literal}"{copixurl dest=gestionautonome|default|updateAssignment}"{literal}
    );
  });
//]]> 
</script>
{/literal}