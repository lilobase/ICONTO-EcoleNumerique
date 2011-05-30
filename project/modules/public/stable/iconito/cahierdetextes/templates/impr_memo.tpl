<div class="memos-list">
{section name=count start=0 loop=$ppo->count}
  <hr class="memo-separator" />
  <div class="memo">
    <p class="memoDate">
    {$ppo->memo->date_creation|datei18n:text}</p>
    <div class="memoMesg">{$ppo->memo->message}</div>
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
{/section}
</div>