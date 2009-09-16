

<table>

{foreach from=$list item=r}

<tr>
	<td valign="top">

<table border="0" class="identite">
	<tr>
		<td class="lib">Nom</td>
		<td>{$r->nom|strtoupper|escape}</td>
		<td align="right" colspan="2">
		{icon action="modify" assign="icon"}
		{assign var=libelle value=$icon|cat:' Modifier'}
		{copixzone process=kernel|linkgael action="modif_responsable" type_id=$r->id mode="txt" libelle=$libelle}

		</td>
	</tr>
	{if $r->nom_jf}
	<tr>
		<td class="lib">Nom de jeune fille</td>
		<td>{$r->nom_jf|strtoupper|escape}</td>
	</tr>
	{/if}
	<tr>
		<td class="lib">Pr&eacute;nom</td>
		<td>{$r->prenom|escape}</td>
	</tr>
	<tr>
		<td class="lib">Date de naiss.</td>
		<td>{$r->date_nais|date_format:"%d/%m/%Y"}</td>
		<td class="lib">T&eacute;l. dom.</td>
		<td>{$r->tel_dom|escape}</td>
	</tr>
	<tr>
		<td class="lib">Sexe</td>
		<td><img src="{copixresource path="img/icon_sexe_s_`$r->sexe_id`.gif"}" width="16" height="16" alt="{$r->sexe_abrev}" title="{$r->sexe_nom|escape}" /> {$r->sexe_nom|escape}</td>
		<td class="lib">T&eacute;l. pro.</td>
		<td>{$r->tel_pro|escape}</td>
	</tr>
	<tr>
		<td class="lib">Adresse</td>
		<td>{$r->num_rue|escape}{$r->num_seq|escape} {$r->adresse1|escape} {if $r->adresse2}<br/>{$r->adresse2|escape}{/if}
		{if $r->commune}<br/>{$r->code_postal|escape} {$r->commune|escape}{/if}
</td>
		<td class="lib">T&eacute;l. port.</td>
		<td>{$r->tel_gsm|escape}</td>
	</tr>
	
</table>
	
	
	
	</td>
	<td>&nbsp;</td>
	<td class="separator">&nbsp;</td>
	<td valign="top">
	
	{copixzone process=instruction|infos_benef_groupes values=$r->infosValeurs benef_type=$r->responsables_type suffixe="[`$r->responsables_type`|`$r->id`]"}
	
	</td>
</tr>

{/foreach}

</table>


