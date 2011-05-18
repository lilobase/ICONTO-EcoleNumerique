{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<form>
  <div class="field">
    <label for="date_from" class="form_libelle">{i18n key="cahierdetextes.message.dateFrom"} :</label>
    <input class="form datepicker" type="text" name="date_from" id="date_from" value="{$ppo->dateSelectionnee|date_format:"%d/%m/%Y"}" />
  </div>
  
  <div class="field">
    <label for="days" class="form_libelle">{i18n key="cahierdetextes.message.daysNumber"}</label>
    <select name="days">
      <option value="10" label="10">10</option>
      <option value="20" label="20">20</option>
    </select>
  </div>
</form>

<div class="works">
  {foreach from=$ppo->travaux key=date item=travauxParDate}
    <h3>{$date|date_format:"%A %d %B %Y"}</h3>
    
    {foreach from=$travauxParDate key=a_faire item=travauxParType}
      <h4>
        {if $a_faire eq 0}
          {i18n key="cahierdetextes.message.classroomWork"}
          <a class="actionLink" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$date|date_format:"%d" mois=$date|date_format:"%m" annee=$date|date_format:"%Y"}">{i18n key="cahierdetextes.message.addClassroomWork"}</a>
        {else}
          {i18n key="cahierdetextes.message.todoWork"}
          <a class="actionLink" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$date|date_format:"%d" mois=$date|date_format:"%m" annee=$date|date_format:"%Y" a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a>
        {/if}
      </h4>
      
      <table class="liste">
        {foreach from=$travauxParType item=travail}
          <tr>
            <td>{$travail->nom}</td>
            <td>{$travail->description}</td>
            <td>-</td>
            {if $ppo->typeUtilisateur == 'USER_ENS'}
              <td>
                <a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid travailId=$travail->id}">{i18n key="cahierdetextes.message.modify"}</a>
                <a href="{copixurl dest="cahierdetextes||supprimerTravail" nid=$ppo->nid travailId=$travail->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteWorkConfirm"}')">{i18n key="cahierdetextes.message.delete"}</a>
              </td>
            {/if}
          </tr>
        {/foreach}
      </table>

    {/foreach}
  {/foreach}
</div>

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