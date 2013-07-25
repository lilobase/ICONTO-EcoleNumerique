<p class="memoClasse">{$ppo->memo->nom_classe}</p>
<p class="memoDate">
  <span class="actions">
    <a class="fancybox" href="{copixurl dest="cahierdetextes|memodirecteur|suivi" ecoleId=$ppo->ecoleId cahierId=$ppo->memo->cahier_id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$ppo->memo->id}" title="{if $ppo->memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}"><img src="{copixurl}themes/default/images/menu/menu_list_active.png" alt="{if $ppo->memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}" /></a>
    <a href="{copixurl dest="cahierdetextes|memodirecteur|imprimer" ecoleId=$ppo->ecoleId cahierId=$ppo->memo->cahier_id memoId=$ppo->memo->id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" title="{i18n key="cahierdetextes.message.print"}"><img src="{copixurl}themes/default/images/button-action/action_print.png" alt="{i18n key="cahierdetextes.message.print"}" /></a>
    {if $ppo->memo->created_by_role == $ppo->roleDirecteur}
      <a href="{copixurl dest="cahierdetextes|memodirecteur|editer" ecoleId=$ppo->ecoleId cahierId=$ppo->memo->cahier_id memoId=$ppo->memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a>
      <a href="{copixurl dest="cahierdetextes|memodirecteur|supprimer" ecoleId=$ppo->ecoleId cahierId=$ppo->memo->cahier_id memoId=$ppo->memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
    {/if}
  </span>
  {$ppo->memo->date_creation|datei18n:text}
</p>
<div class="memoMesg">{$ppo->memo->message}</div>
{if $ppo->memo->avec_signature}
  <div class="signature">
    <p class="warningSign"><span>{i18n key="cahierdetextes.message.toSignOn"} <strong>{$ppo->memo->date_max_signature|datei18n}</strong></span></p>
  </div>
{/if}
