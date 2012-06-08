
{if not $errors eq null}
	<div class="mesgErrors">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL>
	</DIV>
{/if}

<form action="{copixurl dest="fichesecoles|admin|form"}" method="post" enctype="multipart/form-data" class="ficheEcoleForm">
<input type="hidden" name="save" value="1" />
<input type="hidden" name="id" value="{$rEcole->numero}" />

<p><em>{i18n key="fichesecoles.message.form"}</em></p>



<table class="editItems">
{if $canModifyVille}
    <tr>
        <th>{i18n key="dao.fiches_ecoles.fields.zoneville"}</th> 
        <td><input type="text" name="zone_ville_titre" value="{$rForm->zone_ville_titre|escape}" class="form zone_ville_titre" maxlength="200"><br/>{$form_zone_ville_texte}<em>{i18n key="fichesecoles.messageVille.form"}</em></td>
	</tr>
{/if}

<tr>
	<th>{i18n key="dao.fiches_ecoles.fields.horaires"}</th> 
    <td>{$form_horaires}</td>
</tr>
<tr>
	<th>{i18n key="dao.fiches_ecoles.fields.photo"}</th> 
    <td>{if $rForm->photo}<img src="{copixurl dest="fichesecoles||photo" photo=$rForm->photo}" alt="{$rForm->photo|escape}" border="0" /><br />{/if}
		<input type="file" name="photoFile" class="form"><br/>
		<em>{i18n key="fichesecoles.message.photo" nb=$photoMaxWidth}</em>
	</td>
</tr>

<tr>
	<td colspan="2"><h2>{i18n key="dao.fiches_ecoles.fields.zone1"}</h2></td>
</tr>
<tr>
	<th><label for="zone1_titre">{i18n key="fichesecoles.fields.title}</label></th> 
    <td><input type="text" name="zone1_titre" id="zone1_titre" value="{$rForm->zone1_titre|escape}" class="form zone_titre" maxlength="200"></td>
</tr>
<tr>
    <th><label for="zone1_texte">{i18n key="fichesecoles.fields.content}</label></th>
    <td>{$form_zone1_texte}</td>
</tr>

<tr>
	<td colspan="2"><h2>{i18n key="dao.fiches_ecoles.fields.zone2"}</h2></td>
</tr>
<tr>
	<th><label for="zone2_titre">{i18n key="fichesecoles.fields.title}</label></th> 
    <td><input type="text" name="zone2_titre" value="{$rForm->zone2_titre|escape}" class="form zone_titre" maxlength="200"></td>
</tr>
<tr>
    <th><label for="zone2_texte">{i18n key="fichesecoles.fields.content}</label></th>
    <td>{$form_zone2_texte}</td>
</tr>

<tr>
	<td colspan="2"><h2>{i18n key="dao.fiches_ecoles.fields.zone3"}</h2></td>
</tr>
<tr>
	<th><label for="zone3_titre">{i18n key="fichesecoles.fields.title}</label></th> 
    <td><input type="text" name="zone3_titre" value="{$rForm->zone3_titre|escape}" class="form zone_titre"  maxlength="200"></td>
</tr>
<tr>
    <th><label for="zone3_texte">{i18n key="fichesecoles.fields.content}</label></th>
    <td>{$form_zone3_texte}</td>
</tr>

<tr>
	<td colspan="2"><h2>{i18n key="dao.fiches_ecoles.fields.zone4"}</h2></td>
</tr>
<tr>
	<th><label for="zone4_titre">{i18n key="fichesecoles.fields.title}</label></th>
    <td><input type="text" name="zone4_titre" value="{$rForm->zone4_titre|escape}" class="form zone_titre"  maxlength="200"></td>
</tr>
<tr>
    <th><label for="zone4_texte">{i18n key="fichesecoles.fields.content}</label></th>
    <td>{$form_zone4_texte}</td>
</tr>


<tr>
	<td colspan="2"><h2>{i18n key="fichesecoles.fields.doc"}</h2></td>
</tr>
<tr>
    <th><label for="doc1_fichier">{i18n key="dao.fiches_ecoles.fields.doc1_fichier"}</label></th>
    <td>{if $rForm->doc1_fichier}<a href="{copixurl dest="fichesecoles||doc" fichier=$rForm->doc1_fichier}">{$rForm->getDocumentNom(1)}</a> &bull; <input type="checkbox" id="doc1_suppr" name="doc1_suppr" value="1" /><label for="doc1_suppr"> {i18n key="kernel|kernel.btn.delete"}</label> &bull; {i18n key="fichesecoles.fields.fileModify"}{/if}
    <input type="file" name="doc1_fichier" class="form file"></td>
</tr>
<tr>
    <th><label for="doc1_titre">{i18n key="dao.fiches_ecoles.fields.doc1_titre"}</label></th>
    <td><input type="text" name="doc1_titre" value="{$rForm->doc1_titre|escape}" class="form doc_titre" maxlength="200"></td>
</tr>
</table>

<div class="center"><a class="button button-cancel" href="{copixurl dest="|fiche" id=$rEcole->numero}">{i18n key="kernel|kernel.btn.cancel"}</a> <input class="button button-save" type="submit" name="ok" value="{i18n key="kernel|kernel.btn.save"}" /></div>


</form>


