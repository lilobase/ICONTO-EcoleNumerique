<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />

{i18n key="malle.zip.intro"}

<form action="{copixurl dest="|doUploadFileZip"}" method="post">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />
<input type="hidden" name="file" value="{$file.name}" />

<p></p>
{html_radios name="unzip" values=1 checked=1} {i18n key="malle.zip.unzip" 1=$file.name 2=$uploadMaxSize|human_file_size} 

{if $files neq null}
<UL STYLE="font-size:80%;">
{foreach from=$files item=item}
{if !$item.folder}
<LI{if $item.size>$uploadMaxSize} STYLE="color:red;"{/if}>{$item.filename} ({$item.size|human_file_size})</LI>
{/if}
{/foreach}
</UL>

{/if}


<p></p>
{html_radios name="unzip" values=0 checked=1} {i18n key="malle.zip.notUnzip"}

<p></p>

<input class="button button-cancel" onclick="self.location='{copixurl dest="|getMalle" id=$id folder=$folder}'" type="button" value="{i18n key="malle.btn.cancel"}" /> <input class="button button-save" type="submit" value="{i18n key="malle.btn.valid"}" />


</FORM>








