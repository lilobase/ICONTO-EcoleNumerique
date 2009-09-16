
{assign var=item value=$ppo->rDossier}
{copixconf parameter='kernel|demandeChoix' assign=demandeChoix}


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


<form>
	
<div class="entete">
	
	<div class="decision">{i18n key=kernel|dao.commission2demande.fields.decision noEscape=1} : <span class="decision decision{$item->decision}">{$item->decision_nom}</span> {if $item->decision neq $ppo->decisions.AUCUN && !$ppo->end}<input type="button" class="annuler" value="[ X ]" onClick="setDecision(this.form,{$ppo->rCommission->id},{$item->id},{$ppo->decisions.AUCUN},'{$demandeChoix}');" />{/if}</div>
	
	<span class="nom"><a title="Voir le dossier" href="{copixurl dest="dossiers|dossier_demande" id=$item->id}">{$item->enfant_nom|escape} {$item->enfant_prenom|escape}</a></span>
	<span>
		&bull; N&eacute;(e) le {$item->date_nais|date_age}
		&bull; {$item->enfant_num_rue|escape} {$item->enfant_num_seq|escape} {$item->enfant_adresse1|escape} {$item->enfant_adresse2|escape} {$item->enfant_code_postal|escape} {$item->enfant_commune|escape}
		
		{if !$ppo->end}<a href="javascript:jsToggle('comm_dossier_in_{$item->id}');">Plier/d&eacute;plier</a>{/if}
	</span>
</div>

<div id="comm_dossier_in_{$item->id}"{if $ppo->end || $item->decision neq $ppo->decisions.AUCUN} style="display:none;"{/if}>

<table border="0" width="100%">
	<tr>
		<td valign="top">
			<div class="lib">P&egrave;re</div><i>TODO</i><br/>
			<div class="lib">M&egrave;re</div><i>TODO</i><br/>
			<div class="lib">Demande</div>{$item->date|date_format:"%d/%m/%Y"}<br/>
			<div class="lib">Confirmation</div><i>TODO</i><br/>
			<div class="lib">Entr&eacute;e pr&eacute;vue</div>{$item->date_entree|date_format:"%d/%m/%Y"}<br/>
			<div class="lib">Jours pr&eacute;vus</div>
				<div class="jours">
					<ul>
						<li class="first">{if $item->a_lundi}L{else}&nbsp;{/if}</li>
						<li>{if $item->a_mardi}M{else}&nbsp;{/if}</li>
						<li>{if $item->a_mercredi}M{else}&nbsp;{/if}</li>
						<li>{if $item->a_jeudi}J{else}&nbsp;{/if}</li>
						<li>{if $item->a_vendredi}V{else}&nbsp;{/if}</li>
						<li>{if $item->a_samedi}S{else}&nbsp;{/if}</li>
					</ul>
				</div>
			
			
		</td>
		<td valign="top">
		
		{copixconf parameter='kernel|demandeChoix' assign=demandeChoix}
			{if $demandeChoix>0}
				{section name=foo loop=$demandeChoix+1 start=1 step=1}
		  		<div class="lib">Choix {$smarty.section.foo.index}</div>
				{copixzone process=kernel|combo_structure name="choix[`$smarty.section.foo.index`]" selected=$item->choix[$smarty.section.foo.index]}
					<br/>
				{/section}
			{/if}
		</td>
		<td valign="top">
		
		{assign var=decision_date value=$item->decision_date_fr}
		{if !$item->decision_date_fr}
			{assign var=decision_date value=$smarty.now|date_format:'%d/%m/%Y'}
		{/if}
		
		
		<div class="lib">Date d&eacute;cision</div>{inputtext name="decision_date" value=$decision_date maxlength="10" style="width:70px;"}<br/>
		<div class="lib">Date entr&eacute;e</div>{inputtext name="date_entree" value=$item->date_entree_fr maxlength="10" style="width:70px;"}<br/>
		<div class="lib">Motif</div>
		
		{textarea name="decision_motif" value=$item->decision_motif style="width:200px;height:66px;"}
		
		<p></p>
<input type="button" class="decision decision3" value="Refuser" onClick="setDecision(this.form,{$ppo->rCommission->id},{$item->id},{$ppo->decisions.REFUSE},'{$demandeChoix}');" />

<input type="button" class="decision decision2" value="Ajourner" onClick="setDecision(this.form,{$ppo->rCommission->id},{$item->id},{$ppo->decisions.AJOURNE},'{$demandeChoix}');" />
		
		
		
		<input type="button" class="decision decision1" value="Accepter" onClick="setDecision(this.form,{$ppo->rCommission->id},{$item->id},{$ppo->decisions.ACCEPTE},'{$demandeChoix}');" /> 

 

		</td>
		
		
	</tr>
	<tr>
		<td colspan="3"><div class="lib">{i18n key=kernel|dao.demande.fields.notes noEscape=1}</div>
		{textarea name="notes" value=$item->notes style="width:600px;height:38px;"}
		
		</td>
	</tr>

</table>
</div>

</form>