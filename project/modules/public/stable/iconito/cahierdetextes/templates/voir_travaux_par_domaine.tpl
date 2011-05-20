{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{/if}

<form name="list_filter" id="list_filter" action="{copixurl dest="cahierdetextes||voirTravauxParDomaine"}" method="POST" enctype="multipart/form-data">
  <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
  <div class="field">
    <label for="date_deb" class="form_libelle">{i18n key="cahierdetextes.message.dateFrom"} :</label>
    <input class="form datepicker" type="text" name="date_deb" id="date_deb" value="{$ppo->dateDeb|datei18n}" />
  </div>
  
  <div class="field">
    <label for="nb_jours" class="form_libelle">{i18n key="cahierdetextes.message.daysNumber"}</label>
    {html_options name='nb_jours' values=$ppo->choixNbJours output=$ppo->choixNbJours selected=$ppo->nbJours}
  </div>
  
  <div class="field">
    <label for="domaine" class="form_libelle">{i18n key="cahierdetextes.message.domain"}</label>
    {html_options name='domaine' values=$ppo->idsDomaine output=$ppo->nomsDomaine selected=$ppo->domaine}
  </div>
  
  <input type="submit" class="button button-next" value="{i18n key="cahierdetextes.message.seeWorks"}" />
</form>

<div class="works">
  {foreach from=$ppo->travaux key=domaine item=travauxParDomaine}
    <h2>{$domaine}</h2>
    
    {foreach from=$travauxParDomaine key=a_faire item=travauxParType}
      <h3>
        {if $a_faire eq 0}
          {i18n key="cahierdetextes.message.classroomWork"}
          {if $ppo->typeUtilisateur == 'USER_ENS'}
            <a class="button button-add" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee vue="domaine"}">{i18n key="cahierdetextes.message.addClassroomWork"}</a>
          {/if}
        {else}
          {i18n key="cahierdetextes.message.todoWork"}
          {if $ppo->typeUtilisateur == 'USER_ENS'}
            <a class="button button-add" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee vue="domaine" a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a>
          {/if}
        {/if}
      </h3>
      
      <table>
        {assign var=index value=1}
        {foreach from=$travauxParType item=travail}
          <tr class="{if $index%2 eq 0}odd{else}even{/if}">
            <th>
              {if $a_faire}
                {$travail->date_realisation|datei18n:text}
              {else}
                {$travail->date_creation|datei18n:text}
              {/if}
            </th>
            <td>{$travail->description}</td>
            <td>-</td>
            {if $ppo->typeUtilisateur == 'USER_ENS'}
              <td class="center actions">
                <a href="{copixurl dest="cahierdetextes||voirConcernesParTravail" nid=$ppo->nid travailId=$travail->id}" title="{i18n key="cahierdetextes.message.seeConcerned"}"><img src="{copixurl}themes/default/images/menu_list_active.png" alt="{i18n key="cahierdetextes.message.seeConcerned"}" /></a>
                <a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee travailId=$travail->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a> 
            <a href="{copixurl dest="cahierdetextes||supprimerTravail" nid=$ppo->nid travailId=$travail->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteWorkConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
              </td>
            {/if}
          </tr>
        {assign var=index value=$index+1}
        {/foreach}
      </table>

    {/foreach}
  {/foreach}
</div>