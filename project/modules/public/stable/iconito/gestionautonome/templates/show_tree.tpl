{if $ppo->save neq null}
  <p class="mesgSuccess">Structure mise à jour</p>
{/if}

<div id="tree">
  <h2>Sélectionnez une structure</h2>
  
  <div class="field">
    <label for="grade" class="form_libelle"> Année scolaire :</label>
    <select class="form" name="grade" id="grade">
      {html_options values=$ppo->gradesIds output=$ppo->gradesNames selected=$ppo->grade}
    </select>
    <br />
    <form name="search_form" id="search-form">
      <label for="search-input" class="form_libelle">Recherche par nom :</label>
      <input type="text" class="form" name="search" value="" id="search-input" />
      <input type="submit" class="button button-search" value="Voir" id="search-button" />
    </form>
  </div>
  
  <div id="treeView">
      <ul class="tree">
          {copixzone process=gestionautonome|citiesGroup}
      </ul>
  </div>
   
   <div id="treeActions">
     {copixzone process=gestionautonome|TreeActions node_id=$ppo->targetId node_type=$ppo->targetType}
   </div>
</div>

<div id="column-data">
  {copixzone process=gestionautonome|PersonsData node_id=$ppo->targetId node_type=$ppo->targetType tab=$ppo->tab}
</div>      

{literal}
  <script type="text/javascript">
  //<![CDATA[
  
    jQuery(document).ready(function(){
      
      jQuery('#search-form').submit(function(){
        
        return false;
      });
      
      jQuery('#search-button').click(function(){
        
        var value = jQuery('#search-input').val();
        
        if (value != '' && value.length > 1){
          
          jQuery.ajax({
            url:     '{/literal}{copixurl dest=gestionautonome|default|search}{literal}',
            global:  true,
            type:    'GET',
            data:    { value: value },
            success: function(html){

              jQuery('#treeActions').html('<p>Sélectionnez un élément dans la structure.</p>');
              jQuery('#column-data').html('<p>Aucun élément sélectionné dans la structure.</p>');
              jQuery('ul.tree').empty();
              jQuery('ul.tree').append(html);
            }
          });
        }
      });
      
      jQuery('a.toggle-node, a.node').live('click', function(){
        
        // Identification de l'origine de l'action
        // Changement de l'état de la flèche
        // Affectation de l'état "current"
        var show_forced = 0;
 
        if (jQuery(this).hasClass('node')) {

          jQuery(this).prev().addClass('expand');
          jQuery(this).parent().find('ul').first().show();
          
          jQuery('a.current').removeClass('current');
          jQuery(this).addClass('current');
          
          show_forced = 1;
          
          var a_expand = jQuery(this).prev();
          var a_node = jQuery(this);
        }
        else {
          
          jQuery(this).toggleClass('expand');
          
          var a_expand = jQuery(this);
          var a_node = jQuery(this).next();
        }
        
        // Récupération de l'id et du type à partir de l'attribut du noeud
        var id = a_node.attr('id');
        var node_id = id.substr(id.lastIndexOf('-')+1);
        var node_type = id.substr(0, id.lastIndexOf('-'));
        
        if (show_forced){
          
          // Affichage du loader ajax
          jQuery('#treeActions').empty();
          jQuery('#treeActions').html('<p class="center">Chargement en cours...</p>');
          
          // Chargement de la zone "Actions"
          jQuery.ajax({
            url:     '{/literal}{copixurl dest=gestionautonome|default|updateTreeActions}{literal}',
            global:  true,
            type:    'GET',
            context: a_expand.parent(),
            data:    { node_type: node_type, node_id: node_id },
            success: function(html){

             jQuery('#treeActions').empty();
             jQuery('#treeActions').append(html);
            }
          });
          
          // Affichage du loader ajax
          jQuery('#column-data').empty();
          jQuery('#column-data').html('<p class="center">Chargement en cours...</p>');
          
          // Chargement de la zone "Personnes infos"
          jQuery.ajax({
            url:     '{/literal}{copixurl dest=gestionautonome|default|displayPersonsData}{literal}',
            global:  true,
            type:    'GET',
            context: a_expand.parent(),
            data:    { node_type: node_type, node_id: node_id },
            success: function(html){

             jQuery('#column-data').empty();
             jQuery('#column-data').append(html);
            }
          });
        }
        
        // Si le noeud est déplié => chargement des fils
        if (a_expand.length > 0) {
          if (a_expand.hasClass('expand')) {
            jQuery(this).parent().find('ul').first().show();
          }
          else {
            jQuery(this).parent().find('ul').first().hide();
          }
          
          if ($(this).parent().find('li').size() == 0) {
            jQuery('<img class="load-img" src="{/literal}{copixresource path="img/ajax-loader-mini.gif"}{literal}" />').insertAfter(a_node); 
            
            jQuery.ajax({
              url:     '{/literal}{copixurl dest=gestionautonome|default|toggleNode}{literal}',
              global:  true,
              type:    'GET',
              context: a_expand.parent(),
              data:    { node_type: node_type, node_id: node_id, show_forced: show_forced },
              success: function(html){
                var ul = jQuery(this).find('ul').first();
                ul.empty();
                ul.append(html);
                jQuery('img.load-img').remove();  
              }
            });
          }
        }
        return false;      
      });
      
      // Filtre année scolaire
      jQuery('#grade').change(function(){
        
        jQuery.ajax({
          url:     '{/literal}{copixurl dest=gestionautonome|default|refreshTree}{literal}',
          global:  true,
          type:    'GET',
          data:    { grade: jQuery('#grade').val() },
          success: function(html){

            jQuery('#treeActions').html('<p>Sélectionnez un élément dans la structure.</p>');
            jQuery('#column-data').html('<p>Aucun élément sélectionné dans la structure.</p>');
            jQuery('ul.tree').empty();
            jQuery('ul.tree').append(html);
          }
        });
      });
      
      {/literal}
      {if $ppo->targetId && $ppo->targetType}
        {literal}
          var type = '';
          switch ('{/literal}{$ppo->targetType}{literal}'){
            case 'BU_GRVILLE':
              type = 'cities-group-';
              break;
            case 'BU_VILLE':
              type = 'city-';
              break;
            case 'BU_ECOLE':
              type = 'school-';
              break;
            case 'BU_CLASSE':
              type = 'classroom-';
              break;
          }

          jQuery('#'+type+'{/literal}{$ppo->targetId}{literal}').addClass('current');
        {/literal}
      {/if}
      {literal}
    });
    
  //]]>
  </script> 
{/literal}