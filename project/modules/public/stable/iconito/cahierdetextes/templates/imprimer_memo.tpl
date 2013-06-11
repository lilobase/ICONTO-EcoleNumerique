<div id="submenu">
<div class="menuitems">
    <ul>
        {if $ppo->memoContext == 'ecole'}
            <li><a href="{copixurl dest="cahierdetextes|memodirecteur|voir" ecoleId=$ppo->ecoleId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" class="back"><span class="valign"></span><span>Retour</span></a></li>
        {else}
            <li><a href="{copixurl dest="cahierdetextes||voirMemos" cahierId=$ppo->cahierId jour=$ppo->jour mois=$ppo->mois annee=$ppo->annee}" class="back"><span class="valign"></span><span>Retour</span></a></li>
        {/if}
        <li><a class="print"><span class="valign"></span><span>Imprimer</span></a></li>
    </ul>
</div>
</div>
  
  <div class="memos-list">

{section name=count start=0 loop=$ppo->count}
  <div class="memo">
    <p class="memoDate">
    {$ppo->memo->date_creation|datei18n:text}</p>
    <div class="memoMesg memoPrint">{$ppo->memo->message}</div>
    {if $ppo->memo->avec_signature}
      <div class="signature">
      {if $ppo->memo->signe_le|datei18n}
          <p><span>{i18n key="cahierdetextes.message.signOn"} <strong>{$ppo->memo->signe_le|datei18n}</strong></span>
      {else}
          <p><span>{i18n key="cahierdetextes.message.toSignOn"} <strong>{$ppo->memo->date_max_signature|datei18n}</strong></span></p>
      {/if}
      </div>
    {/if}
  </div>
  <hr />
  
{/section}
</div>