{i18n key="comptes|comptes.strings.dateliste" 1=$smarty.now|datei18n:"date_short_time"}

<table border="1" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2 width="100%">
	<tr>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.password"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.type"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.localisation"}</th>
	</tr>
	{if $logins neq null}
		{counter assign="i" name="i"}
		{foreach from=$logins item=login}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td ALIGN="LEFT">{$login.nom}</td>
				<td ALIGN="LEFT">{$login.prenom}</td>
				<td ALIGN="LEFT">{$login.login}</td>
				<td ALIGN="LEFT">{$login.passwd}</td>
				<td ALIGN="LEFT">{$login.type_nom}</td>
				<td ALIGN="LEFT">{$login.node_nom}</td>
			</tr>
		{/foreach}
	{/if}
</table>
