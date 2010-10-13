<a href="{copixurl dest="kernel|migration|alpi_blogalbum" go="1"}">Lancer la migration</a>
<table border="1">
	<tr>
		<th colspan="2">Ecole</th>
		<th colspan="2">Classe</th>
		<th colspan="3">Enseignant</th>
		<th colspan="3">Migration</th>
	</tr>
{foreach from=$ppo->a_migrer item=data}
	<tr>
		<td align="right">{$data->eco_id}</td>
		<td>{$data->eco_nom}</td>
		<td align="right">{$data->cla_id}</td>
		<td>{$data->cla_nom}</td>
		<td align="right">{$data->per_id}</td>
		<td>{$data->per_nom}</td>
		<td>{$data->per_prenom}</td>
		<td>{$data->from_mod_type}</td>
		<td>{$data->from_mod_id}</td>
		<td>{$data->to_malle_id}</td>
	</tr>
	
	{*
	<tr>
		<td colspan="10"><pre>{$data->data|print_r}</pre></td>
	</tr>
	*}
{/foreach}
</table>
