<p class="title">{i18n key="cahierdetextes.message.todoWork"} {if $ppo->typeUtilisateur == 'USER_ENS'}- <span><a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee a_faire=1}">{i18n key="cahierdetextes.message.addTodoWork"}</a></span>{/if}</p>

<div class="todo-works-list">
  {if $ppo->travaux neq null}
    {foreach from=$ppo->travaux item=travail}
      <div class="work">
        <h3>{$travail->nom}</h3>
        {$travail->description}
        
        {if $ppo->typeUtilisateur == 'USER_ENS'}
          <span class="actions">
            (<a href="{copixurl dest="cahierdetextes||editerTravail" nid=$ppo->nid travailId=$travail->id}">{i18n key="cahierdetextes.message.modify"}</a> - <a href="{copixurl dest="cahierdetextes||supprimerTravail" nid=$ppo->nid travailId=$travail->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteWorkConfirm"}')">{i18n key="cahierdetextes.message.delete"}</a>)
          </span>
        {/if}
      </div>
    {/foreach}
  {else}
    <p>{i18n key="cahierdetextes.message.noWork"}</p>
  {/if}
</div>