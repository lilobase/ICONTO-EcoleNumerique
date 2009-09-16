

{if $ppo->errors}
	<div class="form-errors">
		<p>Erreur(s) :</p>	
		<dl>
		{foreach from=$ppo->errors item=error key=key}
			{*<dt>{$key}&nbsp;:&nbsp;</dt>*}
			 	<dd>{$error}</dd>
		{/foreach}
		</dl> 
	</div>
{/if}

<form class="default-form" method="post" onSubmit="submitonce(this);">

<input type="hidden" name="id" value="{$ppo->rForm->id}" />
<input type="hidden" name="submit" value="1" />

<fieldset>
	<legend>Commission</legend>

	

	
	<p>
		<label for="date" class="default">{i18n key=kernel|dao.commission.fields.date noEscape=1}<span class="asterisk">*</span></label>
		{inputtext name="date" value=$ppo->rForm->date maxlength="10" style="width:130px;"} - Format : JJ/MM/AAAA - <a href="javascript:set_value('date','{$smarty.now|date_format:"%d/%m/%Y"}');">aujourd'hui</a>
	</p>
	
	<p>
		<label for="notes" class="default">{i18n key=kernel|dao.demande.fields.notes noEscape=1}</label>
		{textarea name="notes" value=$ppo->rForm->notes style="width:320px;height:50px;"}
	</p>
	
	
	<p class="asterisk_txt">Les champs marqu&eacute;s du symbole <span class="asterisk">*</span> sont obligatoires</p>

<p>
<input type="button" class="formCancel" value="Annuler" onClick="self.location='{copixurl dest="instruction|dossiers|"}';" />

<input type="submit" class="formSubmit" value="Enregistrer" />


</p>
		
</fieldset>

</form>
