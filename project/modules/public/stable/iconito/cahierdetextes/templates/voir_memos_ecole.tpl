<h2>
    {i18n key="cahierdetextes.message.memos"}
    <a class="floatright button button-add" href="{copixurl dest="cahierdetextes||editerMemo" ecoleId=$ppo->ecoleId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
        {i18n key="cahierdetextes.message.addMemo"}
    </a>
</h2>

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="cahierdetextes.message.success"}</p>
{/if}

<div class="memos-list">
  {if $ppo->memos neq null}
    {foreach from=$ppo->memos item=memo}
      <div class="memo">
        <p class="memoDate">
          <span class="actions">
            <a class="fancybox" href="{copixurl dest="cahierdetextes||suiviMemo" cahierId=$memo->classe_id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$memo->id}" title="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}"><img src="{copixurl}themes/default/images/menu/menu_list_active.png" alt="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}" /></a>
            <a href="{copixurl dest="|imprMemo" cahierId=$memo->classe_id memoId=$memo->id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" title="{i18n key="cahierdetextes.message.print"}"><img src="{copixurl}themes/default/images/button-action/action_print.png" alt="{i18n key="cahierdetextes.message.print"}" /></a>
            <a href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$memo->classe_id memoId=$memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a>
            <a href="{copixurl dest="cahierdetextes||supprimerMemo" cahierId=$memo->classe_id memoId=$memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
          </span>
          {$memo->date_creation|datei18n:text}
        </p>
        <div class="memoMesg">{$memo->message}</div>
        {if $memo->avec_signature}
          <div class="signature">
            <p class="warningSign"><span>{i18n key="cahierdetextes.message.toSignOn"} <strong>{$memo->date_max_signature|datei18n}</strong></span></p>
          </div>
        {/if}
        
        {copixzone process=cahierdetextes|affichageFichiers nodeType=memo nodeId=$memo->id} 
      </div>
    {/foreach}
    
    {if $ppo->pager neq null}
      {$ppo->pager}
    {/if}
  {else}
    <p>{i18n key="cahierdetextes.message.noMemo"}</p>
  {/if}
</div>