
<DIV ID="ecole_infos_bloc">

<DIV><IMG CLASS="coude" src="{copixresource path="img/groupe/lucien_coude.gif"}" /></DIV>

<DIV ID="ecole_infos">

<DIV ALIGN="RIGHT">

<form name="formGo" id="formGo" action="" method="get">
<input type="hidden" name="module" value="annuaire" />
<input type="hidden" name="action" value="getAnnuaireEcole" />
{$comboecoles}
<input type="submit" value="{i18n key="annuaire.btn.go"}" class="form_button" />
</form>

</DIV>

<b>{$ecole.nom}</b>{if $ecole.desc} ({$ecole.desc}){/if}<br/>
{$ecole.ALL->eco_num_rue}{$ecole.ALL->eco_num_seq}, {$ecole.ALL->eco_adresse1}<br/>
{$ecole.ALL->eco_code_postal} {$ecole.ALL->eco_commune}<br/>
{if $ecole.ALL->eco_tel}
<img width="11" height="9" src="{copixresource path="img/annuaire/icon_tel.gif"}" alt="{i18n key="annuaire.telephone"}" title="{i18n key="annuaire.telephone"}" border="0" hspace="1" /> {$ecole.ALL->eco_tel}<br/>
{/if}
{if 0 && $ecole.ALL->eco_web}
<A TARGET="_BLANK" HREF="{$ecole.ALL->eco_web}" TITLE="{$ecole.ALL->eco_web}">{$ecole.ALL->eco_web|truncate:43:"...":true}</A><br/>
{/if}
{if $ecole.blog}<a title="{i18n key="annuaire.blog"}" href="{$ecole.blog}">{i18n key="annuaire.blog"}</a><a href="{$ecole.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" hspace="4" /></a><br/>{/if}
<a title="{i18n key="annuaire.fiche"}" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{i18n key="annuaire.fiche"}</a><a href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}" onClick="return ajaxFicheEcole({$ecole.id});"><img alt="{i18n key="public|public.openPopup"}" title="{i18n key="public|public.openPopup"}" border="0" width="12" height="12" src="{copixresource path="img/public/open_popup.png"}" hspace="4" /></a><br/>

{ if $ecole.directeur }
<H2>{i18n key="annuaire.directeur"}</H2>
<DIV>
{foreach from=$ecole.directeur item=directeur}

{user label=$directeur.prenom|cat:" "|cat:$directeur.nom userType=$directeur.type userId=$directeur.id login=$directeur.login dispMail=1}

<br/>
{/foreach}
</DIV>

{/if}

{ if $ecole.administratif }
<H2>{i18n key="annuaire.administratif"}</H2>
<DIV>
{foreach from=$ecole.administratif item=item}

{user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id login=$item.login dispMail=1}

<br/>
{/foreach}
</DIV>

{/if}


{ if $classes }

<H2>{i18n key="annuaire|annuaire.classesEnseignants"}</H2>

{foreach from=$classes item=class}
<DIV>
{if $class.enseignant}
<DIV CLASS="ecole_classe_enseignant">
{assign var=sep value=""}
{foreach from=$class.enseignant item=enseignant}{$sep}

{user label=$enseignant.prenom|cat:" "|cat:$enseignant.nom userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=1}

{assign var=sep value=", "}{/foreach}
</DIV>
{/if}

<b><A HREF="{copixurl dest="|getAnnuaireClasse" classe=$class.id}">{$class.nom}</A></b>

</DIV>

{/foreach}

{/if}

</DIV>

<DIV ID="div_user"></DIV>

</DIV>

