{literal}<SCRIPT LANGUAGE="Javascript1.2" SRC="js/annuaire/annuaire.js"></SCRIPT>{/literal}

<a href="javascript:{if $profil}open_annuaire_profil('{$field}','{$profil}');{else}open_annuaire('{$field}');{/if}"><IMG SRC="img/annuaire/linkPopup.gif" HEIGHT="12" WIDTH="12" BORDER="0" HSPACE="3" />{i18n key="annuaire.linkPopup"}</a>