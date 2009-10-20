{if $nbMessages}
<div class="minimailNbMessages"><a title="{i18n key="minimail.unreads}" href="{copixurl dest="minimail||getListRecv"}">{$nbMessages}</a>
<br/>{if $nbMessages>1}{i18n key="minimail.unreads}{else}{i18n key="minimail.unread}{/if}
</div>
{/if}
