<a title="Ajouter" href="{copixurl dest="gestion|structures|modifier"}">{icon action="add"} Ajouter une structure</a>

{if count($ppo->structures)}
	<h2>Liste des structures</h2>
	<table class="list" border="0">
		<thead>
			<tr>
				<th>Id</th>
				<th>Type</th>
				<th>Nom</th>
				<th>Adresse</th>
				<th>CP</th>
				<th>Ville</th>
				<th>Tel1</th>
				<th>Tel2</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$ppo->structures item=item}
		<tr class="line{cycle values="0,1"}">
			<td align="center">{$item->id}</td>
			{assign var=type_id value=$item->type}
			<td align="center">{$item->type_nom|escape}</td>
			<td align="left">{$item->nom|escape}</td>
			<td align="left">{$item->adresse|escape}</td>
			<td align="left">{$item->cp|escape}</td>
			<td align="left">{$item->ville|escape}</td>
			<td align="left">{$item->tel1|escape}</td>
			<td align="left">{$item->tel2|escape}</td>
			<td align="center">
				<!-- <a title="D&eacute;tails" href="{copixurl dest="gestion|structures|detail" id=$item->id}">{icon action="details"}</a> -->
				<a title="Modifier" href="{copixurl dest="gestion|structures|modifier" id=$item->id}">{icon action="modify"}</a>
				<a title="Ouverture" href="{copixurl dest="gestion|structures|ouverture" id=$item->id}">Jours d'ouverture</a>
			</td>
		</tr>
		{/foreach}
		</tbody>
	</table>
{/if}

<a title="Ajouter" href="{copixurl dest="gestion|structures|modifier"}">{icon action="add"} Ajouter une structure</a>
