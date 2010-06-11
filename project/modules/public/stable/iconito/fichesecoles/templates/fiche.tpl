
<div id="fichesecoles">

<table>
<tr>
<td class="pratique">

<div class="photo">{if $rFiche->photo}<img src="{copixurl dest="fichesecoles||photo" photo=$rFiche->photo|urlencode}" alt="{$rFiche->photo|urlencode}" border="0" />{else}<img width="230" height="180" src="{copixresource path="img/fichesecoles/no_photo.gif"}" alt="{i18n key="fichesecoles.fields.nophoto"}" title="{i18n key="fichesecoles.fields.nophoto"}" />{/if}</div>

<p></p>
<div class="fiche">{i18n key="fichesecoles.fields.adresse"}</div>
<div>{$rEcole->num_rue|escape} {$rEcole->num_seq|escape} {$rEcole->adresse1|escape} {if $rEcole->adresse2}<br/>{$rEcole->adresse2|escape}{/if}<br/>{$rEcole->code_postal|escape} {$rEcole->commune|escape}
{if $rEcole->tel|escape}<br/><img width="11" height="9" src="{copixresource path="img/annuaire/icon_tel.gif"}" alt="{i18n key="annuaire|annuaire.telephone"}" title="{i18n key="annuaire|annuaire.telephone"}" border="0" hspace="1" /> {$rEcole->tel|escape}{/if}
</div>

{assign var=mapWidth value="230px"}
{assign var=mapHeight value="150px"}

<div class="fiche">{i18n key="fichesecoles.fields.plan"}</div>
{if $rEcole->coords}
<div id="googleMap" style="width:{$mapWidth};height:{$mapHeight};"></div>
{else}
<div style="font-style:italic;font-size:0.8em;">{i18n key="fichesecoles.fields.noplan"}</div>
{/if}
<div><a target="_blank" href="http://maps.google.fr/maps?q={$rEcole->googleAdresse|urlencode}">{i18n key="fichesecoles.fields.viewplan"}</a></div>


</td>
<td class="texte">

{if $rFiche->zone_ville_titre && $rFiche->zone_ville_texte}
	<div class="ficheVille">
	<div class="fiche">{$rFiche->zone_ville_titre|escape}</div>
	<div>{$rFiche->zone_ville_texte|escape|nl2br}</div>
	</div>
{/if}


{if $rFiche->horaires}
	<div class="horaires">
	<img class="icon" alt="{i18n key="dao.fiches_ecoles.fields.horaires"}" title="{i18n key="dao.fiches_ecoles.fields.horaires"}" border="0" width="32" height="32" src="{copixresource path="img/fichesecoles/icon_horaires.gif"}" />
	<div class="fiche">{i18n key="dao.fiches_ecoles.fields.horaires"}</div>
	<div>{$rFiche->horaires|escape|nl2br}</div>
	</div>
{/if}


<div class="classes">
<img class="icon" alt="{i18n key="fichesecoles.fields.classes"}" title="{i18n key="fichesecoles.fields.classes"}" border="0" width="32" height="32" src="{copixresource path="img/fichesecoles/icon_classes.gif"}" />
<div class="fiche">{i18n key="fichesecoles.fields.classes"}</div>
<div>

<DIV CLASS="ecole_classe_enseignant">
{foreach from=$rEcole->directeur item=directeur}
{assign var=nom value=$directeur.prenom|cat:" "|cat:$directeur.nom}
{if $canViewDir}{user label=$nom|escape userType=$directeur.type userId=$directeur.id login=$directeur.login dispMail=$canWriteDir escape=1}{else}
	{$nom|escape}{/if}{assign var=sep value=", "}{/foreach}
</DIV>
{i18n key="fichesecoles.fields.direction"} :
{assign var=sep value=""}
<br/>

{if $arClasses}

{foreach from=$arClasses item=class}
<DIV>
{if $class.enseignant}
<DIV CLASS="ecole_classe_enseignant">
{assign var=sep value=""}
{foreach from=$class.enseignant item=enseignant}{$sep}

{assign var=nom value=$enseignant.prenom|cat:" "|cat:$enseignant.nom}

{if $canViewEns}
	{user label=$nom|escape userType=$enseignant.type userId=$enseignant.id login=$enseignant.login dispMail=$canWriteEns escape=1}{else}
	{$nom|escape}{/if}{assign var=sep value=", "}{/foreach}
</DIV>
{/if}
{*<b><A HREF="{copixurl dest="|getAnnuaireClasse" classe=$class.id}">{$class.nom|escape}</A></b>*}

{assign var=sep value=""}
{foreach from=$class.niveaux item=niveau}{$sep}
{$niveau->niveau_court}
{assign var=sep value=" - "}
{foreachelse}
	{$class.nom|escape}
{/foreach}
</DIV>

{/foreach}

{/if}
</div>
</div>

<br clear="all" />


{if $rEcole->blog || $arClassesBlogs}
	<div style="position:relative;">
	<div class="blog">
	
	<div id="ficheblogs" style="display:none;"></div>
	
	<img class="icon" alt="{i18n key="fichesecoles.fields.viewblogs"}" title="{i18n key="fichesecoles.fields.viewblogs"}" border="0" width="56" height="62" src="{copixresource path="img/fichesecoles/icon_blog.gif"}" />
	<div><a href="javascript:ficheViewBlogs({$rEcole->numero},'');" title="{i18n key="fichesecoles.fields.viewblogs"}">{i18n key="fichesecoles.fields.viewblogs"}</a></div>
	
	</div>
	</div>
{/if}

{if $rFiche->zone1_titre && $rFiche->zone1_texte}
	<div class="fiche">{$rFiche->zone1_titre|escape}</div>
	<div>{$rFiche->zone1_texte|escape|nl2br}</div>
{/if}

{if $rFiche->zone2_titre && $rFiche->zone2_texte}
	<div class="fiche">{$rFiche->zone2_titre|escape}</div>
	<div>{$rFiche->zone2_texte|escape|nl2br}</div>
{/if}

{if $rFiche->zone3_titre && $rFiche->zone3_texte}
	<div class="fiche">{$rFiche->zone3_titre|escape}</div>
	<div>{$rFiche->zone3_texte|escape|nl2br}</div>
{/if}

{if $rFiche->zone4_titre && $rFiche->zone4_texte}
	<div class="fiche">{$rFiche->zone4_titre|escape}</div>
	<div>{$rFiche->zone4_texte|escape|nl2br}</div>
{/if}


</td>
</tr>
</table>

</div>
	