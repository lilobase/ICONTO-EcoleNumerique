<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
  <link rel="stylesheet" href="http://local.iconito.fr/themes/default/styles/theme.css" type="text/css"/>
</head>
<body>
  {i18n key="comptes|comptes.strings.dateliste" 1=$smarty.now|datei18n:"date_short_time"}

  <table border="1" cellspacing="2" cellpadding="2" style="-moz-border-radius:6px 6px 6px 6px;background-color:#FFFFFF;font-size:12px;width:50%">
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
</body>
</html>