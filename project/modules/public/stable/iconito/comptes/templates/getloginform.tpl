<SCRIPT LANGUAGE="Javascript1.2" SRC="{copixurl}js/iconito/module_malle.js"></SCRIPT>

<form action="{copixurl dest="comptes||doLoginCreate"}" enctype="multipart/form-data" name="form_comptes" id="form_comptes" method="POST">

<input type="hidden" NAME="type" VALUE="{$type}" />
<input type="hidden" NAME="id"   VALUE="{$id}"   />

<table border="0" CLASS="liste" ALIGN="CENTER" CELLSPACING=2 CELLPADDING=2>
	<tr>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.type"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.password"}</th>
		<th CLASS="liste_th">{i18n key="comptes|comptes.colonne.confirmer"}</th>
	</tr>
	{if $users neq null}
		{counter assign="i" name="i"}
		{foreach from=$users item=user}
			{counter name="i"}
			<input type="hidden" NAME="typeid[]" VALUE="{$user.type}-{$user.id}" />
			<tr CLASS="list_line{math equation="x%2" x=$i}">
				<td ALIGN="LEFT">{$user.type_nom}</td>
				<td ALIGN="LEFT">{$user.nom}</td>
				<td ALIGN="LEFT">{$user.prenom}</td>
				<td ALIGN="LEFT" width="1"><input NAME="login[{$user.type}-{$user.id}]" VALUE="{$user.login}" SIZE="25" /></td>
				<td ALIGN="LEFT" width="1"><input NAME="passwd[{$user.type}-{$user.id}]" VALUE="{$user.passwd}" SIZE="10" /></td>
				<td ALIGN="CENTER" width="1"><input type="checkbox" NAME="confirm[{$user.type}-{$user.id}]" VALUE="1" CHECKED /></td>
			</tr>
		{/foreach}
	{/if}
</table>

<br />
<div align="right">
<input class="button button-confirm" type="submit" value="{i18n key="comptes|comptes.form.submit"}" />
</div>

</form>
