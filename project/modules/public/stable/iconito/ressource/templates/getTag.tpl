{literal}<link rel="stylesheet" type="text/css" href="styles/module_ressource.css" />{/literal}

<p>{i18n key="ressource.liste.rechercheTag"}</p>

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
				<td ALIGN="LEFT"><a href="{$ressource_item->ressources_url}" target-"_blank">{$ressource_item->ressources_nom}</a>
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

{if $tags_list}
<p>Voir aussi :
{foreach from=$tags_list key=tag_key item=tag_item}
<span style="font-size: {if $tag_item->nb < 5}1{elseif $tag_item->nb < 10}1.2{else}1.4{/if}em;">
<a href="{copixurl dest="ressource||getTag" id=$annu_id tag=$tag_item->tag}" title="{$tag_item->nb}  {i18n key="ressource.liste.reponses"}">{$tag_item->tag}</a> {if $tag_item->nb > 1}({$tag_item->nb}){/if}
</span>
{/foreach}
</p>
{/if}
