{if $edit}<form method="POST" action="{copixurl dest="ressource||doRessourceSave"}">{/if}

{assign var="larg" value="600"}

{if $edit}
<div align="right">
<input style="width: 55px;" class="form_button" onclick="self.location='{if $res_id eq 0}{copixurl dest="ressource||getList" id=$annu_id}{else}{copixurl dest="ressource||getRessource" id=$res_id}{/if}'" type="button" value="{i18n key="ressource.liste.annuler"}" /> <input style="width: 75px;" class="form_button" type="submit" value="{i18n key="ressource.liste.enregistrer"}" />
</div>

<input type="hidden" name="res_id" value="{$res_id}">
<input type="hidden" name="annu_id" value="{$annu_id}">
{/if}


<table border="0" width="100%" cellspacing="10">

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.titre"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><input type="text" name="nom" value="{$ressource->ressources_nom}" class="form" style="width: {$larg}px;" maxlength="250" /></td>
	{else}
	<td><b>{$ressource->ressources_nom}</b></td>
	{/if}
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.url"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><input type="text" name="url" value="{$ressource->ressources_url}" class="form" style="width: {$larg}px;" maxlength="250" /></td>
	{else}
	<td><a target="_blank" href="{$ressource->ressources_url}">{$ressource->ressources_url}</a></td>
	{/if}
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.desc"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><textarea class="form" style="width: {$larg}px; height: 120px;" name="description" />{$ressource->ressources_description}</textarea></td>
	{else}
	<td>{$ressource->ressources_description}</td>
	{/if}
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.motsCle"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><input type="text" name="mots" value="{$ressource->ressources_mots}" class="form" style="width: {$larg}px;" maxlength="250" /></td>
	{else}
	<td>{$ressource->ressources_mots}</td>
	{/if}
</tr>

{if ! $edit}
<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.tags"}&nbsp;:</td>
	<td>
	{foreach from=$tags_list key=tag_key item=tag_item}
	<span style="font-size: {if $tag_item->nb < 5}1{elseif $tag_item->nb < 10}1.2{else}1.4{/if}em;">
	{if $tag_item->nb > 1}<a href="{copixurl dest="ressource||getTag" id=$annu_id tag=$tag_item->tag}" title="{$tag_item->nb}  {i18n key="ressource.liste.reponses"}">{/if}{$tag_item->tag}{if $tag_item->nb > 1}</a> ({$tag_item->nb}){/if}
	</span>
	{/foreach}
	</td>
</tr>
{/if}

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.auteur"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><input type="text" name="auteur" value="{$ressource->ressources_auteur}" class="form" style="width: {$larg}px;" maxlength="250" /></td>
	{else}
	<td>{if $ressource->ressources_auteur[0] eq "="}<a target="_blank" href="{$ressource->ressources_auteur|regex_replace:"/^=/":""}">{$ressource->ressources_auteur|regex_replace:"/^=/":""}</a>{else}{$ressource->ressources_auteur}{/if}</td>
	{/if}
</tr>


<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.auteurFiche"}&nbsp;:</td>
	{if $edit}
	<td CLASS="form_saisie"><input type="text" name="submit_user" value="{$ressource->ressources_submit_user}" class="form" style="width: {$larg}px;" maxlength="250" /></td>
	{else}
	<td>{if $ressource->ressources_submit_user eq "0"}
		Mich&egrave;le Drechsler
		{else}
		{$ressource->ressources_submit_user}
		{/if}
	</td>
	{/if}
</tr>

<!--
<tr>
	<td CLASS="form_libelle">Statut&nbsp;:</td>
	<td>
	<select name="type">
	<option value="0" SELECTED>Attente</option>
	<option value="1">Validé</option>
	</select>
	</td>
</tr>
-->

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.fonctions"}&nbsp;:</td>
	<td>
	{foreach from=$fonction_list item=fonction_val}
	{assign var=fonctions_id value=$fonction_val->fonctions_id}
	{assign var=selected value=$fonctionsel_list[$fonctions_id]}
	<LABEL for="fonction-{$fonctions_id}">
	<input {if $selected}checked{/if} {if !$edit}disabled{/if} type="checkbox" name="fonction[]" value="{$fonctions_id}" id="fonction-{$fonctions_id}">
	{if $selected}<b>{/if}{$fonction_val->fonctions_nom}{if $selected}</b>{/if}
	</LABEL><br />
	{/foreach}
	</td>
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="ressource.liste.contenus"}&nbsp;:</td>
	<td>
	{foreach from=$contenu_list item=contenu_val}
	{assign var=contenus_id value=$contenu_val->contenus_id}
	{assign var=selected value=$contenusel_list[$contenus_id]}
	<LABEL for="contenu-{$contenus_id}">
	<input {if $selected}checked{/if} {if !$edit}disabled{/if} type="checkbox" name="contenu[]" value="{$contenus_id}" id="contenu-{$contenus_id}">
	{if $selected}<b>{/if}{$contenu_val->contenus_nom}{if $selected}</b>{/if}
	</LABEL><br />
	{/foreach}
	</td>
</tr>


{foreach from=$niveau_list item=niveau_val}
<tr>
	<td CLASS="form_libelle">{$niveau_val->niveaux_nom}&nbsp;:</td>
	<td>
	{foreach from=$niveau_val->domaines item=domaine_val}
	{assign var=domaines_id value=$domaine_val->domaines_id}
	{assign var=selected value=$domainesel_list[$domaines_id]}
	<LABEL for="domaine-{$domaines_id}">
	<input {if $selected}checked{/if} {if !$edit}disabled{/if} type="checkbox" name="domaine[]" value="{$domaines_id}" id="domaine-{$domaines_id}">
	{if $selected}<b>{/if}{$domaine_val->domaines_nom}{if $selected}</b>{/if}
	</LABEL><br />
	{/foreach}
	</td>
</tr>
{/foreach}


</table>

{if $edit}
<div align="right">
<input style="width: 55px;" class="form_button" onclick="self.location='{if $res_id eq 0}{copixurl dest="ressource||getList" id=$annu_id}{else}{copixurl dest="ressource||getRessource" id=$res_id}{/if}'" type="button" value="{i18n key="ressource.liste.annuler"}" /> <input style="width: 75px;" class="form_button" type="submit" value="{i18n key="ressource.liste.enregistrer"}" />
</div>
{/if}


{if $edit}</form>{/if}