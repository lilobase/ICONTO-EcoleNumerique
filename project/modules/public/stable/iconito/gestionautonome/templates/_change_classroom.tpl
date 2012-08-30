<div id="persons-to-assign">
  {if count($ppo->originAssignments) > 0}
  <ul>
    {foreach from=$ppo->originAssignments item=assignments key=classroomId}
      {foreach from=$assignments item=persons key=levelId}
        <li class="classroom">
        <h3><a href="#" class="classroomClosed">{$ppo->classrooms.$classroomId} {if $levelId}({$ppo->classroomLevels.$levelId}){/if} - <span class="count">{$persons|@count}</span> {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</a></h3>
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
  {foreach from=$ppo->destinationAssignments item=assignments key=classroomId}
    {foreach from=$assignments item=persons key=levelId}
      <li class="classroom" data-classroom-id={$classroomId}{if $levelId} data-classroom-level={$levelId}{/if}>
      <h3><a href="#" class="classroomClosed">{$ppo->classrooms.$classroomId} {if $levelId}({$ppo->classroomLevels.$levelId}){/if} - <span class="count">{$persons|@count}</span> {if $ppo->filters.originUserType eq "USER_ELE"}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{else}{if $persons|@count > 1}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_persons%%" catalog=$ppo->vocabularyCatalog->id_vc}{else}{customi18n key="gestionautonome|gestionautonome.message.%%structure_element_staff_person%%" catalog=$ppo->vocabularyCatalog->id_vc}{/if}{/if}</a></h3>
      <div class="class-box">
        {if count($persons) > 0}
          <ul>
            {foreach from=$persons item=person}
              <li data-user-id={$person->user_id} data-user-type={$person->user_type}>
                {$person->nom} {$person->prenom}
                {if $ppo->filters.originUserType eq "USER_ELE"}
                  <a href=""  class="remove-person"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
                {else}
                  <a href=""  class="remove-person"><img src="{copixurl}themes/default/images/icon-16/action-exit.png" title="{customi18n key="gestionautonome|gestionautonome.message.remove%%definite__structure_element_staff_person%%to%%definite__structure_element%%" catalog=$ppo->vocabularyCatalog->id_vc}" /></a>
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
    
    // Masquer Groupes de villes inutiles
    if (jQuery('#origin-citygroup select option').size() < 2)
        jQuery('#origin-citygroup').hide();
    if (jQuery('#destination-citygroup select option').size() < 2)
        jQuery('#destination-citygroup').hide();

    jQuery('#persons-to-assign a.classroomClosed, #assigned-persons a.classroomClosed').each(function(){
        $(this).parent('h3').next('div.class-box').hide();
    });
    jQuery('#persons-to-assign h3 a, #assigned-persons h3 a').click(function(e){
        if ($(this).hasClass('classroomClosed'))
            $(this).removeClass('classroomClosed').addClass('classroomOpen');
        else
            $(this).removeClass('classroomOpen').addClass('classroomClosed');
        $(this).parent('h3').next('div.class-box').slideToggle();
        e.stopPropagation();
        return false;
    });
    
    jQuery('#assigned-persons .classroom').droppable({
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
    
    jQuery('#assigned-persons').delegate('.remove-person', 'click', function(e) {
      
      var item          = jQuery(this);
      var classroomId   = item.closest('.classroom').data('classroom-id');
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
        e.stopPropagation();
        return false;
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
          data: { classroom_id: classroomId, classroom_level: classroomLevel, user_id: userId, user_type: userType, remove_old_assignment: 1 },
          success: function() {
            
            item.parent().parent().parent().find('.count').html(item.parent().find('li').size()-1);
            item.appendTo(target.find('div'));
            target.find('.count').html(target.find('div li').size());
          }
        });
      }
    };
  });
//]]> 
</script>
{/literal}