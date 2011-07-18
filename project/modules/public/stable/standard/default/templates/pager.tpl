<div class="center">
{$NBRECORD} {i18n key=copix:pager.messages.resultFound pNb=$NBRECORD} ({i18n key=copix:pager.messages.currentOfTotal CURRENT_PAGE=$CURRENT_PAGE TOTAL_PAGE=$TOTAL_PAGE})
<br />
<table class="pagerContainer">
<tr>
  <td>{$FIRST_PAGE}</td>
  <td>{$PREVIOUS_PAGE}</td>
  <td>{$LOOP}</td>
  <td>{$NEXT_PAGE}</td>
  <td>{$LAST_PAGE}</td>
</tr>
</table>
</div>