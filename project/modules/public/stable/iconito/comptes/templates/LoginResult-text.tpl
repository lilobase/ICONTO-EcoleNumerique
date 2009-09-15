{i18n key="comptes|comptes.strings.dateliste" 1=$smarty.now|datei18n:"date_short_time"}

{i18n key="comptes|comptes.colonne.nom"} {i18n key="comptes|comptes.colonne.prenom"} {i18n key="comptes|comptes.colonne.login"} {i18n key="comptes|comptes.colonne.password"} {i18n key="comptes|comptes.colonne.type"} {i18n key="comptes|comptes.colonne.localisation"}
=====================  =====================  =============  =============  ==========  ==============
{if $logins neq null}
{foreach from=$logins item=login}
{$login.nom|str_pad:22} {$login.prenom|str_pad:22} {$login.login|str_pad:14} {$login.passwd|str_pad:14} {$login.type_nom|str_pad:11} {$login.node_nom}
{/foreach}
{/if}
