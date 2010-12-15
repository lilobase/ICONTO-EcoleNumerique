
<form action="{copixurl dest="comptes||getLoginForm" type=$type id=$id}" enctype="multipart/form-data" name="form_comptes" id="form_comptes" method="POST">

{assign var=nbCheckbox value=0}

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
        {assign var=nbCheckbox value=$nbCheckbox+1}
				{/if}
				</td>
			</tr>
		{/foreach}
  {if $nbCheckbox>0}
	<tr CLASS="liste_footer">
		<TD COLSPAN="3"></TD>
		<TD ALIGN="CENTER"><a class="checkAll" checked="checked" href="">{i18n key="comptes|comptes.button.all"}</a> / <a class="checkAll" checked="" href="">{i18n key="comptes|comptes.button.none"}</a></TD>
	</tr>
  {/if}
	{/if}
</table>

<br />
<div align="right">
{if $type eq "BU_CLASSE"}
  <a class="button" href="{copixurl dest="comptes|default|getLoginForm" type=$type id=$id reset="USER_RES"}">R&eacute;initialiser les mots de passes des parents</a>
  <a class="button" href="{copixurl dest="comptes|default|getLoginForm" type=$type id=$id reset="USER_ELE"}">R&eacute;initialiser les mots de passes des &eacute;l&egrave;ves</a>
{/if}
{if $nbCheckbox>0}
  <input class="button button-confirm" type="submit" value="{i18n key="comptes|comptes.form.submit"}" />
{/if}
  </div>

</form>

{literal}
<script type="text/javascript">
$(document).ready(function(){
  $('a.checkAll').click(function(){
    $('form#form_comptes input[type=checkbox]').attr('checked',$(this).attr('checked'));
    return false;
  });
});
</script>
{/literal}


