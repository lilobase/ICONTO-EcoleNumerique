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
				{if $animateur->can_comptes || ( $personnel->assistance && ( $animateur->can_connect || $ien ) ) }
		
				<tr class="{if $smarty.foreach.personnels.first}first{/if}{if $smarty.foreach.personnels.last} last{/if}">
					<td>{$personnel->login|escape}</td>
					<td>{$personnel->nom|escape}</td>
					<td>{$personnel->prenom|escape}</td>
					
					<td>{$personnel->nom_role|escape}</td>
					<td>{$ecole->eco_nom|escape}</td>
					<td>{$ecole->vil_nom|escape}</td>
					
					
					<td width="1" style="text-align: right; white-space: nowrap;">
					
						{if $animateur->can_connect || $ien}
							{if $personnel->assistance}<a href="{copixurl dest="assistance||switch" login=$personnel->login}">Connexion</a>{else}<span style="visibility: hidden;">Connexion</span>{/if}
						{/if}
						{if ( $ien || ( $personnel->assistance && $animateur->can_connect ) ) && $animateur->can_comptes} :: {/if}
						{if $animateur->can_comptes}
							<a href="{copixurl dest="comptes||getUser" login=$personnel->login from="assistance"}">Mot de passe</a>
						{/if}
					</td>
				</tr>
				{/if}
		
			{/foreach}
		{/foreach}
	{/foreach}
	</table>
{else}
	<i>Aucun utilisateur...</i>
{/if}
