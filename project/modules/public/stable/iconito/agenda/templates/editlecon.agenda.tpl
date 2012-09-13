{if $showError}
	<div class="mesgErrors">
		{foreach from=$arError item=errors key=index}
		{ulli values=$errors}
		{/foreach}
	</div>
{/if}

<form action="{copixurl dest="agenda|lecon|valid" id_agenda=$toEdit->id_agenda}" method="post" class="copixForm" name="saisieLecon">
<table style="border:0;" class="saisieEvent">
		<tr>
			<td class="form_libelle">{i18n key="agenda.message.lecon"}</td>
			<td><textarea class="form" style="width: 400px; height: 80px;" name="desc_lecon">{$toEdit->desc_lecon}</textarea></td>
		</tr>
    <tr>
  <td colspan="4" CLASS="form_submit">
  <input type="button" class="button button-cancel" value="{i18n key=copix:common.buttons.cancel}" onclick="self.location='{copixurl dest="agenda|agenda|vueSemaine"}'" />
		<input type="submit" class="button button-confirm" value="{i18n key=copix:common.buttons.save}" />
  </td></tr>  
	</table>
</form>

