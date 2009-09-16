<form class="default-form" method="post" onSubmit="submitonce(this);">
<input type="hidden" name="id" value="{$ppo->rDemande->id}" />
<input type="hidden" name="submit" value="1" />
<p>Changer de statut : {copixzone process=kernel|combo_demande_etat name="new_etat" selected=$ppo->rDemande->etat} <input type="submit" class="formSubmit" value="Changer" /></p>
</form>

<table class="list" border="0">
	<thead>
		<tr>
			<th>Date</th>
			<th>Statut</th>
			<th>Auteur</th>
		</tr>
	</thead>
	<tbody>
	{foreach from=$ppo->list item=item}
	<tr class="line{cycle values="0,1"}">
		<td align="center">{$item->date|date_format:"%d/%m/%Y %H:%M"}</td>
		<td align="center">{$item->demande_etat_nom|escape}</td>
		<td align="center">{$item->auteur|escape}</td>
	</tr>
	{/foreach}
	</tbody>
</table>


