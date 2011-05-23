<h3>{i18n key="cahierdetextes.message.memos"}</h3>
<div id="memos-list" class="memos-list">
  
  <ul class="memo">
  {assign var=index value=1}
  {foreach from=$ppo->memos item=memo}
    <li>
      {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
      <a href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}" rel="{$index}">
      {else}
      <a href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" rel="{$index}">
      {/if}
        {$memo->message}
      </a>
    </li>
  {assign var=index value=$index+1}
  {/foreach}
  </ul>
  {if $ppo->niveauUtilisateur == PROFILE_CCV_READ}
  <a id="seeAllMemos" href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee eleve=$ppo->eleve}">
  {else}
  <a id="seeAllMemos" href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">
  {/if}
    {i18n key="cahierdetextes.message.seeAllMemos"}
  </a>
</div>