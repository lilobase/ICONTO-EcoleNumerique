{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<form name="list_filter" id="list_filter" action="{copixurl dest="cahierdetextes||voirListeTravaux"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
  <div class="field">
    <label for="date_deb" class="form_libelle">{i18n key="cahierdetextes.message.dateFrom"} :</label>
    <input class="form datepicker" type="text" name="date_deb" id="date_deb" value="{$ppo->dateDeb|date_format:"%d/%m/%Y"}" />
  </div>
  
  <div class="field">
    <label for="nb_jours" class="form_libelle">{i18n key="cahierdetextes.message.daysNumber"}</label>
    {html_options name='nb_jours' values=$ppo->choixNbJours output=$ppo->choixNbJours selected=$ppo->nbJours}
  </div>
  
  <input type="submit" />
</form>

<div class="works">
  {foreach from=$ppo->travaux key=date item=travauxParDate}
    <h3>{$date|datei18n:text}</h3>
    
    {foreach from=$travauxParDate key=a_faire item=travauxParType}
      <h4>
        {if $a_faire eq 0}
          {i18n key="cahierdetextes.message.classroomWork"}
          {if $ppo->typeUtilisateur == 'USER_ENS'}
            <a class="actionLink" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$date|date_format:"%d" mois=$date|date_format:"%m" annee=$date|date_format:"%Y"}">{i18n key="cahierdetextes.message.addClassroomWork"}</a>
          {/if}
        {else}
          {i18n key="cahierdetextes.message.todoWork"}
          {if $ppo->typeUtilisateur == 'USER_ENS'}
            <a class="actionLink" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$date|date_format:"%d" mois=$date|date_format:"%m" annee=$date|date_format:"%Y" a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a>
          {/if}
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