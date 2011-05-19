<div id="memos-list" class="memos-list">
  <ul class="memo">
  {foreach from=$ppo->memos item=memo}
    <li>
      {$memo->message}
    </li>
  {/foreach}
  </ul>
  <a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.seeAllMemos"}</a>
</div>