{copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve}

<h2>{i18n key="cahierdetextes.message.memos"}</h2>

{if $ppo->success}
  <p class="success">{i18n key="cahierdetextes.message.success"}</p>
{/if}

{if $ppo->estAdmin}<a class="button button-add" href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.addMemo"}</a>{/if}

<div class="memos-list">
  {if $ppo->memos neq null}
    {foreach from=$ppo->memos item=memo}
      <div class="memo">
        <p class="memoDate">
          {if $ppo->estAdmin}
          <span class="actions">
            <a href="{copixurl dest="cahierdetextes||suiviMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$memo->id}" title="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}"><img src="{copixurl}themes/default/images/menu_list_active.png" alt="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}" /></a>
            <a href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$ppo->cahierId memoId=$memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a>
            <a href="{copixurl dest="cahierdetextes||supprimerMemo" cahierId=$ppo->cahierId memoId=$memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
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
        
        {if $ppo->niveauUtilisateur == PROFILE_CCV_READ && $memo->avec_signature && $memo->signe_le == ''}
          <form name="memo_sign" id="memo_sign" action="{copixurl dest="cahierdetextes||voirMemos"}" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
            <input type="hidden" name="memoId" id="memoId" value="{$memo->id}" />
            <input type="hidden" name="eleve" id="eleve" value="{$ppo->eleve}" />
            
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