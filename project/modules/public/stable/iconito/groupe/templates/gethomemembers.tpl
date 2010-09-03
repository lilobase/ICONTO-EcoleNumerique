<h1>{$groupe->titre}</h1>
<quote>{$groupe->description}</quote>
<p>{if $groupe->is_open}{i18n key=groupe|groupe.homeMembers.group_open}{else}{i18n key=groupe|groupe.homeMembers.group_closed}{/if}


	{if $list}
	<h1>{i18n key=groupe|groupe.homeMembers.members_list}</h1>
	
	<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
		<tr>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.number"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.login"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.name"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.firstname"}</th>
			<th CLASS="liste_th">{i18n key="groupe.adminMembers.list.right"}</th>
		</tr>

		{counter start=1 assign="cpt"}
		{foreach from=$list item=user}
			<tr CLASS="list_line{$cpt%2}">
				<td ALIGN="CENTER">{$cpt}</td>
				<td>{user label=$user.login userType=$user.type userId=$user.id login=$user.login dispMail=1}</td>
				<td>{$user.nom}</td>
				<td>{$user.prenom}</td>
				<td>{$user.droitnom}</td>
				{counter}
			</tr>
		{/foreach}
		</table>
		
	{else}
	<i>{i18n key="groupe.noMember"}</i>
	{/if}

	
	
	