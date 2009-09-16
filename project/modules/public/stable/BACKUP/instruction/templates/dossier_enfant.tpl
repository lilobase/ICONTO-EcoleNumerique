
{copixzone process=instruction|dossier_menu rDemande=$ppo->rDemande tab="enfant"}



<div  class="default-form">
<fieldset>
	<legend>Enfant</legend>
	
	
	<table>
	<tr>
	<td valign="top">
	
	
	<h2>Identit&eacute;</h2>

	
	{assign var=benef value=$ppo->rEnfant}
	

<table cellspacing="1" cellpadding="1" border="0" class="identite">
<tr>
	<td class="lib">{i18n key='kernel|dao.eleve.fields.nom' noEscape=true}</td>
	<td>{$benef->nom|escape}</td>

	<td class="lib" rowspan="3" valign="top">{i18n key='kernel|dao.eleve.fields.adresse1' noEscape=true}</td>
	<td rowspan="3" valign="top">{$benef->num_rue|escape} {$benef->num_seq|escape} {$benef->adresse1|escape} {$benef->adresse2|escape}<br/>
	{$benef->code_postal|escape} {$benef->commune|escape}
	</td>
</tr>
<tr>
	<td class="lib">{i18n key='kernel|dao.eleve.fields.prenom' noEscape=true}</td>
	<td>{$benef->prenom}{if $benef->prenom2}, {$benef->prenom2}{/if}</td>
</tr>
<tr>
	<td class="lib">{i18n key='kernel|dao.eleve.fields.date_nais' noEscape=true}</td>
	<td>{$benef->date_nais|date_age}</td>
</tr>
<tr>
	<td class="lib">{i18n key='kernel|dao.eleve.fields.id_sexe' noEscape=true}</td>
	<td><nobr><img src="{copixresource path="img/icon_sexe_s_`$benef->sexe_id`.gif"}" width="16" height="16" alt="{$benef->sexe_abrev}" title="{$benef->sexe_nom|escape}" />

 {$benef->sexe_nom}</nobr></td>
	<td colspan="2" align="right">
	
	
	{icon action="modify" assign="icon"}
		{assign var=libelle value=$icon|cat:' Modifier'}
		{copixzone process=kernel|linkgael action=modif_benef type=eleve type_id=$benef->id mode="txt" libelle=$libelle}
	</td>
</tr>
</table>


	
	</td>
	<td>&nbsp;</td>
	<td class="separator">&nbsp;</td>
	<td valign="top">
	<h2>Code-barres</h2>
	
	<i>A venir</i>
	</td>
	</tr>
	</table>
	
	
	
	
	
	
	
	
	
	
	
	
		
</fieldset>

</div>

