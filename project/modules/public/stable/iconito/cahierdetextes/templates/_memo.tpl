<p class="memoDate">
  {if $ppo->estAdmin}
  <span class="actions">
    <a class="fancybox" href="{copixurl dest="cahierdetextes||suiviMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$ppo->memo->id}" title="{if $ppo->memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}"><img src="{copixurl}themes/default/images/menu/menu_list_active.png" alt="{if $ppo->memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}" /></a>
    <a href="{copixurl dest="|imprimerMemo" cahierId=$ppo->cahierId memoId=$ppo->memo->id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" title="{i18n key="cahierdetextes.message.print"}"><img src="{copixurl}themes/default/images/button-action/action_print.png" alt="{i18n key="cahierdetextes.message.print"}" /></a>
    {if $ppo->memo->created_by_role != $ppo->roleDirecteur}
        <a href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$ppo->cahierId memoId=$ppo->memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a>
        <a href="{copixurl dest="cahierdetextes||supprimerMemo" cahierId=$ppo->cahierId memoId=$ppo->memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
    {/if}
  </span>
  {/if}
{$ppo->memo->date_creation|datei18n:text}</p>
<div class="memoMesg">{$ppo->memo->message}</div>
{if $ppo->memo->avec_signature}
  <div class="signature">
  {if $ppo->memo->signe_le|datei18n}
      <p class="confirmSign"><span>{i18n key="cahierdetextes.message.signOn"} <strong>{$ppo->memo->signe_le|datei18n}</strong></span>
        {if $ppo->memo->commentaire neq null && $ppo->niveauUtilisateur == PROFILE_CCV_READ}<p>{$ppo->memo->commentaire}</p>{/if}</p>
  {else}
      <p class="warningSign"><span>{i18n key="cahierdetextes.message.toSignOn"} <strong>{$ppo->memo->date_max_signature|datei18n}</strong></span></p>
  {/if}
  {if $ppo->niveauUtilisateur == PROFILE_CCV_READ && $ppo->memo->signe_le == ''}
      <form name="memo_sign" id="memo_sign" action="{copixurl dest="cahierdetextes||voirMemos"}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
        <input type="hidden" name="memoId" id="memoId" value="{$ppo->memo->id}" />
        <input type="hidden" name="eleve" id="eleve" value="{$ppo->eleve}" />

        <label class="comment" for="commentaire__{$ppo->memo->id}">{i18n key="cahierdetextes.message.comment"}</label><input type="text" name="commentaire" id="commentaire_{$ppo->memo->id}" value="" />
        <input type="submit" value="{i18n key="cahierdetextes.message.signNow"}" class="button button-update" />
      </form>
  {/if}
  </div>
{/if}