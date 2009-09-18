<html>

<head>
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_album.css"}" />
<link rel="stylesheet" type="text/css" href="{copixresource path="styles/theme.css"}" media="screen" />

{if $finish}
<meta HTTP-EQUIV="REFRESH" content="3; url={copixurl dest="album||album" album_id=$url_album dossier=$url_dossier}">
{else}
<meta HTTP-EQUIV="REFRESH" content="0; url={copixurl dest="album||vignettes" album=$url_album dossier=$url_dossier key=$url_key}">
{/if}
</head>

<body>

<div align="center">

<br /><br /><br /><br />

<h1>{$message}</h1>
{if ! $finish}
<br />
<table border="0"><tr><td valign="middle">
	<table border="0" width="400" style="border: 2px solid #666;" cellpadding="0" cellspacing="3">
	<tr>
	<td bgcolor="#666666" width="{math equation="400 * ( bar_value / bar_max ) + 1"
	      bar_value=$bar_value
	      bar_max=$bar_max}">&nbsp;</td>
	<td>&nbsp;</td>
	</table>
</td><td valign="middle">
	<h2 style="padding: 0px; margin: 0px;">{math equation="( bar_value / bar_max ) * 100"
		bar_value=$bar_value
		bar_max=$bar_max
		format="%d"}%</h2>
</td></tr></table>
{/if}
</div>
</body>

</html>