{if $ppo->rForm}


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
	<legend>Modification de structure</legend>

	<p>
		<label for="nom" class="default">Nom<span class="asterisk">*</span></label>
		{inputtext name="nom" value=$ppo->rForm->nom maxlength="50" style="width:300px;"}
	</p>

	<p>
		<label for="type" class="default">Type<span class="asterisk">*</span></label>
		{copixzone process=kernel|combo_structure_type name="type" selected=$ppo->rForm->type}
		{* html_options name=type options=$ppo->structures_types selected=$ppo->rForm->type *}
	</p>
	
	<p>
		<label for="adresse" class="default">Adresse</label>
		<textarea name="adresse" style="width:300px;">{$ppo->rForm->adresse}</textarea>
	</p>

	<p>
		<label for="" class="default"></label>
		<label for="cp" class="">CP : </label>
		{inputtext name="cp" value=$ppo->rForm->cp maxlength="5" style="width:50px;"} - 
		<label for="ville" class="">Ville : </label>
		{inputtext name="ville" value=$ppo->rForm->ville maxlength="50" style="width:174px;"}
	</p>

	<p>
		<label for="tel1" class="default">T&eacute;l&eacute;phone 1</label>
		{inputtext name="tel1" value=$ppo->rForm->tel1 maxlength="25" style="width:300px;"}
	</p>

	<p>
		<label for="tel2" class="default">T&eacute;l&eacute;phone 2</label>
		{inputtext name="tel2" value=$ppo->rForm->tel2 maxlength="25" style="width:300px;"}
	</p>

	<p class="asterisk_txt">Les champs marqu&eacute;s du symbole <span class="asterisk">*</span> sont obligatoires</p>

<p>
<input type="button" class="formCancel" value="Annuler" onClick="self.location='{copixurl dest="gestion|structures|"}';" />

<input type="submit" class="formSubmit" value="Enregistrer" />

</p>

</fieldset>



</form>

{/if}