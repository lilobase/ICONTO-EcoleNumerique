<h4>Personnes disponibles</h4>

{if $ppo->save neq null}
  <p class="ui-state-highlight ui-corner-all" style="margin-top: 20px; padding: 0pt 0.7em;">
    <span style="float: left; margin-right: 0.3em;" class="ui-icon ui-icon-info"></span>
    <strong>Personne ajoutée</strong>
  </p>
{/if}

{if $ppo->persons neq null}
  <form name="add_existing_persons" id="add_existing_persons" action="{copixurl dest="|validateExistingPersonsAdd"}" method="POST" enctype="multipart/form-data">
    
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
    <input type="hidden" name="role" id="role" value="{$ppo->role}" />
    
    <table class="liste">
      <tr>
        <th class="liste_th">nom</th>
        <th class="liste_th">prenom</th>
        <th class="liste_th">compte</th>
        <th class="liste_th"></th>
      </tr>
      {foreach from=$ppo->persons item=person}
        <tr>
          <td>{$person->nom}</td>
          <td>{$person->prenom1}</td>
          <td>{$person->login_dbuser}</td>
          <td>
            <input type="checkbox" class="form" name="personIds[]" value="{$person->numero}" />
          </td>
        </tr>
      {/foreach}
    </table> 
    
    <ul class="actions">
      <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
    	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
    </ul>
  </form>
{else}
  <p><i>Pas de personnes disponibles...</i></p>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  </ul>
{/if}

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree nodeId=$ppo->nodeId nodeType=$ppo->nodeType notxml=true}'{literal};
  });
//]]> 
</script>
{/literal}