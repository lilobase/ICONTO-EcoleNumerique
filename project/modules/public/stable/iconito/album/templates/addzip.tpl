<form action="{copixurl dest="album||doaddzip"}" method="post" enctype="multipart/form-data">
<input type="hidden" name="album_id" value="{$album->album_id}">
<input type="hidden" name="dossier_id" value="{$dossier_id}">
<table class="form">
<tr><th>{i18n key="album.form.file"}</th><td><input type="file" name="fichier" size="40" class="form" /></td></tr>
<tr><th></th><td><input type="submit" value="{i18n key="album.confirm.send"}" class="form_button" /></td></tr>
</table>
</form>

{i18n key="album.form.file_size_zip" 1=$file_size_zip|human_file_size}