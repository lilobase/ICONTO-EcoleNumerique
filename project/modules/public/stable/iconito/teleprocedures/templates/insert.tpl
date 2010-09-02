
{if not $errors eq null}
	<div id="dialog-message" title="{i18n key=kernel|kernel.error.problem}">
	<UL>
	{foreach from=$errors item=error}
		<LI>{$error}</LI>
	{/foreach}
	</UL>
	</div>
{/if}

<form action="{copixurl dest="|insert"}" method="post">
<input type="hidden" name="save" value="1" />
<input type="hidden" name="ecole" value="{$rEcole.id}" />
<input type="hidden" name="idtype" value="{$rForm->idtype}" />

<table border="0" CELLSPACING="1" CELLPADDING="1" ALIGN="CENTER">
<tr>
    <td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.intervention.field.date"}&nbsp;:</td>
    <td CLASS="form_saisie">{$date|datei18n:"date_short"}</td>
	<td width=""></td>
	<td width=""></td>
</tr>
<tr> 
<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.intervention.field.ecole"}&nbsp;:</td>
<td CLASS="form_saisie">{$rEcole.nom}</td>
<td width=""></td>
<td width=""></td>
</tr>

{*
<tr>
 <td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.intervention.field.statut"}&nbsp;:</td> 
  <td colspan="3" CLASS="form_saisie">
<select name="idstatu" CLASS="form" STYLE="width:130px;">
 {foreach from=$arStat item=i}
<option value="{$i->idstat}">{$i->nom}</option>
{/foreach}
</select>  </td></tr>
*}
<tr>
<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.intervention.field.objet"}&nbsp;:</td> 
  <td colspan="3" CLASS="form_saisie"><input type="text" name="objet" CLASS="form" value="{$rForm->objet|escape}" STYLE="width:450px;"> </td></tr>
<tr>
<td CLASS="form_libelle">{i18n key="teleprocedures|teleprocedures.intervention.field.detail"}&nbsp;:</td> 
  <td colspan="3" CLASS="form_saisie">
	
	{$detail_edition}


  </td>
</tr>
<tr>
 <td COLSPAN="4" CLASS="form_submit"><input style="" class="form_button" onclick="self.location='{copixurl dest="|go" id=$rType->teleprocedure}'" type="button" value="{i18n key="kernel|kernel.btn.cancel"}" /> <input class="form_button" type="submit" name="ok" value="{i18n key="kernel|kernel.btn.save"}" /></td>
</tr>
</table>
</form>


