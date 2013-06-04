<h2>{i18n key="cahierdetextes.message.memos"}{if $ppo->estAdmin}<a class="floatright button button-add" href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.addMemo"}</a>{/if}{if $ppo->niveauUtilisateur == PROFILE_CCV_READ}{copixzone process=cahierdetextes|lienMinimail cahierId=$ppo->cahierId}{/if}</h2>

{if $ppo->success}
<p class="mesgSuccess">{i18n key="cahierdetextes.message.success"}</p>
{/if}

<div class="memos-list">
    {if $ppo->memos neq null}
        {foreach from=$ppo->memos item=memo}
            <div class="memo">
                <p class="memoDate">
                    {if $ppo->estAdmin}
                        <span class="actions">
                            <a class="fancybox" href="{copixurl dest="cahierdetextes||suiviMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee memoId=$memo->id}" title="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}"><img src="{copixurl}themes/default/images/menu/menu_list_active.png" alt="{if $memo->avec_signature}{i18n key="cahierdetextes.message.seeValidated"}{else}{i18n key="cahierdetextes.message.seeConcerned"}{/if}" /></a>
                            <a href="{copixurl dest="|imprMemo" cahierId=$ppo->cahierId memoId=$memo->id jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" title="{i18n key="cahierdetextes.message.print"}"><img src="{copixurl}themes/default/images/button-action/action_print.png" alt="{i18n key="cahierdetextes.message.print"}" /></a>
                            {if $memo->created_by_role eq $ppo->roleDirecteur }
                                <a href="{copixurl dest="cahierdetextes|memodirecteur|editer" cahierId=$ppo->cahierId memoId=$memo->id}" title="{i18n key="cahierdetextes.message.modify"}"><img src="{copixurl}themes/default/images/button-action/action_update.png" alt="{i18n key="cahierdetextes.message.modify"}" /></a>
                                <a href="{copixurl dest="cahierdetextes|memodirecteur|supprimer" cahierId=$ppo->cahierId memoId=$memo->id}" onclick="return confirm('{i18n key="cahierdetextes.message.deleteMemoConfirm"}')" title="{i18n key="cahierdetextes.message.delete"}"><img src="{copixurl}themes/default/images/button-action/action_delete.png" alt="{i18n key="cahierdetextes.message.delete"}" /></a>
                            {/if}
                        </span>
                    {/if}
                    {$memo->date_creation|datei18n:text}
                </p>
                <div class="memoMesg">{$memo->message}</div>
                {if $memo->avec_signature}
                    <div class="signature">
                        {if $memo->signe_le|datei18n}
                            <p class="confirmSign"><span>{i18n key="cahierdetextes.message.signOn"} <strong>{$memo->signe_le|datei18n}</strong></span>
                            {if $memo->commentaire neq null && $ppo->niveauUtilisateur == PROFILE_CCV_READ}<p>{$memo->commentaire}</p>{/if}</p>
                            {else}
                            <p class="warningSign"><span>{i18n key="cahierdetextes.message.toSignOn"} <strong>{$memo->date_max_signature|datei18n}</strong></span></p>
                        {/if}
                        {if $ppo->niveauUtilisateur == PROFILE_CCV_READ && $memo->signe_le == ''}
                            <form name="memo_sign" id="memo_sign" action="{copixurl dest="cahierdetextes||voirMemos"}" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="cahierId" id="cahierId" value="{$ppo->cahierId}" />
                                <input type="hidden" name="memoId" id="memoId" value="{$memo->id}" />
                                <input type="hidden" name="eleve" id="eleve" value="{$ppo->eleve}" />

                                <label class="comment" for="commentaire__{$memo->id}">{i18n key="cahierdetextes.message.comment"}</label><input type="text" name="commentaire" id="commentaire_{$memo->id}" value="" />
                                <input type="submit" value="{i18n key="cahierdetextes.message.signNow"}" class="button button-update" />
                            </form>
                        {/if}
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
