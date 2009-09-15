<br /><br />
{if nbInsertions eq "0"}
{i18n key="agenda.message.noeventsimport"}
{else}
<div class="messageimport">{i18n key="agenda.message.importOK"}.</div>
<div class="nbinsertions">{i18n key="agenda.message.eventsimportes" pNb=$nbInsertions}.</div>
{/if}
<br /><br />
<a href="{copixurl dest="agenda|agenda|vueSemaine"}">{i18n key="agenda.message.back"}</a>

