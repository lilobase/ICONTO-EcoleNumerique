<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'une ville</h2>

<h3>Ville</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="city_creation" id="city_creation" action="{copixurl dest="|validateCityCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_parent" id="id-parent" value="{$ppo->parentId}" />
    <input type="hidden" name="type_parent" id="type-parent" value="{$ppo->parentType}" />
    
    <div class="field">
      <label class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->city->nom}" />
    </div>
  </fieldset>
  
  <ul class="actions">
    <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
  	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	
 	  jQuery('.button').button();
 	  
 	  jQuery('#cancel').click(function() {

      document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
    });
  });
//]]> 
</script>
{/literal}