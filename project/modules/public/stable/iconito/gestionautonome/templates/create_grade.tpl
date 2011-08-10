<p class="breadcrumbs">{$ppo->breadcrumbs}</p>

<h2>Ajout d'une année scolaire</h2>

<h3>Année scolaire</h3>

{if not $ppo->errors eq null}
	<div class="mesgErrors">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="grade_creation" id="grade_creation" action="{copixurl dest="|validateGradeCreation"}" method="POST" enctype="multipart/form-data">
  <fieldset>
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
      <input class="form" type="checkbox" name="current" id="current" />
    </div>
  </fieldset>

  <div class="submit">
      <a href="{copixurl dest=gestionautonome||manageGrades}" class="button button-cancel">Annuler</a>
      <input class="button button-confirm" type="submit" name="save" id="save" value="Enregistrer" />
  </div>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function() {

    jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/gestionautonome/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-5:c+5'
    });   
    
  });

//]]> 
</script>
{/literal}