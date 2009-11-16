<div class="HIDDEN">&nbsp;</div>
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_fichesecoles.css"}" />

<div id="fichesecoles">


<div align="right" style="font-size:80%;"><a href="{copixurl dest="|fiche" id=$rEcole->numero}">{i18n key="annuaire|annuaire.fiche"}</a>{if $canModify} | <a href="{copixurl dest="fichesecoles|admin|form" id=$rEcole->numero}">{i18n key="kernel|kernel.btn.modify"}</a>{/if} | <a href="#" onClick="return hideAjaxDiv();">{i18n key="annuaire|annuaire.btn.close"}</a></div>

<table width="100%"><tr><td>

<div class="fiche">{i18n key="fichesecoles.fields.adresse"}</div>
<div>{$rEcole->num_rue} {$rEcole->num_seq} {$rEcole->adresse1} {if $rEcole->adresse2}<br/>{$rEcole->adresse2}{/if}<br/>{$rEcole->code_postal} {$rEcole->commune}
{if $rEcole->tel}<br/><img width="11" height="9" src="{copixresource path="img/annuaire/icon_tel.gif"}" alt="{i18n key="annuaire|annuaire.telephone"}" title="{i18n key="annuaire|annuaire.telephone"}" border="0" hspace="1" /> {$rEcole->tel}{/if}
</div>


{*
{if $rFiche->horaires}
	<div class="fiche">{i18n key="dao.fiches_ecoles.fields.horaires"}</div>
	<div>{$rFiche->horaires|nl2br}</div>
{/if}
*}



{*
<div class="fiche">{i18n key="fichesecoles.fields.classes"}</div>
<div>
{if $arClasses}

{foreach from=$arClasses item=class}
<DIV>
{if $class.enseignant}
<DIV CLASS="ecole_classe_enseignant">
{assign var=sep value=""}
{foreach from=$class.enseignant item=enseignant}{$sep}

{assign var=nom value=$enseignant.prenom|cat:" "|cat:$enseignant.nom}

{if $canViewEns}
	{user label=$nom|escape userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=1 escape=1}{else}
	{$nom|escape}{/if}{assign var=sep value=", "}{/foreach}
</DIV>
{/if}

<b><A HREF="{copixurl dest="|getAnnuaireClasse" classe=$class.id}">{$class.nom|escape}</A></b>
{$class.nom|escape}

</DIV>

{/foreach}

{/if}
</div>
*}


<div class="photo">{if $rFiche->photo}<p></p><img src="{copixurl dest="fichesecoles||photo" photo=$rFiche->photo|urlencode}" alt="{$rFiche->photo|urlencode}" border="0" width="140" />{/if}</div>


</td></tr></table>

</div>
	