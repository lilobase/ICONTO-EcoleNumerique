
{if not $errors eq null}
	<DIV CLASS="message_erreur">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI><br/>
	{/foreach}
	</UL>
	</DIV>
{/if}

<form action="{copixurl dest="fichesecoles|admin|form"}" method="post" enctype="multipart/form-data">
<input type="hidden" name="save" value="1" />
<input type="hidden" name="id" value="{$rEcole->numero}" />

{i18n key="fichesecoles.message.form"}

<p></p>

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">

{if $canModifyVille}
	<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zoneville"}&nbsp;:</td> 
  <td CLASS="form_saisie" colspan="3"><input type="text" name="zone_ville_titre" value="{$rForm->zone_ville_titre|escape}" class="form" style="width:340px;" maxlength="200"><br/>{$form_zone_ville_texte}<div>{i18n key="fichesecoles.messageVille.form"}</div></td>
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
	
	
{if $rForm->photo}<img src="{copixurl dest="fichesecoles||photo" photo=$rForm->photo}" alt="{$rForm->photo}" border="0" /><br />{/if}
<input type="file" name="photoFile" class="form"><br/>
{i18n key="fichesecoles.message.photo" nb=$photoMaxWidth}<br/>
</td>
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone1"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone1_titre" value="{$rForm->zone1_titre|escape}" class="form" style="width:340px;" maxlength="200"><br/>{$form_zone1_texte}</td>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone2"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone2_titre" value="{$rForm->zone2_titre|escape}" class="form" style="width:340px;" maxlength="200"><br/>{$form_zone2_texte}</td>
</tr>

<tr>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone3"}&nbsp;:</td> 
  <td CLASS="form_saisie"><input type="text" name="zone3_titre" value="{$rForm->zone3_titre|escape}" class="form" style="width:340px;" maxlength="200"><br/>{$form_zone3_texte}</td>
	<td CLASS="form_libelle">{i18n key="dao.fiches_ecoles.fields.zone4"}&nbsp;:</td> 
  <td CLASS="form_saisie" colspan="3"><input type="text" name="zone4_titre" value="{$rForm->zone4_titre|escape}" class="form" style="width:340px;" maxlength="200"><br/>{$form_zone4_texte}</td>
</tr>
<tr>
 <td COLSPAN="4" CLASS="form_submit"><input style="" class="form_button" onclick="self.location='{copixurl dest="|fiche" id=$rEcole->numero}'" type="button" value="{i18n key="kernel|kernel.btn.cancel"}" /> <input class="form_button" type="submit" name="ok" value="{i18n key="kernel|kernel.btn.save"}" /></td>
</tr>
</table>






</form>


