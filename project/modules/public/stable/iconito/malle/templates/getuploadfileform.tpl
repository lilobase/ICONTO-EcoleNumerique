<link rel="stylesheet" type="text/css" href="{copixresource path="styles/module_malle.css"}" />

{$petitpoucet}

{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL></DIV>
{/if}


<form action="{copixurl dest="|doUploadFile"}" method="post" ENCTYPE="multipart/form-data">
<input type="hidden" name="id" value="{$id}" />
<input type="hidden" name="folder" value="{$folder}" />

		
Choisissez le fichier sur votre disque dur en faisant "Parcourir", puis cliquez sur "Envoyer". Taille maximum d'un fichier: 2M oct. 

<INPUT class="form" style="margin: 2px;" TYPE="file" NAME="fichier" ></INPUT>

<p></p>


	<input style="" class="button button-cancel" onclick="self.location='{copixurl dest="|getMalle" id=$id folder=$folder}'" type="button" value="Annuler" /> <input class="button button-save" type="submit" value="Envoyer" />

</form>
