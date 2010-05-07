<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Modification d'une école</h2>

<h3>Ecole</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="school_update" id="school_update" action="{copixurl dest="|validateSchoolUpdate"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_node" id="id-node" value="{$ppo->nodeId}" />
    <input type="hidden" name="type_node" id="type-node" value="{$ppo->nodeType}" />
   
    <div class="field">
      <label for="type" class="form_libelle"> Type :</label>
      <select class="form" name="type" id="type">
  	    {html_options values=$ppo->types output=$ppo->types selected=$ppo->school->type}
  	  </select>
  	</div>
    
    <div class="field">
      <label for="name" class="form_libelle"> Nom :</label>
      <input class="form" type="text" name="nom" id="nom" value="{$ppo->school->nom}" />
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
  
  jQuery.noConflict();
  
  jQuery(document).ready(function(){
 	
 	  jQuery('.button').button();
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||showTree}'{literal};
  });
//]]> 
</script>
{/literal}