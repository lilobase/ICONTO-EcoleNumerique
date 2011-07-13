
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

{i18n key="fichesecoles.message.form"}

<p></p>

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

{if $canModifyVille}
	<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zoneville"}&nbsp;:</td> 
  <td CLASS="form_saisie" colspan="3"><input type="text" name="zone_ville_titre" value="{$rForm->zone_ville_titre|escape}" class="form zone_ville_titre" maxlength="200"><br/>{$form_zone_ville_texte}<div>{i18n key="fichesecoles.messageVille.form"}</div></td>
	</tr>
	<tr>
		<td colspan="4"><hr /></td>
	</tr>

{/if}


<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.horaires"}&nbsp;:</td> 
  <td CLASS="form_saisie">{$form_horaires}</td>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.photo"}&nbsp;:</td> 
  <td CLASS="form_saisie">
	
	
{if $rForm->photo}<img src="{copixurl dest="fichesecoles||photo" photo=$rForm->photo}" alt="{$rForm->photo|escape}" border="0" /><br />{/if}
<input type="file" name="photoFile" class="form"><br/>
{i18n key="fichesecoles.message.photo" nb=$photoMaxWidth}<br/>
</td>
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone1"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone1_titre" value="{$rForm->zone1_titre|escape}" class="form zone_titre" maxlength="200"><br/>{$form_zone1_texte}</td>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone2"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone2_titre" value="{$rForm->zone2_titre|escape}" class="form zone_titre" maxlength="200"><br/>{$form_zone2_texte}</td>
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone3"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone3_titre" value="{$rForm->zone3_titre|escape}" class="form zone_titre"  maxlength="200"><br/>{$form_zone3_texte}</td>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone4"}&nbsp;:</td> 
  <td CLASS="form_saisie" colspan="3"><input type="text" name="zone4_titre" value="{$rForm->zone4_titre|escape}" class="form zone_titre"  maxlength="200"><br/>{$form_zone4_texte}</td>
</tr>


<tr>
    <td CLASS="form_libelle">{i18n key="fichesecoles.fields.doc"}&nbsp;:</td>
    <td CLASS="form_saisie" colspan="3">
	{i18n key="dao.fiches_ecoles.fields.doc1_fichier"} : {if $rForm->doc1_fichier}<a href="{copixurl dest="fichesecoles||doc" fichier=$rForm->doc1_fichier}">{$rForm->getDocumentNom(1)}</a> &bull; <input type="checkbox" id="doc1_suppr" name="doc1_suppr" value="1" /><label for="doc1_suppr"> {i18n key="kernel|kernel.btn.delete"}</label> &bull; {i18n key="fichesecoles.fields.fileModify"}{/if}
    <input type="file" name="doc1_fichier" class="form file"><br/>


    {i18n key="dao.fiches_ecoles.fields.doc1_titre"} : <input type="text" name="doc1_titre" value="{$rForm->doc1_titre|escape}" class="form doc_titre" maxlength="200">

    </td>
</tr>

<tr>
 <td COLSPAN="4" CLASS="form_submit"><input class="button button-cancel" onclick="self.location='{copixurl dest="|fiche" id=$rEcole->numero}'" type="button" value="{i18n key="kernel|kernel.btn.cancel"}" /> <input class="button button-save" type="submit" name="ok" value="{i18n key="kernel|kernel.btn.save"}" /></td>
</tr>

</table>






</form>


