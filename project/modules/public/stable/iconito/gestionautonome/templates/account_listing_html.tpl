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
	{if $sessionDatas neq null}
		{counter assign="i" name="i"}
		{foreach from=$sessionDatas item=sessionData}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td ALIGN="LEFT">{$sessionData.nom}</td>
				<td ALIGN="LEFT">{$sessionData.prenom}</td>
				<td ALIGN="LEFT">{$sessionData.login}</td>
				<td ALIGN="LEFT">{$sessionData.password}</td>
				<td ALIGN="LEFT">{$sessionData.type_nom}</td>
				<td ALIGN="LEFT">{$sessionData.node_nom}</td>
			</tr>
		{/foreach}
	{/if}
</table>