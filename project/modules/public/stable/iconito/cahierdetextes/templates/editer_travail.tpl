{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

{if $ppo->travail->a_faire}
  <h2>{i18n key="cahierdetextes.message.addTodoWork"}</h2>
{else}
  <h2>{i18n key="cahierdetextes.message.addClassroomWork"}</h2>
{/if}

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{elseif not $ppo->erreurs eq null}
	<div class="message_errors">
	  <ul>
	    {foreach from=$ppo->erreurs item=erreur}
		    <li>{$erreur}</li>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="travail_add" id="travail_add" action="{copixurl dest="cahierdetextes||editerTravail"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
    <input type="hidden" name="a_faire" id="a_faire" value="{$ppo->travail->a_faire}" />
    <input type="hidden" name="travailId" id="travailId" value="{$ppo->travail->id}" />
    <input type="hidden" name="jour" id="jour" value="{$ppo->jour}" />
    <input type="hidden" name="mois" id="mois" value="{$ppo->mois}" />
    <input type="hidden" name="annee" id="annee" value="{$ppo->annee}" />
    
    {if $ppo->travail->a_faire}  
      <div class="field">
        <label for="travail_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.dateGiven"} :</label>
        <input class="form datepicker" type="text" name="travail_date_creation" id="travail_date_creation" value="{if $ppo->travail->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->travail->date_creation}{/if}" />
      </div>
      
      <div class="field">
        <label for="travail_date_realisation" class="form_libelle">{i18n key="cahierdetextes.message.dateFor"} :</label>
        <input class="form datepicker" type="text" name="travail_date_realisation" id="travail_date_realisation" value="{$ppo->travail->date_realisation}" />
      </div>
    {else}
      <div class="field">
        <label for="travail_date_creation" class="form_libelle">{i18n key="cahierdetextes.message.date"} :</label>
        <input class="form datepicker" type="text" name="travail_date_creation" id="travail_date_creation" value="{if $ppo->travail->date_creation eq null}{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}{else}{$ppo->travail->date_creation}{/if}" />
      </div>
    {/if}
    <div class="field">
      <label for="travail_domaine_id" class="form_libelle">{i18n key="cahierdetextes.message.domain"} :</label>
      {if $ppo->idsDomaine|@count le $ppo->nombreMaxVueRadio}
        {html_radios name='travail_domaine_id' values=$ppo->idsDomaine output=$ppo->nomsDomaine selected=$ppo->travail->domaine_id}
      {else}
        {html_options name='travail_domaine_id' values=$ppo->idsDomaine output=$ppo->nomsDomaine selected=$ppo->travail->domaine_id}
      {/if}
    </div>
    <div class="field">
      <label for="travail_description" class="form_libelle">{i18n key="cahierdetextes.message.description"} :</label>
      {copixzone process=kernel|edition field='travail_description' format=$ppo->format content=$ppo->travail->description height=200}
    </div>
    <div class="field">
      <label for="travail_fichiers" class="form_libelle">{i18n key="cahierdetextes.message.relatedDocuments"} :</label>
    </div>
  </fieldset>
  
  <fieldset>
    {copixzone process=cahierdetextes|listeEleves nid=$ppo->nid elevesSelectionnes=$ppo->elevesSelectionnes}
  </fieldset>
  
  <div class="field">
    <label for="travail_redirection" class="form_libelle">{i18n key="cahierdetextes.message.whatWouldYouDo"}</label>
    <label><input type="radio" name="travail_redirection" value="0" {if $ppo->travail_redirection eq 0}checked{/if} /> {i18n key="cahierdetextes.message.backWorks"}</label>
    <label><input type="radio" name="travail_redirection" value="1" {if $ppo->travail_redirection eq 1}checked{/if} /> {i18n key="cahierdetextes.message.addClassroomWork"}</label>
    <label><input type="radio" name="travail_redirection" value="2" {if $ppo->travail_redirection eq 2}checked{/if} /> {i18n key="cahierdetextes.message.addTodoWork"}</label>
  </div>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="{i18n key="cahierdetextes.message.save"}" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	  
 	  $('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/cahierdetextes/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
  });
//]]> 
</script>
{/literal}