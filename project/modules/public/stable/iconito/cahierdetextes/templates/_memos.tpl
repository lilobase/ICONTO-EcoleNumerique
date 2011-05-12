<div id="memos-list" class="memos-list">
  {foreach from=$ppo->memos item=memo}
    <div class="memo">
      {$memo->message}
    </div>
  {/foreach}
  <span><a href="{copixurl dest="cahierdetextes||voirMemos" nid=$ppo->nid jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}">{i18n key="cahierdetextes.message.seeAllMemos"}</a></span>
</div>