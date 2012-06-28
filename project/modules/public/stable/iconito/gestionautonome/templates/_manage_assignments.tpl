<div id="persons-to-assign">
  {if count($ppo->originAssignments) > 0}
    {foreach from=$ppo->originAssignments item=assignments key=classroomId}
      {foreach from=$assignments item=persons key=levelId}
        <h3>{$ppo->classrooms.$classroomId} {if $levelId}({$ppo->classroomLevels.$levelId}){/if} - {$persons|@count} {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</h3>
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
      {/foreach}
    {/foreach}
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
  {if count($ppo->destinationAssignments) > 0}
    {foreach from=$ppo->destinationAssignments item=assignments key=classroomId}
      {foreach from=$assignments item=persons key=levelId}
        <h3>{$ppo->classrooms.$classroomId} {if $levelId}({$ppo->classroomLevels.$levelId}){/if} - {$persons|@count} {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</h3>
        <div class="class-box" data-classroom-id={$classroomId}{if $levelId} data-classroom-level={$levelId}{/if}>
          {if count($persons) > 0}
            <ul>
              {foreach from=$persons item=person}
                <li data-user-id={$person->user_id} data-user-type={$person->user_type}>
                  {$person->nom} {$person->prenom}
                  {if $ppo->filters.originUserType eq "USER_ELE"}
                    <img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" class="remove-person" />
                  {else}
                    <img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_staff_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" class="remove-person" />
                  {/if}
                </li>
              {/foreach}
            </ul>
          {/if}
        </div>
      {/foreach}
    {/foreach}
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

{literal}
<script type="text/javascript">
//<![CDATA[
  jQuery(document).ready(function(){
    
    jQuery('#persons-to-assign, #assigned-persons').accordion({
      autoHeight: false,
      navigation: true
    });
    
    jQuery('#assigned-persons .class-box').droppable({
      activeClass: "ui-state-default",
      hoverClass: "ui-state-hover",
      accept: ":not(.ui-sortable-helper)",
      drop: function (event, ui) {
        
        var item = jQuery(ui.draggable);
        
        if (item.is('li')) {
          
          var target = jQuery(event.target);

          reassignePerson(item, target, true);
        }
        else {
          
          var target = jQuery(event.target);
          var allLi = item.next('.class-box').find('li');
          
          jQuery.each(allLi, function(index) {
            
            var item = jQuery(this);
            var reload = index == (allLi.length - 1);
            
            reassignePerson(item, target, reload);
          });
        }
      }
    });
    
    jQuery('#persons-to-assign .class-box li').draggable({
      revert: "invalid",
      helper: "clone",
      cursor: "move"
    });
    
    jQuery('#persons-to-assign h3').draggable({
      revert: "invalid",
      helper: "clone",
      cursor: "move"
    });
    
    jQuery('#assigned-persons').delegate('.remove-person', 'click', function() {
      
      var item          = jQuery(this);
      var classroomId   = item.closest('.class-box').data('classroom-id');
      var userId        = item.parent('li').data('user-id');
      var userType      = item.parent('li').data('user-type');
      var grade         = jQuery('#destination select[name="destination_grade"]').val();
      
      jQuery.ajax({
        url: {/literal}"{copixurl dest=gestionautonome|default|removeAssignment}"{literal},
        global: true,
        type: "GET",
        data: { classroom_id: classroomId, user_id: userId, user_type: userType, grade: grade },
        success: function(){
          
          jQuery('#filter-form').submit();
        }
      });
    });
    
    function reassignePerson(item, target, reload) {
      
      if (target.find("li[data-user-id='"+item.data('user-id')+"'][data-user-type='"+item.data('user-type')+"']").length == 0) {

        var classroomId    = target.data('classroom-id');
        var classroomLevel = target.data('classroom-level');
        var userId         = item.data('user-id');
        var userType       = item.data('user-type');

        jQuery.ajax({
          url: {/literal}"{copixurl dest=gestionautonome|default|updateAssignment}"{literal},
          global: true,
          type: "GET",
          data: { classroom_id: classroomId, classroom_level: classroomLevel, user_id: userId, user_type: userType },
          success: function() {
            
            if (reload) {
              
              jQuery('#filter-form').submit();
            }
          }
        });
      }
    };
  });
//]]> 
</script>
{/literal}