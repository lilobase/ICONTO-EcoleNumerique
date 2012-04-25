{copixzone process=classeur|affichageMenu classeurId=$ppo->classeur->id dossierId=$ppo->dossierParent->id}

<h2>{i18n key="classeur.message.options"}</h2>

{if $ppo->conf_ModClasseur_upload}
<h3>{i18n key="classeur.message.editUpload"}</h3>
<div style="background: none repeat scroll 0 0 #FFFFFF;
    border-radius: 10px 10px 10px 10px;
    margin: 3px 0 20px;
    padding: 10px 10px 1px;">

<div class="coldroite" style="float: right; width: 38%;">
<h3>Aide</h3>
<p>Pour configurer votre TBI :
<ul>
	<li>Notez les paramètres techniques qui vous sont présentés ci-contre.</li>
	<li>Sur l'ordinateur connecté à votre TBI, installez un logiciel qui permet de synchroniser un dossier avec un serveur WebDav <!-- (consultez <a href="">cette aide</a> pour plus de détails). --></li>
</ul>
</div>

<div class="colgauche" style="margin-right: 42%;">
<p>Cette option vous permet d'envoyer des fichiers dans ce Classeur depuis un dispositif externe, comme un <abbr title="Tableau Blanc Interactif">TBI</abbr>.</p>

{if ! $ppo->classeur->upload_db|is_null}
<p class="mesgInfo">La réception de fichier est activée pour ce classeur</p>
<form id="form_upload_config" action="{copixurl dest="classeur|options|" classeurId=$ppo->classeur->id}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
	<input type="hidden" name="save-mode" value="upload-delete" />
	<input class="button button-cancel" type="submit" name="save" value="{i18n key="classeur.message.upload-delete"}" />
</form>
<p>Les documents seront déposés dans le dossier {if $ppo->classeur->folder_infos}"{$ppo->classeur->folder_infos->nom|escape}"{else}principal{/if} de ce classeur.</p>
<fieldset>
<legend>Paramètres techniques</legend>
<table width="100%">
	<tr><th width="1" align="right">Serveur&nbsp;WebDav&nbsp;:</th><td><input readonly="readonly" style="width: 100%" value="{$ppo->classeur->upload_url|escape}"/></td></tr>
	<tr><th align="right">Identifiant&nbsp;:</th><td><input readonly="readonly" value="{$ppo->classeur->upload_fs|escape}"/></td></tr>
	<tr><th align="right">Mot de passe&nbsp;:</th><td><input readonly="readonly" value="{$ppo->classeur->upload_pw|escape}"/></td></tr>
</table>
</fieldset>

{else}
<p class="mesgInfo">La réception de fichier n'est pas activée.</p>
{/if}

<form id="form_upload_config" action="{copixurl dest="classeur|options|" classeurId=$ppo->classeur->id}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="classeurId" id="classeurId" value="{$ppo->classeur->id}" />
	<input type="hidden" name="save-mode" value="upload" />
	<p>Selectionnez ci-dessous le dossier où seront déposés les documents.</p>
	<div class="selectFolder">
	<ul class="child">
		<li class="folder"><p class="{if ! $ppo->classeur->upload_db}current{/if}"><input id="dossier-0" type="radio" value="dossier-0" name="destination"{if ! $ppo->classeur->upload_db} checked="checked"{/if}><label for="dossier-0">Dossier principal du classeur</label></p></li>
		{copixzone process=classeur|selectionDossiers classeurId=$ppo->classeur->id targetType="dossier" targetId=$ppo->classeur->upload_db alwaysOpen=$ppo->classeur->upload_db}
	</ul>
	</div>
	{if ! $ppo->classeur->upload_db|is_null}
	<input class="button button-update" type="submit" name="save" id="save" value="{i18n key="classeur.message.upload-modify"}" />
	{else}
	<input class="button button-confirm" type="submit" name="save" id="save" value="{i18n key="classeur.message.upload-active"}" />
	{/if}
</form>
</div>
</div>

{/if}
