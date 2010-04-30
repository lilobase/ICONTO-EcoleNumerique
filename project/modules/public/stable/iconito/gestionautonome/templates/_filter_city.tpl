<label for="city"> Ville :</label>
<select class="form" name="city" id="city">
  {html_options values=$ppo->citiesIds output=$ppo->citiesNames selected=$ppo->listFilters.city}
</select>

{if $ppo->role < 4}

  {literal}
  <script type="text/javascript">
  //<![CDATA[
  
    jQuery.noConflict();

    jQuery('#city').change(function(){

      var cityId = jQuery('#city').val();
    
   	  jQuery.ajax({
        url: {/literal}'{copixurl dest=gestionautonome|default|refreshSchoolFilter}'{literal},
        global: true,
        type: "GET",
        data: ({cityId: cityId, role: {/literal}{$ppo->role}{literal}}),
        success: function(html){
          jQuery('#school-filter').empty();
          jQuery('#school-filter').append(html);
        }
      }).responseText; 
    });
  
  //]]> 
  </script>
  {/literal}
{/if}