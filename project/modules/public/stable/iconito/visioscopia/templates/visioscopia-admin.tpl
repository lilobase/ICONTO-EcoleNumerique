<h2>Administration de la visioconférence</h2>

<p>Url d'acc&egrave;s : <a href="{$url}" target="_blank">{$url|escape}</a></p>

{if $saved}<p style="color: red;">Enregistrement effectu&eacute;</p>{/if}
<form action="{copixurl dest="visioscopia|default|go" id=$config->id}" method="post">
	<input type="hidden" name="save" value="1" />
	<input type="hidden" name="id" value="{$config->id}" />
	<table border="0">
	<tr><th>Activer la conférence :</th><td><input type="checkbox" name="conf_active" value="1" {if $config->conf_active}CHECKED{/if} /></td></tr>
	<tr><th>Identifiant de la conférence :</th><td><input name="conf_id" value="{$config->conf_id}" /></td></tr>
	<tr><th valign="top">Message aux utilisateurs :<br /><span style="font-weight: normal;">(&agrave; saisir en html)&nbsp;&nbsp;&nbsp;</span></th>
		<td><textarea name="conf_msg" cols="40" rows="6" />{$config->conf_msg|escape}</textarea></td></tr>
	<tr><td></td><td align="right"><input type="submit" value="Enregistrer les modifications" /></td></tr>
	</table>
</form>