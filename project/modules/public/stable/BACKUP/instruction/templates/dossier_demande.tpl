
{if $ppo->rDemande->id}
	{copixzone process=instruction|dossier_menu rDemande=$ppo->rDemande tab="demande"}
{/if}

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
	<legend>Demande</legend>
	
	<table>
	<tr>
	<td>
	
	{if !$ppo->rDemande->id}
	
	<p>
		<label for="enfant" class="default">{i18n key=kernel|dao.demande.fields.enfant noEscape=1}<span class="asterisk">*</span></label>
		{inputtext name="enfant" value=$ppo->rForm->enfant maxlength="10" style="width:130px;"}
		 - Saisir le num&eacute;ro de l'enfant (ID dans Vie Scolaire)
	</p>
	
	{/if}
	
	<p>
		<label for="etat" class="default">{i18n key=kernel|dao.demande.fields.etat noEscape=1}<span class="asterisk">*</span></label>
		{if $ppo->rDemande->id}
			{$ppo->rDemande->demande_etat_nom|escape}
		{else}
			{copixzone process=kernel|combo_demande_etat name="etat" selected=$ppo->rForm->etat}
		{/if}
			
	</p>
	
	<p>
		<label for="date" class="default">{i18n key=kernel|dao.demande.fields.date noEscape=1}<span class="asterisk">*</span></label>
		{inputtext name="date" value=$ppo->rForm->date maxlength="10" style="width:130px;"} - Format : JJ/MM/AAAA - <a href="javascript:set_value('date','{$smarty.now|date_format:"%d/%m/%Y"}');">aujourd'hui</a>
	</p>
	
	<p>
		<label for="date_entree" class="default">{i18n key=kernel|dao.demande.fields.date_entree noEscape=1}</label>
		{inputtext name="date_entree" value=$ppo->rForm->date_entree maxlength="10" style="width:130px;"}
	</p>
	
	
	{copixconf parameter='kernel|demandeChoix' assign=demandeChoix}
	{if $demandeChoix>0}
		{section name=foo loop=$demandeChoix+1 start=1 step=1}
  		<p>
			<label for="choix[{$smarty.section.foo.index}]" class="default">Choix {$smarty.section.foo.index}</label>
		{copixzone process=kernel|combo_structure name="choix[`$smarty.section.foo.index`]" selected=$ppo->rForm->choix[$smarty.section.foo.index]}
			</p>
		{/section}
	{/if}
	
	<p>
		<label for="notes" class="default">{i18n key=kernel|dao.demande.fields.notes noEscape=1}</label>
		{textarea name="notes" value=$ppo->rForm->notes style="width:320px;height:50px;"}
	</p>
	
	
	
	
	
	</td>
	<td>&nbsp;</td>
	<td class="separator">&nbsp;</td>
	<td valign="top">
	
	<b>Planning hebdomadaire pr&eacute;vu</b>
	
	
	<p>
		<label class="default">Jours</label>
		<div class="bigger">
		<input name="a_lundi" id="a_lundi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_lundi eq 1}checked{/if} /><label for="a_lundi"> {i18n key=kernel|dao.demande.fields.a_lundi noEscape=1}</label><br/>
		<input name="a_mardi" id="a_mardi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_mardi eq 1}checked{/if} /><label for="a_mardi"> {i18n key=kernel|dao.demande.fields.a_mardi noEscape=1}</label><br/>
		<input name="a_mercredi" id="a_mercredi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_mercredi eq 1}checked{/if} /><label for="a_mercredi"> {i18n key=kernel|dao.demande.fields.a_mercredi noEscape=1}</label><br/>
		<input name="a_jeudi" id="a_jeudi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_jeudi eq 1}checked{/if} /><label for="a_jeudi"> {i18n key=kernel|dao.demande.fields.a_jeudi noEscape=1}</label><br/>
		<input name="a_vendredi" id="a_vendredi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_vendredi eq 1}checked{/if} /><label for="a_vendredi"> {i18n key=kernel|dao.demande.fields.a_vendredi noEscape=1}</label><br/>
		<input name="a_samedi" id="a_samedi" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_samedi eq 1}checked{/if} /><label for="a_samedi"> {i18n key=kernel|dao.demande.fields.a_samedi noEscape=1}</label><br/>
		</div>
	</p>
	
	<p>
		<label class="default">Heures</label>
		<div class="bigger">
		<input name="a_avant_7h" id="a_avant_7h" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_avant_7h eq 1}checked{/if} /><label for="a_avant_7h"> {i18n key=kernel|dao.demande.fields.a_avant_7h noEscape=1}</label><br/>
		<input name="a_apres_19h" id="a_apres_19h" class="checkbox" value="1" type="checkbox" {if $ppo->rForm->a_apres_19h eq 1}checked{/if} /><label for="a_apres_19h"> {i18n key=kernel|dao.demande.fields.a_apres_19h noEscape=1}</label><br/>
		</div>
	</p>
	
		</td>
	</tr>
	</table>

	
	<p class="asterisk_txt">Les champs marqu&eacute;s du symbole <span class="asterisk">*</span> sont obligatoires</p>

<p>
<input type="button" class="formCancel" value="Annuler" onClick="self.location='{copixurl dest="instruction|dossiers|"}';" />

<input type="submit" class="formSubmit" value="Enregistrer" />


</p>
		
</fieldset>

</form>


{if $ppo->rDemande->id}
	<div class="default-form">
	<fieldset>
		<legend>Historique</legend>
		{copixzone process=instruction|dossier_historique_etat rDemande=$ppo->rDemande}
	</fieldset>
	</div>
{/if}
	
	