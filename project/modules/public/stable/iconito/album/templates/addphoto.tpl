<div style="width: 500px; min-height: 200px; background:none repeat scroll 0 0 #F0F4F0; padding:5px; margin-left: auto; margin-right: auto;">
<form action="{copixurl dest="album||doaddphoto"}" method="post" enctype="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$ppo->file_size_photo}">
<input type="hidden" name="album_id"      value="{$ppo->album->album_id}">
<input type="hidden" name="dossier_id"    value="{$ppo->dossier->dossier_id}">
<input type="hidden" name="mode"  value="{$ppo->display_mode}">
<input type="hidden" name="popup_field"   value="{$ppo->popup_field}">
<input type="hidden" name="popup_format"  value="{$ppo->popup_format}">
<table class="form">
<tr><th valign="top" align="right"><nobr>{i18n key="album.form.file"}</nobr></th><td><input type="file" name="fichier" size="40" class="form" /></td></tr>
<tr><th valign="top" align="right"><nobr>{i18n key="album.form.title"}</nobr></th><td><input type="text" name="titre" size="60" class="form" /></td></tr>
<tr><th valign="top" align="right"><nobr>{i18n key="album.form.comment"}</nobr></th><td><textarea cols="60" rows="5" name="commentaire" class="form"></textarea></td></tr>
<tr><th valign="top"></th><td>
	<input type="submit" value="{i18n key="album.confirm.send"}" class="form_button" />
	<input type="button" value="{i18n key="album.confirm.cancel"}" onClick="self.location='{copixurl dest="album|default|getpopup" album_id=$ppo->album->album_id field=$ppo->popup_field format=$ppo->popup_format}';" />
</td></tr>
</table>
</form>

{i18n key="album.form.file_size_photo" 1=$ppo->file_size_photo|human_file_size noEscape=1}
</div>