{foreach from=$ppo->citiesGroups key=key item=citiesGroup}
  <li>
    {if in_array($citiesGroup->id_grv, $ppo->nodes)}
      {assign var=is_expanded value=true}
    {else}
      {assign var=is_expanded value=false}
    {/if}
    <a href="#" class="toggle-node{if $is_expanded} expand{/if}"><span>+</span></a>
    <a href="#" id="cities-group-{$citiesGroup->id_grv}" class="node after-expand"><span>{$citiesGroup->nom_groupe}</span></a>
    
    <ul class="tree">
      {if $is_expanded}
        {copixzone process=gestionautonome|city cities_group_id=$citiesGroup->id_grv}
      {/if}
    </ul>
  </li>
{/foreach}

{literal}
  <script type="text/javascript">
  //<![CDATA[
  
    jQuery.noConflict();
    jQuery(document).ready(function(){
      
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
          
          // Chargement de la zone "Actions"
          jQuery.ajax({
            url:     '{/literal}{copixurl dest=gestionautonome|default|updateTreeActions}{literal}',
            global:  true,
            type:    'GET',
            context: a_expand.parent(),
            data:    { node_type: node_type, node_id: node_id },
            success: function(html){

             jQuery('#tree-actions').empty();
             jQuery('#tree-actions').append(html);
            }
          });

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

          jQuery.ajax({
            url:     '{/literal}{copixurl dest=gestionautonome|default|toggleNode}{literal}',
            global:  true,
            type:    'GET',
            context: a_expand.parent(),
            data:    { node_type: node_type, node_id: node_id, show_forced: show_forced },
            success: function(html){

              var ul = jQuery(this).find('ul').first();
              ul.empty('');
              ul.append(html);
            }
          });
        }      
      });
      
      // Filtre année scolaire
      jQuery('#grade').change(function(){

        location.href = '{/literal}{copixurl dest=gestionautonome|default|showTree}{literal}?grade='+jQuery('#grade').val();
      });
    });
    
  //]]>
  </script> 
{/literal}