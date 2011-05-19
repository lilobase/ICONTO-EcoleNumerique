<h5>{i18n key="cahierdetextes.message.todoWork"} {if $ppo->typeUtilisateur == 'USER_ENS'}<a class="button button-add" href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a>{/if}</h5>

<div class="todo-works-list">
  {if $ppo->travaux neq null}
    {foreach from=$ppo->travaux item=travail}
      <div class="work">
        <h6>{$travail->nom} {if $ppo->typeUtilisateur == 'USER_ENS'}
          <span class="actions">
            <a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid travailId=$travail->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a> <a href="{copixurl dest="cahierdetextes||supprimerTravail" nid=$ppo->nid travailId=$travail->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteWorkConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
          </span>
        {/if}</h6>
        {$travail->description}
      </div>
    {/foreach}
  {else}
    <p class="no-work">{i18n key="cahierdetextes.message.noWork"}</p>
  {/if}
</div>