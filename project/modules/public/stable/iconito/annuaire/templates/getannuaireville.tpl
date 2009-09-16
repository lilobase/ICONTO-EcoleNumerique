<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_annuaire.css"}" />
{literal}<SCRIPT LANGUAGE="Javascript1.2" SRC="js/annuaire/annuaire.js"></SCRIPT>{/literal}

<div id="ecole_infos_bloc">

<div><img class="coude" src="{copixresource path="img/groupe/lucien_coude.gif"}" /></div>

<div id="ecole_infos">

<div align="right">

<form name="formGo" id="formGo" action="" method="get">
<input type="hidden" name="module" value="annuaire" />
<input type="hidden" name="action" value="getAnnuaireVille" />
{$combovilles}
<input type="submit" value="{i18n key="annuaire.btn.go"}" class="form_button" />
</form>

</div>

{if $ville.blog}<div style="text-align:right;"><a title="{$ville.blog}" href="{$ville.blog}">{i18n key="annuaire.blogVille"}</a><a href="{$ville.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a></div>{/if}

{ if $agents }
<h2>{i18n key="annuaire.agents"}</h2>
<div>
{foreach from=$agents item=agent}

{user label=$agent.prenom|cat:" "|cat:$agent.nom userType=$agent.type userId=$agent.id login=$agent.login dispMail=1}

<br/>
{/foreach}
</div>
{/if}

</div>

<div id="div_user"></div>

</div>





{if $ecoles}

{assign var=current_type value=""}

<div id="eleves">

{foreach from=$ecoles item=ecole}
<div style="padding-bottom:2px;">

{if $ecole.type <> $current_type}
<!--<h3>{$ecole.type}</h3>-->
{assign var=current_type value=$ecole.type}
{/if}

<div class="ecole_web">{if $ecole.blog}<a title="{$ecole.blog}" href="{$ecole.blog}">{i18n key="annuaire.blog"}</a><a href="{$ecole.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a>{/if}

<a title="{i18n key="annuaire.fiche"}" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{i18n key="annuaire.fiche"}</a><a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}" onClick="return ajaxFicheEcole({$ecole.id});"><img alt="{i18n key="public|public.openPopup"}" title="{i18n key="public|public.openPopup"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_popup.png"}" hspace="1" /></a>

{if $ecole.web}<a target="_blank" title="{$ecole.web}" href="{$ecole.web}">{i18n key="annuaire.siteWeb"}</a>{/if}



</div>

<a href="{copixurl dest="|getAnnuaireEcole" ecole=$ecole.id}">{$ecole.nom}{if $ecole.type} ({$ecole.type}){/if}</a>

{if $ecole.directeur}
{assign var=sep value=""}
({foreach from=$ecole.directeur item=directeur}{$sep}{$directeur.prenom} {$directeur.nom|upper}{assign var=sep value=", "}{/foreach})
{/if}

</div>
{/foreach}

</div>
{else}
{i18n key="annuaire.noEcoles"}
{/if}



<br clear="all" />