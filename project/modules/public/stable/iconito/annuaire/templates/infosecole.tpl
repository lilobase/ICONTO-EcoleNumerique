
<div id="ecole_infos_bloc">

<div class="lucien"></div>

<div id="ecole_infos" class="block">

<div class="right">

<form name="formGo" id="formGo" action="{copixurl dest="annuaire||getAnnuaireEcole"}" method="get">
{$comboecoles}
<input type="submit" value="{i18n key="annuaire.btn.go"}" class="button" />
</form>

</div>

<b>{$ecole.nom|escape}</b>{if $ecole.desc} ({$ecole.desc|escape}){/if}<br />

    

{if $ecole.ALL->eco_num_rue OR $ecole.ALL->eco_num_seq OR $ecole.ALL->eco_adresse1 OR $ecole.ALL->eco_code_postal OR $ecole.ALL->eco_commune}


    

  {if $ecole.ALL->eco_num_rue OR $ecole.ALL->eco_num_seq}
    {$ecole.ALL->eco_num_rue}{$ecole.ALL->eco_num_seq}, 
  {/if}
   {$ecole.ALL->eco_adresse1}<br />
  {$ecole.ALL->eco_code_postal} {$ecole.ALL->eco_commune|escape}<br />
{/if}
{if $ecole.ALL->eco_tel}
<img width="11" height="9" src="{copixresource path="img/annuaire/icon_tel.gif"}" alt="{i18n key="annuaire.telephone"}" title="{i18n key="annuaire.telephone"}" /> {$ecole.ALL->eco_tel}<br />
{/if}
{if 0 && $ecole.ALL->eco_web}
<a target="_blank" href="{$ecole.ALL->eco_web}" title="{$ecole.ALL->eco_web}">{$ecole.ALL->eco_web|truncate:43:"...":true}</a><br />
{/if}
{if $ecole.blog}<a title="{i18n key="annuaire.blog"}" href="{$ecole.blog}">{i18n key="annuaire.blog"}</a><a href="{$ecole.blog}" target="_blank"><img alt="{i18n key="public|public.openNewWindow"}" title="{i18n key="public|public.openNewWindow"}" width="12" height="12" src="{copixresource path="img/public/open_window.png"}" /></a><br />{/if}
<a title="{i18n key="annuaire.fiche"}" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id}">{i18n key="annuaire.fiche"}</a><a class="fancybox" href="{copixurl dest="fichesecoles||fiche" id=$ecole.id popup=1}"><img alt="{i18n key="public|public.openPopup"}" title="{i18n key="public|public.openPopup"}" width="12" height="12" src="{copixresource path="img/public/open_popup.png"}" /></a><br />

{ if $ecole.directeur }
<h2>{i18n key="annuaire.directeur"}</h2>
<div>
{foreach from=$ecole.directeur item=directeur}

{user label=$directeur.prenom|cat:" "|cat:$directeur.nom userType=$directeur.type userId=$directeur.id login=$directeur.login dispMail=$canWriteUSER_DIR}

<br />
{/foreach}
</div>

{/if}

{ if $ecole.administratif }
<h2>{i18n key="annuaire.administratif"}</h2>
<div>
{foreach from=$ecole.administratif item=item}

{user label=$item.prenom|cat:" "|cat:$item.nom userType=$item.type userId=$item.id login=$item.login dispMail=$canWriteUSER_ADM}

<br />
{/foreach}
</div>

{/if}


{ if $classes }

<h2>{i18n key="annuaire|annuaire.classesEnseignants"}</h2>

{foreach from=$classes item=class}
<div>
{if $class.enseignant}
<div class="ecole_classe_enseignant">
{assign var=sep value=""}
{foreach from=$class.enseignant item=enseignant}{$sep}

{user label=$enseignant.prenom|cat:" "|cat:$enseignant.nom userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=$canWriteUSER_ENS}

{assign var=sep value=", "}{/foreach}
</div>
{/if}

<strong><a href="{copixurl dest="|getAnnuaireClasse" classe=$class.id}">{$class.nom}</a></strong>

</div>

{/foreach}

{/if}

</div>

<div id="div_user"></div>

</div>
