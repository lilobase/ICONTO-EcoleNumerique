
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

<input type="hidden" name="id_dbuser" value="{$ppo->rForm->id_dbuser}" />
<input type="hidden" name="submit" value="1" />

<fieldset>
	<legend>Informations</legend>

	<p>
		<label for="login_dbuser" class="default">Login<span class="asterisk">*</span></label>
		{if $ppo->rForm->id_dbuser}
			{inputtext name="login_dbuser" value=$ppo->rForm->login_dbuser maxlength="32" style="width:200px;" readonly=1} (non modifiable)
		{else}
			{inputtext name="login_dbuser" value=$ppo->rForm->login_dbuser maxlength="32" style="width:200px;"}
		{/if}
	</p>

	{if $ppo->rForm->id_dbuser}
		<label for="password1" class="default">Nouveau mot de passe</label>
	{else}
		<label for="password1" class="default">Mot de passe<span class="asterisk">*</span></label>
	{/if}
	<p>
		<input type="password" name="password1" value="" style="width:150px;" /><br/><input type="password" name="password2" value="" style="width:150px;" /> (confirmer)
	</p>
	
	<p>
		<label for="login_dbuser" class="default">Email<span class="asterisk">*</span></label>
		{inputtext name="email_dbuser" value=$ppo->rForm->email_dbuser maxlength="255" style="width:200px;"}
	</p>

	<p>
		<label for="login_dbuser" class="default">Personnel ville (SSO)<span class="asterisk">*</span></label>
		{copixzone process=kernel|combo_personnel name="personnel_dbuser" selected=$ppo->rForm->personnel_dbuser entite=VILLE}
	</p>
	
	<p>
		<label for="login_dbuser" class="default">Type<span class="asterisk">*</span></label>
		{radiobutton name="type_dbuser" values="agent=>Agent;root=>Root"|toarray selected=$ppo->rForm->type_dbuser extra='class="radiobutton"'}
	</p>

	<p class="asterisk_txt">Les champs marqu&eacute;s du symbole <span class="asterisk">*</span> sont obligatoires</p>

<p>
<input type="button" class="formCancel" value="Annuler" onClick="self.location='{copixurl dest="kernel|compte|"}';" />

<input type="submit" class="formSubmit" value="Enregistrer" />

</p>

</fieldset>

</form>

