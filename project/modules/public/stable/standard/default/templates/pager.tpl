{if $TOTAL_PAGE > 1}
<div class="pagerContainer">
    <p class="totalPages">{i18n key=copix:pager.messages.resultFound pNb=$NBRECORD} - {i18n key=copix:pager.messages.currentOfTotal CURRENT_PAGE=$CURRENT_PAGE TOTAL_PAGE=$TOTAL_PAGE}</p>
    <p>{$FIRST_PAGE} {$PREVIOUS_PAGE} &nbsp; {$LOOP} &nbsp; {$NEXT_PAGE} {$LAST_PAGE}</p>
</div>
{/if}