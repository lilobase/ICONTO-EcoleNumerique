<p class="right"><a href="{copixurl dest="comptes|ien|new"}" class="button button-add">{i18n key="comptes.menu.new_ien" noEscape=1}</a></p>

{if $ppo->iens}
	<table width="100%" class="liste comptes_animateurs comptes_animateurs_list">
	<tr>
		<th class="liste_th">Identifiant</th>
		<th class="liste_th">Nom</th>
		<th class="liste_th">Pr&eacute;nom</th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_connexion.png"}"/></th>
		<th class="liste_th" width="14"><img src="{copixresource path="img/comptes/comptes_animateurs_visibleannuaire.png"}"/></th>
		
		<th class="liste_th">Groupes de villes</th>
		<th class="liste_th">Groupes d'&eacute;coles</th>
		
		<th class="liste_th">Actions</th>
	</tr>
	{foreach from=$ppo->iens item=ien name=iens}
		<tr class="{if $smarty.foreach.iens.first}first{/if}{if $smarty.foreach.iens.last} last{/if}">
			<td>{$ien->user_infos.login}</td>
			<td>{$ien->user_infos.nom}</td>
			<td>{$ien->user_infos.prenom}</td>
			
			<td align="center">{if $ien->can_connect}X{/if}</td>
			<td align="center">{if $ien->is_visibleannuaire}X{/if}</td>
			<td>
				{foreach from=$ien->regroupements->grvilles item=grville name=grvilles}{if ! $smarty.foreach.grvilles.first}, {/if}{$grville->nom}{/foreach}
			</td>
			<td>
				{foreach from=$ien->regroupements->grecoles item=grecole name=grecoles}{if ! $smarty.foreach.grecoles.first}, {/if}{$grecole->nom}{/foreach}
			</td>
			
			<td><a class="button button-update" href="{copixurl dest="comptes|ien|edit" user_type=$ien->user_type user_id=$ien->user_id}">modifier</a></td>
		</tr>
	{/foreach}
	</table>
{else}
	<i>Aucun IEN...</i>
{/if}
