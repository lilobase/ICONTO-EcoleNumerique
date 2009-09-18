<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_malle.js"></SCRIPT>

<form action="{copixurl dest="comptes||doPurgeResult"}" enctype="multipart/form-data" name="form_comptes" id="form_comptes" method="POST">

<input type="hidden" NAME="type" VALUE="{$type}" />
<input type="hidden" NAME="id"   VALUE="{$id}"   />

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.localisation"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.confirmer"}</th>
	</tr>
	{if $logins neq null}
		{counter assign="i" name="i"}
		{foreach from=$logins item=login}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td ALIGN="LEFT">{$login.nom}</td>
				<td ALIGN="LEFT">{$login.prenom}</td>
				<td ALIGN="LEFT">{$login.login}</td>
				<td ALIGN="LEFT">{$login.node_nom}</td>
				<td ALIGN="CENTER" width="1"><input type="checkbox" NAME="users[]" VALUE="{$login.bu_type}-{$login.bu_id}" CHECKED /></td>
			</tr>
		{/foreach}
			<tr CLASS="liste_footer">
				<TD COLSPAN="4"></TD>
				<TD ALIGN="CENTER"><a href="javascript:cocherElements('form_comptes', 'users[]', 1);">{i18n key="comptes|comptes.button.all"}</a> / <a href="javascript:cocherElements('form_comptes', 'users[]', 0);">{i18n key="comptes|comptes.button.none"}</a></TD>
			</tr>
	{/if}
</table>

<br />
<div align="right">
<input class="form_button" type="submit" value="{i18n key="comptes|comptes.form.submit"}" />
</div>

</form>
