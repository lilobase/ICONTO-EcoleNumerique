

	
	<table>
	<tr>
	<td>
	D&eacute;cisions prises 
	</td>
	<td>{assign var=pourc value=$ppo->rCommission->nb_dossiers_traites/$ppo->rCommission->nb_dossiers*100|round}

	<div class="progressBar">
		<span><em style="left:{$pourc*2}px;">&nbsp;</em></span>
	</div></td>
	<td>{$ppo->rCommission->nb_dossiers_traites} sur {$ppo->rCommission->nb_dossiers} - {$pourc}%</td>
	<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
	<td>
	
	{if $ppo->page eq 'fin'}
		<input type="button" class="formCancel" value="Retour" onClick="self.location='{copixurl dest="commissions|details" id=$ppo->rCommission->id}'" />
		<input type="button" class="formSubmit" value="Confirmer la fin" onClick="endCommission(this.form);" />
	{elseif $ppo->page eq 'bilan'}
		<input type="button" class="formCancel" value="Retour aux commissions" onClick="self.location='{copixurl dest="commissions|"}'" />
	{else}
	<input type="button" class="formSubmit" value="Chercher" onClick="jsToggle('search');" /> <input type="button" class="formSubmit" value="Mettre fin" onClick="self.location='{copixurl dest="commissions|end" id=$ppo->rCommission->id}'" />
	{/if}

</td>
	
	
	</tr>
	</table>
	

	




