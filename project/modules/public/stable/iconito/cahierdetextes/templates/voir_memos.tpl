{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h4>{i18n key="cahierdetextes.message.memos"}</h4>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{/if}

{if $ppo->typeUtilisateur == 'USER_ENS'}<span><a href="{copixurl dest="cahierdetextes||editerMemo" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.addMemo"}</a></span>{/if}

<div class="memos-list">
  {if $ppo->memos neq null}
    {foreach from=$ppo->memos item=memo}
      <div class="memo">
        {$memo->message}
        
        {if $ppo->typeUtilisateur == 'USER_ENS'}
          {popupinformation handler=onclick zone='cahierdetextes|'}{/popupinformation}
          {if $memo->avec_signature}
            Voir le suivi
          {else}
            Voir les concernés
          {/if}
          <span class="actions">
            <a href="{copixurl dest="cahierdetextes||editerMemo" nid=$ppo->nid memoId=$memo->id}">{i18n key="cahierdetextes.message.modify"}</a> - <a href="{copixurl dest="cahierdetextes||supprimerMemo" nid=$ppo->nid memoId=$memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')">{i18n key="cahierdetextes.message.delete"}</a>
          </span>
        {/if}
        
        {if $memo->avec_signature}
          {if $memo->signe_le}
            {i18n key="cahierdetextes.message.signOn"} : {$memo->signe_le}
          {else}
            {i18n key="cahierdetextes.message.toSignOn"} : {$memo->date_max_signature}
          {/if}
        {/if}
        
        {if $ppo->typeUtilisateur == 'USER_RES' && $memo->avec_signature && $memo->signe_le == ''}
          <form name="memo_sign" id="memo_sign" action="{copixurl dest="cahierdetextes||voirMemos"}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
            <input type="hidden" name="memoId" id="memoId" value="{$memo->id}" />
            
            <input type="text" name="commentaire" id="commentaire" value="Commentaire" />
            <input type="submit" value="Signer" />
          </form>
        {/if}
      </div>
    {/foreach}
  {else}
    <p>{i18n key="cahierdetextes.message.noMemo"}</p>
  {/if}
</div>