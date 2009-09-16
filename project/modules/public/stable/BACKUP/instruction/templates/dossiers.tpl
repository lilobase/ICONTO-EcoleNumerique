

<form class="default-form" id="form" method="get" onSubmit="submitonce(this);">
<div class="default-form">
<fieldset>
		<legend>{icon action="search" size="24x24"} Chercher un dossier </legend>
		<div>
<table border="0">
<tr>
	<td>Nom</td>
	<td>{inputtext name="nom" value=$ppo->filtre->nom maxlength="50" style="width:200px;"}</td>
	<td>&nbsp; &nbsp; Date de naissance</td>
	<td>{inputtext name="date_nais" value=$ppo->filtre->date_nais maxlength="10" style="width:90px;"} - Format : JJ/MM/AAAA</td>
	<td>&nbsp; &nbsp; Cr&egrave;che</td>
	<td></td>
</tr>
<tr>
	<td>Pr&eacute;nom</td>
	<td>{inputtext name="prenom" value=$ppo->filtre->prenom maxlength="50" style="width:200px;"}</td>
	<td>&nbsp; &nbsp; Statut</td>
	<td></td>
	<td colspan="2">&nbsp; &nbsp; <input type="submit" class="formSubmit" value="Chercher" /> <input type="button" class="" value="Nouveau dossier" onClick="self.location='{copixurl dest="instruction|dossiers|dossier_nouveau"}'" /></td>
</tr>
</table>

</div>
	</fieldset>
</div>
</form>



{if count($ppo->eleveResults)}


	{assign var=currentStructure value=0}
	
	{foreach from=$ppo->eleveResults item=item}
	
	{if $item->structure neq $currentStructure}
		{if $currentStructure}
			</table>
		{/if}
		<h2>{$item->structure_type_nom|escape} {$item->structure_nom|escape}</h2>
		<table class="list" border="0">
			<thead>
				<tr>
					<th>Id</th>
					<th>Statut</th>
					<th>Date dossier</th>
					<th>Sexe</th>
					<th>Nom</th>
					<th>Pr&eacute;nom</th>
					<th>Date naiss.</th>
					<th>Choix</th>
					<th>Notes</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
		{assign var=currentStructure value=$item->structure}
	{/if}
	
	
	<tr class="line{cycle values="0,1"}">
		<td align="center">{$item->id}</td>
		<td align="center">{$item->demande_etat_nom|escape}</td>
		<td align="center">{$item->date|date_format:"%d/%m/%Y"}</td>
		<td align="center"><img src="{copixresource path="img/icon_sexe_s_`$item->sexe_id`.gif"}" width="16" height="16" alt="{$item->sexe_abrev}" title="{$item->sexe_nom|escape}" /></td>
		<td align="center"><a title="D&eacute;tails" href="{copixurl dest="instruction|dossiers|dossier_demande" id=$item->id}">{$item->nom|escape}</a></td>
		<td align="center">{$item->prenom|escape}</td>
		<td align="center">{if $item->date_nais>0}{$item->date_nais|date_format:"%d/%m/%Y"}{else}A venir{/if}</td>
		<td align="center">{$item->ordre|escape}</td>
		<td align="center">{tooltip text=$item->notes|truncate:20|escape text_tooltip=$item->notes|escape}</td>
		<td align="center"><a title="D&eacute;tails" href="{copixurl dest="instruction|dossiers|dossier_demande" id=$item->id}">{icon action="details"}</a></td>
	</tr>
	{/foreach}
	</tbody>
</table>


{/if}



{if $ppo->filtre}
<p></p>

{copixzone process=kernel|legende actions="details"}

{/if}
