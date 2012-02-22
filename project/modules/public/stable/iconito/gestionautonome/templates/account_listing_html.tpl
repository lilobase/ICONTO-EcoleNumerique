<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">

<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type" />
</head>
<body>
  Liste éditée le {$smarty.now|datei18n:"date_short_time"}

  <table border="1" cellspacing="2" cellpadding="2" style="-moz-border-radius:6px 6px 6px 6px;background-color:#FFFFFF;font-size:12px;width:50%">
  	<tr>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.nom"}</th>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.prenom"}</th>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.login"}</th>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.password"}</th>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.type"}</th>
  		<th class="liste_th">{i18n key="comptes|comptes.colonne.localisation"}</th>
  	</tr>
  	{if $accounts neq null}
  		{counter assign="i" name="i"}
  		{foreach from=$accounts item=account}
  			{counter name="i"}
  			<tr class="list_line{math equation="x%2" x=$i}">
  				<td align="LEFT">{$account.lastname}</td>
  				<td align="LEFT">{$account.firstname}</td>
  				<td align="LEFT">{$account.login}</td>
  				<td align="LEFT">{$account.password}</td>
  				<td align="LEFT">{$account.type_nom}</td>
  				<td align="LEFT">{$account.node_nom}</td>
  			</tr>
  			{foreach from=$account.person item=person}
    			<tr>
    				<td align="LEFT">{$person.lastname}</td>
    				<td align="LEFT">{$person.firstname}</td>
    				<td align="LEFT">{$person.login}</td>
    				<td align="LEFT">{$person.password}</td>
    				<td align="LEFT">{$person.nom_pa}</td>
    				<td align="LEFT">{$person.node_nom}</td>
    			</tr>
    		{/foreach}
  		{/foreach}
  	{/if}
  </table>
</body>
</html>