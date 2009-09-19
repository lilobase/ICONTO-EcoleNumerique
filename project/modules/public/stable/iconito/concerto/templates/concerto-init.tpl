<table border="1">
<tr><td>id_loc</td><td>id_ext</td><td>Nom</td><td>Prénom</td></tr>
{foreach from=$data item=item}
	<tr><td>{$item->numero}</td><td>{$item->id_ext}</td><td>{$item->nom}</td><td>{$item->prenom1}</td><td>{$item->login}</td><td>{$item->passwd}</td></tr>
{/foreach}
</table>