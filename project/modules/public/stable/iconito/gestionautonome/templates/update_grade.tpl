<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Modification de l'année scolaire {$ppo->grade->id_as}</h2>

<h3>Année scolaire</h3>

{if not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="grade_update" id="grade_update" action="{copixurl dest="|validateGradeUpdate"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="id_as" id="id-as" value="{$ppo->grade->id_as}" />
    
    <div class="field">
      <label for="dateDebut" class="form_libelle"> Date de début :</label>
      <input type="text" id="dateDebut" name="dateDebut" class="form datepicker" value="{$ppo->grade->dateDebut}" />
    </div>
    <div class="field">
      <label for="dateFin" class="form_libelle"> Date de fin :</label>
      <input type="text" id="dateFin" name="dateFin" class="form datepicker" value="{$ppo->grade->dateFin}" />
    </div>
    <div class="field">
      <label for="dateFin" class="form_libelle"> Année courante :</label>
      <input class="form" type="checkbox" name="current" id="current" {if $ppo->grade->current} checked=checked{/if}" />
    </div>
</form>

<ul class="actions">
  <li><input class="button" type="button" value="Annuler" id="cancel" /></li>
	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
</ul>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery.noConflict();
  
  jQuery(document).ready(function() {

    jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="../gestionautonome/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-5:c+5'
    });   
    
    jQuery('.button').button();
  });
  
  jQuery('#cancel').click(function() {
    
    document.location.href={/literal}'{copixurl dest=gestionautonome||manageGrades}'{literal};
  });
  
//]]> 
</script>
{/literal}