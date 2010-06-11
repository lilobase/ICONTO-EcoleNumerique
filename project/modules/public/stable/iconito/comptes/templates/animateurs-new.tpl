	<table width="100%" class="liste comptes_animateurs comptes_animateurs_new">
	
	{assign var=personne value=1}
	
	{if $ppo->userext}
		{assign var=personne value=0}
		<tr>
			<th class="liste_th" colspan="4"><h1 style="margin: 0px;">Personnes externes</h1></th>
		</tr>
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->userext item=user name=user}
			<tr class="{if $smarty.foreach.user.first}first{/if}{if $smarty.foreach.user.last} last{/if}">
				<td>{$user->user_infos.login}</td>
				<td>{$user->ext_nom}</td>
				<td>{$user->ext_prenom}</td>
				<td width="1%" style="white-space: nowrap;"><a href="{copixurl dest="comptes|animateurs|edit" user_type="USER_EXT" user_id=$user->ext_id}">d&eacute;finir comme animateur</a></td>
			</tr>
		{/foreach}
	{/if}
	
	
	{if $ppo->userens}
		{assign var=personne value=0}
		<tr>
			<th class="liste_th" colspan="4"><h1 style="margin: 0px;">Enseignants</h1></th>
		</tr>
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->userens item=user name=user}
			<tr class="{if $smarty.foreach.user.first}first{/if}{if $smarty.foreach.user.last} last{/if}">
				<td>{$user->user_infos.login}</td>
				<td>{$user->pers_nom}</td>
				<td>{$user->pers_prenom1}</td>
				<td width="1%" style="white-space: nowrap;"><a href="{copixurl dest="comptes|animateurs|edit" user_type="USER_ENS" user_id=$user->pers_numero}">d&eacute;finir comme animateur</a></td>
			</tr>
		{/foreach}
	{/if}
		
	</table>

	{if $personne}<p><i>Il n'y a plus personne &agrave; ajouter en tant qu'animateur...</i></p>{/if}