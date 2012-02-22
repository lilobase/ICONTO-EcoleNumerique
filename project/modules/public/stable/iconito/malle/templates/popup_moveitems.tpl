<form name="movefile" id="movefile" action="{copixurl dest="|doAction"}" method="post" style="display: none;">
	<h1 id="form-message" style="width: 300px;">{i18n key="malle.move.where"}</h1>
	<input type="hidden" name="id" value="{$ppo->id}" />
	<input type="hidden" name="folder" value="{$ppo->folder}" />
	<div id="form-replicator"></div>
	{$ppo->combofoldersdest}
	<div class="content-panel content-panel-button"><input type="submit" name="actionMove" value="{i18n key="malle.btn.move"}" class="button button-confirm" /></div>
</form>

<div class="warning warning-exclamation" style="display: none; width: 300px;">
	{i18n key="malle.error.noSelection"}
</div>

{literal}
<script type="text/javascript">
jQuery(document).ready(function($){
	var folders = $('#remote-checker :checked[name="folders[]"]').serializeArray();
	var folderCount = 0;
	jQuery.each(folders, function(i, folder){
		if (folder.name) $('#form-replicator').append('<input type="hidden" name="folders[]" value="' + folder.value + '"/>');
		folderCount++;
	});

	var files = $('#remote-checker :checked[name="files[]"]').serializeArray();
	var fileCount = 0;
	jQuery.each(files, function(i, file){
		if (file.name) $('#form-replicator').append('<input type="hidden" name="files[]" value="' + file.value + '"/>');
		fileCount++;
	});
	
	if (fileCount==0 && folderCount==0) $('.warning').show();
	else $('#movefile').show();
	
});
</script>
{/literal}
