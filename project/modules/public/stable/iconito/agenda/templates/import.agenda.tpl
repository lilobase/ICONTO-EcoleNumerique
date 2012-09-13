<h2>{i18n key="agenda.menu.import"}</h2>

{if $showError}
	<div class="mesgErrors">
		{foreach from=$arError item=errors key=index}
		{ulli values=$errors}
		{/foreach}
	</div>
{/if}

<form action="{copixurl dest="agenda|importexport|import"}" method="post" class="copixForm" name="importiCal" enctype="multipart/form-data">
	<table  style="border:0;" class="saisieEvent">
		<tr>
			<td class="form_libelle"><label for="id_agenda">{i18n key="agenda.message.agenda"}</label></td>
			<td class="input_import">
				<!--{select name="id_agenda" values=$arTitleAgendasAffiches selected=$toEdit->id_agenda emptyShow=false}-->
				<select name="id_agenda" id="id_agenda">
	   				{foreach from=$arTitleAgendasAffiches item=title key=idAgenda}
						<option value="{$idAgenda}" {if $idAgenda eq $importParams->id_agenda}selected="selected"{/if}>{$title}</option>
					{/foreach}
			    </select>
			</td>
		</tr>
		<tr>
			<td class="form_libelle">{i18n key="agenda.message.source"}</td>		

			<td class="desc_import">- {i18n key="agenda.message.iCalOrdi"} :
      <br/>
      <input class="form" type="file" size="50" name="import_ordi" value="{$importParams->import_ordi}" /><br/>
      {i18n key="agenda.message.ou"}<br/>
      - {i18n key="agenda.message.iCalInternet"} :<br/>
      <input class="form" type="text" name="import_internet" value="{$importParams->import_internet}" {if $agenda->id_agenda eq $toEdit->id_agenda}selected="selected"{/if} size="65"/></td>
		</tr>
		<tr>
			<td class="form_libelle">{i18n key="agenda.message.option"}</td>
			<td class="desc_import">{i18n key="agenda.message.define" noEscape=1}
      <br/>
      <input type="radio" id="optionVider" name="option" value="0" size="65" {if "0" eq $importParams->option}checked="checked"{/if} /> <label for="optionVider">{i18n key="agenda.message.vider"}</label><br />
		<input type="radio" id="optionImporter" name="option" value="1" size="65" {if "1" eq $importParams->option}checked="checked"{/if} /> <label for="optionImporter">{i18n key="agenda.message.importer"}</label>
			</td>
		</tr>
    <tr>
    <td colspan="4" class="center form_submit">
		<input type="button" class="button button-cancel" value="{i18n key=copix:common.buttons.cancel}" onclick="javascript:document.location='{copixurl dest="agenda|agenda|vueSemaine"}'" />
		<input type="submit" class="button button-continue" value="{i18n key="agenda.message.importation"}" />
    </td>
    </tr>
	</table>

</form>