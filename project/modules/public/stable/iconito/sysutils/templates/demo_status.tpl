
{if $installed eq 0}

  {$demo_txt_install}
  <p class="demo_information">INFORMATION : Le jeu d'essai ne support pas encore le module classeur (nouveaut&eacute; 2011). 
      Il reste bas&eacute; sur les anciens modules Malle et Albums.
  Celui-ci sera port&eacute; d&egrave;s que possible. Merci pour votre compr&eacute;hension.</p>
  <a href="{copixurl dest="sysutils|demo|install"}" class="button button-continue">{i18n key="sysutils|demo.btn.install"}</a>

{else}

  {i18n key="sysutils|demo.error.alreadyInstalled" noEscape=1}
  <p></p>
  {i18n key="sysutils|demo.txt.accounts" noEscape=1}

{/if}
