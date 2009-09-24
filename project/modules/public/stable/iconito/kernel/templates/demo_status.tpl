
{if $installed eq 0}

{i18n key="kernel|demo.txt.install"}
<p></p>
<a href="{copixurl dest="kernel|demo|install"}" class="button_like">{i18n key="kernel|demo.btn.install"}</a>

{else}

{i18n key="kernel|demo.error.alreadyInstalled" noEscape=1}
<p></p>
{i18n key="kernel|demo.txt.accounts" noEscape=1}
{/if}
