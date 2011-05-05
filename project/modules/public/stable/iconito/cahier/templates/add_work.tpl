{if $ppo->todo}
  <h2>{i18n key="cahier.message.add_todo_work"}</h2>
{else}
  <h2>{i18n key="cahier.message.add_classroom_work"}</h2>
{/if}

{if $ppo->save neq null}
  <p class="success">Travail créé</p>
{elseif not $ppo->errors eq null}
	<div class="message_erreur">
	  <ul>
	    {foreach from=$ppo->errors item=error}
		    <li>{$error}</li><br \>
	    {/foreach}
	  </ul>
	</div>
{/if}

<form name="work_add" id="work_add" action="{copixurl dest="cahier||validateAddWork"}" method="POST" enctype="multipart/form-data">
  <fieldset>
    <input type="hidden" name="todo" id="todo" value="{$ppo->todo}" />
    <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
    
    {if $ppo->todo}  
      <div class="field">
        <label for="work_date_from" class="form_libelle">Donné le :</label>
        <input class="form datepicker" type="text" name="work_date_from" id="work_date_from" value="{if $ppo->work->date_creation eq null}{$ppo->selectedDate|date_format:"%d/%m/%Y"}{else}{$ppo->work->date_creation}{/if}" />
      </div>
      
      <div class="field">
        <label for="work_date_to" class="form_libelle">Pour le :</label>
        <input class="form datepicker" type="text" name="work_date_to" id="work_date_to" value="{$ppo->work->date_realisation}" />
      </div>
    {else}
      <div class="field">
        <label for="work_date" class="form_libelle">Date :</label>
        <input class="form datepicker" type="text" name="work_date" id="work_date" value="{if $ppo->work->date_creation eq null}{$ppo->selectedDate|date_format:"%d/%m/%Y"}{else}{$ppo->work->date_creation}{/if}" />
      </div>
    {/if}
    <div class="field">
      <label for="domain_id" class="form_libelle">Domaine :</label>
      {if $ppo->domainsStyleView eq 'radio'}
        {html_radios name='domain_id' values=$ppo->domainIds output=$ppo->domainNames selected=$ppo->work->domaine_id}
      {else}
        {html_options name='domain_id' values=$ppo->domainIds output=$ppo->domainNames selected=$ppo->work->domaine_id}
      {/if}
    </div>
    <div class="field">
      <label for="work_description" class="form_libelle">Description :</label>
      {copixzone process=kernel|edition field='work_description' format=$ppo->format content=$ppo->work->description height=200}
    </div>
    <div class="field">
      <label for="work_files" class="form_libelle">Documents associés :</label>
    </div>
  </fieldset>
  
  <fieldset>
    {copixzone process=cahier|showStudents nid=$ppo->nid selectedStudentIds=$ppo->selectedStudentIds}
  </fieldset>
  
  <div class="field">
    <label for="work_after" class="form_libelle">Que souhaitez-vous faire</label>
    <input type="radio" name="work_after" value="0" {if $ppo->work_after eq 0}checked{/if} /> {i18n key="cahier.message.back_works"}
    <input type="radio" name="work_after" value="1" {if $ppo->work_after eq 1}checked{/if} /> {i18n key="cahier.message.add_classroom_work"}
    <input type="radio" name="work_after" value="2" {if $ppo->work_after eq 2}checked{/if} /> {i18n key="cahier.message.add_todo_work"}
  </div>
  
  <ul class="actions">
  	<li><input class="button" type="submit" name="save" id="save" value="Enregistrer" /></li>
  </ul>
</form>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  jQuery(document).ready(function(){
 	  
 	  jQuery('.datepicker').datepicker({
    	showOn: 'button',
    	buttonImage: '{/literal}{copixresource path="img/cahier/calendar.png"}{literal}',
    	buttonImageOnly: true,
    	changeMonth: true,
      changeYear: true,
      yearRange: 'c-20:c+10'
    });
    
  });
//]]> 
</script>
{/literal}