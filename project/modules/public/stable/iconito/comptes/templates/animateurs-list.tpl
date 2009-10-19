<p style="text-align: right;"><a class="button_like" href="{copixurl dest="comptes|animateurs|new"}">D&eacute;finir un nouvel animateur</a></p>

{if $ppo->animateurs}
	<table width="100%" class="liste comptes_animateurs comptes_animateurs_list">
	<tr>
		<th class="liste_th">Login</th>
		<th class="liste_th">Nom</th>
		<th class="liste_th">Pr&eacute;nom</th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_connexion.png"}"/></th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_tableaubord.png"}"/></th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_gestioncomptes.png"}"/></th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_visibleannuaire.png"}"/></th>
		
		<th class="liste_th">Groupes de villes</th>
		
		<th class="liste_th">Actions</th>
	</tr>
	{foreach from=$ppo->animateurs item=animateur name=animateurs}
		<tr class="{if $smarty.foreach.animateurs.first}first{/if}{if $smarty.foreach.animateurs.last} last{/if}">
			<td>{$animateur->user_infos.login}</td>
			<td>{$animateur->user_infos.nom}</td>
			<td>{$animateur->user_infos.prenom}</td>
			
			<td align="center">{if $animateur->can_connect}X{/if}</td>
			<td align="center">{if $animateur->can_tableaubord}X{/if}</td>
			<td align="center">{if $animateur->can_comptes}X{/if}</td>
			<td align="center">{if $animateur->is_visibleannuaire}X{/if}</td>
			<td>{foreach from=$animateur->grvilles item=grville name=grvilles}{if ! $smarty.foreach.grvilles.first}, {/if}{$grville->nom}{/foreach}</td>
			
			<td><a href="{copixurl dest="comptes|animateurs|edit" user_type=$animateur->user_type user_id=$animateur->user_id}">modifier</a></td>
		</tr>
	{/foreach}
	</table>
{else}
	<i>Aucun animateur...</i>
{/if}
