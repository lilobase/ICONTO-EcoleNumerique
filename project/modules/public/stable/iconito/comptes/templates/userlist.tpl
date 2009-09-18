<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_malle.js"></SCRIPT>

<form action="{copixurl dest="comptes||getLoginForm" type=$type id=$id}" enctype="multipart/form-data" name="form_comptes" id="form_comptes" method="POST">

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.type"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
	</tr>
	{if $childs neq null}
		{counter assign="i" name="i" start="1"}
		{foreach from=$childs item=user}
			{counter name="i"}
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td ALIGN="LEFT">{$user.type_nom}</td>
				<td ALIGN="LEFT">{$user.nom}</td>
				<td ALIGN="LEFT">{$user.prenom}</td>
				<td ALIGN="LEFT">
				{if $user.login}
				<a href="{copixurl dest="comptes||getUser" node_type=$type node_id=$id login=$user.login}">{$user.login}</a>
				{else}
				<input type="checkbox" NAME="users[]" VALUE="{$user.type}-{$user.id}"> {i18n key="comptes|comptes.strings.create"}
				{/if}
				</td>
			</tr>
		{/foreach}
	<tr CLASS="liste_footer">
		<TD COLSPAN="3"></TD>
		<TD ALIGN="CENTER"><a href="javascript:cocherElements('form_comptes', 'users[]', 1);">{i18n key="comptes|comptes.button.all"}</a> / <a href="javascript:cocherElements('form_comptes', 'users[]', 0);">{i18n key="comptes|comptes.button.none"}</a></TD>
	</tr>
	{/if}
</table>

<br />
<div align="right">
<input class="form_button" type="submit" value="{i18n key="comptes|comptes.form.submit"}" />
</div>

</form>