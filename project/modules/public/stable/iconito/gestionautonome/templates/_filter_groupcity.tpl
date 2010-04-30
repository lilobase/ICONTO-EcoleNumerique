<label for="groupcity"> Groupe de ville :</label>
<select class="form" name="groupcity" id="groupcity">
  {html_options values=$ppo->cityGroupsIds output=$ppo->cityGroupsNames selected=$ppo->listFilters.groupcity}
</select>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery('#groupcity').change(function(){

    var cityGroupId = jQuery('#groupcity').val();
 	  
 	  jQuery.ajax({
      url: {/literal}'{copixurl dest=gestionautonome|default|refreshCityFilter}'{literal},
      global: true,
      type: "GET",
      data: ({cityGroupId: cityGroupId, role: {/literal}{$ppo->role}{literal}}),
      success: function(html){
        jQuery('#city-filter').empty();
        jQuery("#city-filter").val(html);
      }
    }).responseText; 
  });
  
//]]> 
</script>
{/literal}