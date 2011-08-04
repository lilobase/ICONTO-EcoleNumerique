{if $TOTAL_PAGE > 1}
<div class="center">
    {$NBRECORD} {i18n key=copix:pager.messages.resultFound pNb=$NBRECORD} ({i18n key=copix:pager.messages.currentOfTotal CURRENT_PAGE=$CURRENT_PAGE TOTAL_PAGE=$TOTAL_PAGE})
    <br />
    <p class="pagerContainer">{$FIRST_PAGE} {$PREVIOUS_PAGE} &nbsp; {$LOOP} &nbsp; {$NEXT_PAGE} {$LAST_PAGE}</p>
</div>
{/if}
