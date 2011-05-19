<div id="memos-list" class="memos-list">
  <h3>{i18n key="cahierdetextes.message.memos"}</h3>
  <ul class="memo">
  {assign var=index value=1}
  {foreach from=$ppo->memos item=memo}
    <li>
      <a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" rel="{$index}">{$memo->message}</a>
    </li>
  {assign var=index value=$index+1}
  {/foreach}
  </ul>
  <a id="seeAllMemos" href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.seeAllMemos"}</a>
</div>