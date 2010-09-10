
{if $installed eq 0}

  {$demo_txt_install}
  <p></p>
  <a href="{copixurl dest="sysutils|demo|install"}" class="button button-continue">{i18n key="sysutils|demo.btn.install"}</a>

{else}

  {i18n key="sysutils|demo.error.alreadyInstalled" noEscape=1}
  <p></p>
  {i18n key="sysutils|demo.txt.accounts" noEscape=1}

{/if}
