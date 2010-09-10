<div style="width: 500px; min-height: 200px;">
<form action="{copixurl dest="album||doaddphoto"}" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$ppo->file_size_photo}">
<input type="hidden" name="album_id" value="{$ppo->album->album_id}">
<input type="hidden" name="dossier_id" value="{$ppo->dossier->dossier_id}">
<table class="form">
<tr><th valign="top">{i18n key="album.form.file"}</th><td><input type="file" name="fichier" size="40" class="form" /></td></tr>
<tr><th valign="top">{i18n key="album.form.title"}</th><td><input type="text" name="titre" size="60" class="form" /></td></tr>
<tr><th valign="top">{i18n key="album.form.comment"}</th><td><textarea cols="60" rows="5" name="commentaire" class="form"></textarea></td></tr>
<tr><th valign="top"></th><td><input type="submit" value="{i18n key="album.confirm.send"}" class="form_button" /></td></tr>
</table>
</form>

{i18n key="album.form.file_size_photo" 1=$ppo->file_size_photo|human_file_size noEscape=1}
</div>