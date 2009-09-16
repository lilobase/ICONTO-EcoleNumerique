
<p><a title="Ajouter" href="{copixurl dest="instruction|commissions|form"}">{icon action="add"} Ajouter une commission</a></p>

<table class="list" border="0">
	<thead>
		<tr>
			<th>Id</th>
			<th>Date</th>
			<th>Dossiers</th>
			<th>Cr&eacute;ation et auteur</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>

{foreach from=$ppo->list item=item}
<tr class="line{cycle values="0,1"}">
		<td align="center">{$item->id}</td>
		<td align="center">{$item->date|date_format:"%d/%m/%Y"}</td>
		<td align="center">{$item->nb_dossiers}</td>
		<td align="center">{$item->date_saisie|date_format:"%d/%m/%Y %H:%M"} - {$item->auteur|escape}</td>
		<td align="center">{if $item->est_finie}<a title="Bilan" href="{copixurl dest="commissions|bilan" id=$item->id}">{icon action="stats"}</a>{else}<a title="D&eacute;tails" href="{copixurl dest="commissions|details" id=$item->id}">{icon action="details"}</a>{/if}</td>
	</tr>
{/foreach}
</tbody>
</table>
