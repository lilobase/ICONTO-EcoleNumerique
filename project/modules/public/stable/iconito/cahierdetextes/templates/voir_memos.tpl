{copixzone process=cahierdetextes|affichageMenu nid=$ppo->nid date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee}

<h2>{i18n key="cahierdetextes.message.memos"}</h2>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{/if}

{if $ppo->typeUtilisateur == 'USER_ENS'}<a class="button button-add" href="{copixurl dest="cahierdetextes||editerMemo" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.addMemo"}</a>{/if}

<div class="memos-list">
  {if $ppo->memos neq null}
    {foreach from=$ppo->memos item=memo}
      <div class="memo">
        <p class="memoDate">{if $ppo->typeUtilisateur == 'USER_ENS'}
          <span class="actions">
            {if $memo->avec_signature}
              <a href="{copixurl dest="cahierdetextes||suiviMemo" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$memo->id}" title="{i18n key="cahierdetextes.message.seeValidated"}"><img src="{copixurl}themes/default/images/menu_list_active.png" alt="{i18n key="cahierdetextes.message.seeValidated"}" /></a>
            {else}
              <a href="{copixurl dest="cahierdetextes||suiviMemo" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$memo->id}" title="{i18n key="cahierdetextes.message.seeConcerned"}"><img src="{copixurl}themes/default/images/menu_list_active.png" alt="{i18n key="cahierdetextes.message.seeConcerned"}" /></a>
            {/if}
            <a href="{copixurl dest="cahierdetextes||editerMemo" nid=$ppo->nid memoId=$memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a> <a href="{copixurl dest="cahierdetextes||supprimerMemo" nid=$ppo->nid memoId=$memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
          </span>
        {/if}
        {$memo->date_creation|datei18n:text}</p>
        {$memo->message}

        {if $memo->avec_signature}
          {if $memo->signe_le|datei18n}
            <p>{i18n key="cahierdetextes.message.signOn"} : {$memo->signe_le|datei18n}</p>
          {else}
            <p>{i18n key="cahierdetextes.message.toSignOn"} : {$memo->date_max_signature|datei18n}</p>
          {/if}
        {/if}
        
        {if $ppo->typeUtilisateur == 'USER_RES' && $memo->avec_signature && $memo->signe_le == ''}
          <form name="memo_sign" id="memo_sign" action="{copixurl dest="cahierdetextes||voirMemos"}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="nid" id="nid" value="{$ppo->nid}" />
            <input type="hidden" name="memoId" id="memoId" value="{$memo->id}" />
            
            <input type="text" name="commentaire" value="{i18n key="cahierdetextes.message.comment"}" />
            <input type="submit" value="Signer" />
          </form>
        {/if}
        
        
      </div>
    {/foreach}
  {else}
    <p>{i18n key="cahierdetextes.message.noMemo"}</p>
  {/if}
</div>

{literal}
<script type="text/javascript">
//<![CDATA[
  
  $(document).ready(function(){
 	  
 	  $('input[name="commentaire"]').live('focus', function () {
       if ($(this).val() == '{/literal}{i18n key="cahierdetextes.message.comment"}{literal}') {
         
         $(this).val('');
       }
    });
    
    $('input[name="commentaire"]').live('blur', function () {
       
      if ($(this).val() == '') {

        $(this).val('{/literal}{i18n key="cahierdetextes.message.comment"}{literal}');
      }
    });
    
  });
//]]> 
</script>
{/literal}