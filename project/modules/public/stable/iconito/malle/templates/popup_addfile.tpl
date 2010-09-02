<h1>{i18n key="malle.addFile"}</h1>
<div class="explanation">
{i18n key="malle.addFileTxt" 1=$ppo->uploadMaxSize|human_file_size}<br/>
{i18n key="malle.addFileZip"}
</div>

<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="MAX_FILE_SIZE" value="{$ppo->uploadMaxSize}">
<input type="hidden" name="id" value="{$ppo->id}" />
<input type="hidden" name="folder" value="{$ppo->folder}" />
<input class="form" type="file" name="fichier" size="36"></input>


<input class="button button-confirm" type="submit" value="{i18n key="malle.btn.submitAddFile"}" />
</form>
