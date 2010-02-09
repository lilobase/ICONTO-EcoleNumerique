<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_ressource.css"}" />

<p>{i18n key="ressource.liste.rechercheTxt"}</p>

<div align="right" style="padding: 10px;">
<FORM method="POST" action="{copixurl dest="|getSearchAdvanced"}">
<input type="hidden" name="id" value="{$id}">
{i18n key="ressource.title.recherche"} : <INPUT TYPE="mot" NAME="mot" CLASS="form" STYLE="width:130px;"> <input style="width: 30px;" class="form_button" type="submit" value="{i18n key="ressource.form.ok"}" />
</FORM>
</div>

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<!-- <th CLASS="liste_th" width="1">{i18n key="ressource.liste.id"}</th> -->
		<th CLASS="liste_th">{i18n key="ressource.liste.titre"}</th>
		<!-- <th CLASS="liste_th" width="1">{i18n key="ressource.liste.valide"}</th> -->
		<!-- <th CLASS="liste_th" width="1">{i18n key="ressource.liste.date"}</th> -->
		<th CLASS="liste_th" width="1">{i18n key="ressource.liste.fiche"}</th>

	</tr>
	{if $ressource_list neq null}
		{counter assign="i" name="i"}
		
		{foreach from=$ressource_list item=ressource_item}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<!-- <td><a href="{copixurl dest="|getRessource" id=$ressource_item->ressources_id}">#{$ressource_item->ressources_id}</a></td> -->
				<td ALIGN="LEFT"><a href="{$ressource_item->ressources_url}" target="_blank">{$ressource_item->ressources_nom}</a>
				<div class="ressource_description">{$ressource_item->ressources_description}</div>
				</td>
				<!-- <td ALIGN="LEFT">{if $ressource_item->ressources_valid_date}Oui{else}Non{/if}</td> -->
				<!-- <td><nobr>{$ressource_item->ressources_submit_date|datei18n:"date_short_time"}</nobr></td> -->
				<td><nobr><a href="{copixurl dest="|getRessource" id=$ressource_item->ressources_id}">{i18n key="ressource.liste.fichenb" 1=$ressource_item->ressources_id}</a></nobr></td>
			</tr>
		{/foreach}

	{else}
		<tr>
			<td COLSPAN="4">{i18n key="ressource.liste.vide"}</td>
		</tr>
	{/if}

</table>
