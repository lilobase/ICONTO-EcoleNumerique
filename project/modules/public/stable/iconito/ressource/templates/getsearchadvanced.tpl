{if $ressource_list neq null}

<table border="0" class="liste" align="CENTER" cellspacing="2" cellpadding="2">
	<tr>
		<th class="liste_th">{i18n key="ressource.liste.titre"}</th>
		<!-- <th class="liste_th" width="1">Date</th> -->
		<th CLASS="liste_th" width="1">{i18n key="ressource.liste.fiche"}</th>
	</tr>

		{counter assign="i" name="i"}
		
		{foreach from=$ressource_list item=ressource_item}
			{counter name="i"}
			<tr class="list_line{math equation="x%2" x=$i}">
				<td align="LEFT"><a href="{$ressource_item->url}" target="_blank"><b>{$ressource_item->nom}</b></a>
				<div class="ressource_description">{$ressource_item->description}
				<!-- <br/>[ <a class="ressource_link_fiche" href="{copixurl dest="|getRessource" id=$ressource_item->id}">Voir la fiche de la ressource</a> ] -->
				</div>
				</td>
				<!-- <td><nobr>{$ressource_item->submit_date|datei18n:"date_short_time"}</nobr></td> -->
				<td><nobr><a href="{copixurl dest="|getRessource" id=$ressource_item->id}">{i18n key="ressource.liste.fichenb" 1=$ressource_item->id}</a></nobr></td>
			</tr>
		{/foreach}
</table>

{else}

<div align="center"><b>
	{if $search neq 1}
		{i18n key="ressource.getsearchadvanced.intro"}
	{elseif !$params}
		{i18n key="ressource.getsearchadvanced.noparam"}
	{else}
		{i18n key="ressource.getsearchadvanced.noresult"}
	{/if}
</b></div>
	
{/if}


<div><br/>

<form method="POST" action="{copixurl dest="ressource||getSearchAdvanced"}">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="search" value="1" />
{assign var="larg" value="600"}



<table border="0" width="" cellspacing="3" align="center">


<tr>
	<td class="form_libelle">{i18n key="ressource.liste.motcle"} :</td>
	<td class="form_saisie"><input type="text" name="mot" value="{$mot}" class="form" style="width:200px;" maxlength="150"> {i18n key="ressource.liste.motcle_desc"}</td>
</tr>

<tr>
	<td class="form_libelle">{i18n key="ressource.liste.fonction"} :</td>
	<td>
		<select name="fonctions" class="form" style="width:300px;">
		<option value="">{i18n key="ressource.recherche.nonDefini"}</option>
		{foreach from=$fonction_list item=fonction_val}
		{assign var=fonctions_id value=$fonction_val->fonctions_id}
		{assign var=selected value=$fonctionsel_list[$fonctions_id]}
		<option value="{$fonctions_id}" {if $selected}SELECTED{/if}>{$fonction_val->fonctions_nom|escape}</option>
		{/foreach}
		</select>
	</td>
</tr>

<tr>
	<td class="form_libelle">{i18n key="ressource.liste.contenu"} :</td>
	<td>
		<select name="contenus" class="form" style="width:300px;">
		<option value="">{i18n key="ressource.recherche.nonDefini"}</option>
		{foreach from=$contenu_list item=contenu_val}
		{assign var=contenus_id value=$contenu_val->contenus_id}
		{assign var=selected value=$contenusel_list[$contenus_id]}
		<option value="{$contenus_id}" {if $selected}SELECTED{/if}>{$contenu_val->contenus_nom|escape}</option>
		{/foreach}
		</select>
	</td>
</tr>

{*
<tr>
	<td class="form_libelle">{i18n key="ressource.liste.licence"} :</td>
	<td>
		<select name="licences" class="form" style="width:300px;">
		<option value="">{i18n key="ressource.recherche.nonDefini"}</option>
		{foreach from=$licence_list item=licence_val}
		{assign var=licences_id value=$licence_val->licences_id}
		{assign var=selected value=$licencesel_list[$licences_id]}
		<option value="{$licence_id}" {if $selected}SELECTED{/if}>{$licence_val->licences_nom|escape}</option>
		{/foreach}
		</select>
	</td>
</tr>
*}

{if count($niveau_list)}

	{foreach from=$niveau_list item=niveau_val}
	
	{assign var=toutes value=""}
	<tr>
	<td class="form_libelle">{$niveau_val->niveaux_nom} :</td>
	<td>
	<select name="domaines[]" class="form" style="width:500px;">
	<option value="">{i18n key="ressource.recherche.nonDefini"}</option>
	{foreach from=$niveau_val->domaines item=domaine_val}
	{assign var=domaines_id value=$domaine_val->domaines_id}
	{assign var=selected value=$domainesel_list[$domaines_id]}
	<option value="{$domaines_id}" {if $selected}SELECTED{/if}>{$domaine_val->domaines_nom|escape}</option>
	{assign var=toutes value=$toutes|cat:"$domaines_id,"}
	{/foreach}
	{assign var=selected value=$domainesel_list[$toutes]}
	<option value="{$toutes}" {if $selected}SELECTED{/if}>{i18n key="ressource.liste.fonction_mini"}</option>
	</select>

	</td></tr>

	{/foreach}

{/if}

	<tr><td colspan="2" CLASS="form_submit">
	<input style="" class="form_button" onclick="self.location='{copixurl dest="|getList" id=$id}'" type="button" value="{i18n key="ressource.liste.annuler"}" />
	<input style="" class="form_button" type="submit" value="{i18n key="ressource.liste.chercher"}" />
</td></tr>
</table>

</form>

<div style="margin:20px 80px 0px 80px; font-size:75%;">{i18n key="ressource.form.mention"}</div>

</div>