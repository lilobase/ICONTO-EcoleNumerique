<label for="school"> Ecole :</label>
<select class="form" name="school" id="school">
  {html_options values=$ppo->schoolsIds output=$ppo->schoolsNames selected=$ppo->listFilters.school}
</select>



  {literal}
  <script type="text/javascript">
  //<![CDATA[
  
    jQuery.noConflict();
    
    jQuery('#school').change(function(){

      var schoolId = jQuery('#school').val();
    
   	  jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|refreshClassFilter}'{literal},
        global: true,
        type: "GET",
        data: ({schoolId: schoolId, role: {/literal}{$ppo->role}{literal}}),
        success: function(html){
          jQuery('#class-filter').empty();
          jQuery('#class-filter').append(html);
        }
      }).responseText; 
    });
  
  //]]> 
  </script>
  {/literal}
