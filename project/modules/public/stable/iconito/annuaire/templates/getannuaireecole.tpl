<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_annuaire.css"}" />
{literal}<SCRIPT LANGUAGE="Javascript1.2" SRC="js/annuaire/annuaire.js"></SCRIPT>{/literal}

{$infosecole}

{$infosclasse}

{if !$infosclasse}
{i18n key="annuaire.noClasses"}
{/if}

<div id="div_user"></div>

<br clear="all" />
