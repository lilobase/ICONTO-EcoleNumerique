

<form class="default-form" method="get" onSubmit="submitonce(this);">

<input type="hidden" name="id" value="{$ppo->rCommission->id}" />

<fieldset>
	<legend>Infos commission</legend>

	
	<div id="comm_infos">
	{copixzone process=instruction|commission_infos rCommission=$ppo->rCommission page=fin}
	</div>
	
	
	
</fieldset>

</form>


<div class="default-form">
<fieldset>
	<legend>Dossiers</legend>
	
	<p>Vous pouvez v&eacute;rifier les d&eacute;cisions prises avant de confirmer la fin de commission et d'acc&eacute;der aux PV et notifications.</p>
	
	{assign var=currentDecision value=''}
	
	
	{foreach from=$ppo->dossiers item=item}
		
		{if $currentDecision neq $item->decision}
			{assign var=idDecision value=$item->decision}
			<h2>{$ppo->bilan.$idDecision.nom} : {$ppo->bilan.$idDecision.nb} / {$ppo->rCommission->nb_dossiers} </h2>
		{/if}	
		
		
		
		<div class="comm_dossier" id="comm_dossier_{$item->id}">
		{copixzone process=instruction|commission_dossier rDossier=$item rCommission=$ppo->rCommission end=true}
		</div>
		
		{assign var=currentDecision value=$item->decision}
		
	{/foreach}
		
		
	<h2>Bilan (sur {$ppo->rCommission->nb_dossiers} dossiers)</h2>
	<ul>
	{foreach from=$ppo->bilan item=item}
		<li>{$item.nom} : <b>{$item.nb}</b> ({$item.nb/$ppo->rCommission->nb_dossiers*100|round}%)</li>
	
	{/foreach}
	</ul>
	
	
</fieldset>
</div>

















