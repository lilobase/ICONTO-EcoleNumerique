{i18n key="comptes|comptes.colonne.nom"};{i18n key="comptes|comptes.colonne.prenom"};{i18n key="comptes|comptes.colonne.login"};{i18n key="comptes|comptes.colonne.password"};{i18n key="comptes|comptes.colonne.type"};{i18n key="comptes|comptes.colonne.localisation"}

{if $logins neq null}
{foreach from=$logins item=login}
{$login.nom};{$login.prenom};{$login.login};{$login.passwd};{$login.type_nom};{$login.node_nom}
{/foreach}
{/if}
