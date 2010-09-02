{i18n key="liste.home"}

<p></p>
{if $canWrite}
<a class="button button-add" href="{copixurl dest="|getMessageForm" liste=$liste->id}">{i18n key="liste.homeWriteMessage"}</a>	
{/if}
