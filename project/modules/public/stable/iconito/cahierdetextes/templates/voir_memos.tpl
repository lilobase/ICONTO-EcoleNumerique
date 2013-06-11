{if $ppo->memoContext == 'classe'}
    {copixzone process=cahierdetextes|affichageMenu cahierId=$ppo->cahierId date_jour=$ppo->jour date_mois=$ppo->mois date_annee=$ppo->annee eleve=$ppo->eleve current="voirMemos"}

    <h2>
        {i18n key="cahierdetextes.message.memos"}
        {if $ppo->estAdmin}
            <a class="floatright button button-add" href="{copixurl dest="cahierdetextes||editerMemo" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
                {i18n key="cahierdetextes.message.addMemo"}
            </a>
        {/if}
        {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
            {copixzone process=cahierdetextes|lienMinimail cahierId=$ppo->cahierId}
        {/if}
    </h2>
{else}
    <h2>
        {i18n key="cahierdetextes.message.memos"}
        <a class="floatright button button-add" href="{copixurl dest="cahierdetextes|memodirecteur|editer" ecoleId=$ppo->ecoleId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
            {i18n key="cahierdetextes.message.addMemo"}
        </a>
    </h2>
{/if}

{if $ppo->success}
  <p class="mesgSuccess">{i18n key="cahierdetextes.message.success"}</p>
{/if}

<div class="memos-list">
  {if $ppo->memos neq null}
    {foreach from=$ppo->memos item=memo}
      <div class="memo">
        {copixzone process=cahierdetextes|affichageMemo ppo=$ppo memo=$memo}
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
