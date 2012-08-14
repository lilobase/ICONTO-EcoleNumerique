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
				<td width="1%" style="white-space: nowrap;"><a class="button button-add" href="{copixurl dest="comptes|ien|edit" user_type="USER_EXT" user_id=$user->ext_id}">d&eacute;finir comme IEN</a></td>
			</tr>
		{/foreach}
	{/if}
	
	
	{if $ppo->pers.USER_ENS}
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
		{foreach from=$ppo->pers.USER_ENS item=user name=user}
			<tr class="{if $smarty.foreach.user.first}first{/if}{if $smarty.foreach.user.last} last{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td width="1%" style="white-space: nowrap;"><a class="button button-add" href="{copixurl dest="comptes|IEN|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme IEN</a></td>
			</tr>
		{/foreach}
	{/if}
	
	{if $ppo->pers.USER_VIL}
		{assign var=personne value=0}
		<tr>
			<th class="liste_th" colspan="4"><h1 style="margin: 0px;">Agents de ville</h1></th>
		</tr>
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->pers.USER_VIL item=user name=user}
			<tr class="{if $smarty.foreach.user.first}first{/if}{if $smarty.foreach.user.last} last{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td width="1%" style="white-space: nowrap;"><a class="button button-add" href="{copixurl dest="comptes|IEN|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme IEN</a></td>
			</tr>
		{/foreach}
	{/if}
	
	{if $ppo->pers.USER_ADM}
		{assign var=personne value=0}
		<tr>
			<th class="liste_th" colspan="4"><h1 style="margin: 0px;">Personnels administratif</h1></th>
		</tr>
		<tr>
			<th class="liste_th">Login</th>
			<th class="liste_th">Nom</th>
			<th class="liste_th">Pr&eacute;nom</th>
			<th class="liste_th">Actions</th>
		</tr>
		{foreach from=$ppo->pers.USER_ADM item=user name=user}
			<tr class="{if $smarty.foreach.user.first}first{/if}{if $smarty.foreach.user.last} last{/if}">
				<td>{$user->login_dbuser}</td>
				<td>{$user->nom}</td>
				<td>{$user->prenom}</td>
				<td width="1%" style="white-space: nowrap;"><a class="button button-add" href="{copixurl dest="comptes|IEN|edit" user_type=$user->bu_type user_id=$user->bu_id}">d&eacute;finir comme IEN</a></td>
			</tr>
		{/foreach}
	{/if}
		
	</table>

	{if $personne}<p><i>Il n'y a plus personne &agrave; ajouter en tant qu'IEN...</i></p>{/if}