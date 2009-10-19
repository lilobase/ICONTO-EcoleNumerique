{if $users}
	<table width="100%" class="liste assistance">
	<tr>
		<th class="liste_th">Login</th>
		<th class="liste_th">Nom</th>
		<th class="liste_th">Pr&eacute;nom</th>
		<th class="liste_th">Role</th>
		<th class="liste_th">Ecole</th>
		<th class="liste_th">Ville</th>
		
		<th class="liste_th">Actions</th>
	</tr>
	{foreach from=$users item=ville name=villes}
		{foreach from=$ville item=ecole name=ecoles}
			{foreach from=$ecole->personnels item=personnel name=personnels}
		
		
				<tr class="{if $smarty.foreach.personnels.first}first{/if}{if $smarty.foreach.personnels.last} last{/if}">
					<td>{$personnel->login|escape}</td>
					<td>{$personnel->nom|escape}</td>
					<td>{$personnel->prenom|escape}</td>
					
					<td>{$personnel->nom_role|escape}</td>
					<td>{$ecole->eco_nom|escape}</td>
					<td>{$ecole->vil_nom|escape}</td>
					
					
					<td width="1" style="white-space: nowrap;">
						<a href="{copixurl dest="assistance||switch" login=$personnel->login}">Connexion</a>
						::
						<a href="{copixurl dest="comptes||getUser" login=$personnel->login}">Mot de passe</a>
					</td>
				</tr>
		
		
			{/foreach}
		{/foreach}
	{/foreach}
	</table>
{else}
	<i>Aucun utilisateur...</i>
{/if}
