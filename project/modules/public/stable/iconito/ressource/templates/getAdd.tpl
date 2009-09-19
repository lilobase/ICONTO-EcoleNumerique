<form method="POST" action="{copixurl dest="ressource||doAdd"}">
<table border="0" width="100%">
<tr>
	<th valign="top" align="right">{i18n key="ressource.form.titre"}</th>
	<td><input name="ressource_nom" value="{$ressource->ressource_nom}" size="80"></td>
</tr>
<tr>
	<th valign="top" align="right">Description :</th>
	<td><textarea name="ressource_descr" rows="20" cols="60">{$ressource->ressource_descr}</textarea></td>
</tr>
<tr>
	<th valign="top" align="right">Mots clé :</th>
	<td><input name="ressource_mots" value="{$ressource->ressource_mots}" size="80"></td>
</tr>
<tr>
	<th valign="top" align="right">Type :</th>
	<td>
	<select name="type">
	<option value="0" SELECTED>--- à renseigner ---</option>
	<option value="1">Site web</option>
	<option value="2">Page web</option>
	<option value="3">Image</option>
	<option value="4">Son</option>
	<option value="5">Vidéo</option>
</select>
	</td>
</tr>
<tr>
	<th valign="top" align="right">Niveau :</th>
	<td>
	<LABEL for="level-1"><input type="checkbox" name="level" value="1" id="level-1">Niveau 1</LABEL><br />
	<LABEL for="level-2"><input type="checkbox" name="level" value="2" id="level-2">Niveau 2</LABEL><br />
	<LABEL for="level-3"><input type="checkbox" name="level" value="3" id="level-3">Niveau 3</LABEL><br />
	</td>
</tr>
<tr>
	<th valign="top" align="right">Compétences :</th>
	<td>
	<div style="margin-bottom: 5px; margin-left: 0px;"><LABEL for="comp-1"><input type="checkbox" name="comp" value="1" id="comp-1">Compétence 1</LABEL></div>
	<div style="margin-bottom: 5px; margin-left: 0px;"><LABEL for="comp-2"><input type="checkbox" name="comp" value="2" id="comp-2">Compétence 2</LABEL>
		<div style="margin-bottom: 5px; margin-left: 20px;"><LABEL for="comp-2-1"><input type="checkbox" name="comp" value="2" id="comp-2">Compétence 2 - Sous-compétence 1</LABEL></div>
		<div style="margin-bottom: 5px; margin-left: 20px;"><LABEL for="comp-2-2"><input type="checkbox" name="comp" value="2" id="comp-2">Compétence 2 - Sous-compétence 2</LABEL></div>
	</div>
	<div style="margin-bottom: 5px; margin-left: 0px;"><LABEL for="comp-3"><input type="checkbox" name="comp" value="3" id="comp-3">Compétence 3</LABEL></div>
	</td>
</tr>

<tr>
	<th valign="top" align="right">Valider :</th>
	<td><input type="Submit" value="Enregistrer"></td>
</tr>
</table>
</form>