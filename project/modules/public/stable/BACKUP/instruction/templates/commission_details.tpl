

<form class="default-form" method="get" onSubmit="submitonce(this);">

<input type="hidden" name="id" value="{$ppo->rCommission->id}" />

<fieldset>
	<legend>Infos commission</legend>

	
	<div id="comm_infos">
	{copixzone process=instruction|commission_infos rCommission=$ppo->rCommission}
	</div>
	
	
	<div id="search" style="{if !$ppo->hasFiltre}display:none;{/if}">
	<h3>{icon action="search"} Chercher des dossiers parmi ceux de la commission</h3>
	<table class="search" border="0">
	<tr>
		<td class="lib">Nom</td>
		<td>{inputtext name="nom" value=$ppo->filtre->nom maxlength="50" style="width:130px;"}</td>
	
		<td class="lib">{i18n key=kernel|dao.commission2demande.fields.decision noEscape=1}</td>
	
		{section name=foo loop=$ppo->decisions}
			<td>
			{assign var=i value=$smarty.section.foo.index}
			<input name="decision[{$i}]" id="decision[{$i}]" class="checkbox" value="1" type="checkbox" {if is_array($ppo->filtre->decision) AND $ppo->filtre->decision[$i] eq 1}checked{/if} /><label for="decision[{$i}]"> {$ppo->commission2demande->getDecisionNom($i)}</label>

	</td>
				{/section}
		

	</tr>
	<tr>
		<td class="lib">Pr&eacute;nom</td>
		<td>{inputtext name="prenom" value=$ppo->filtre->prenom maxlength="50" style="width:130px;"}</td>
		
		<td class="lib">Type</td>
		<td><input name="type[RENO]" id="type[RENO]" class="checkbox" value="1" type="checkbox" {if is_array($ppo->filtre->type) AND $ppo->filtre->type.RENO eq 1}checked{/if} /><label for="type[RENO]"> A renouveler</label></td>
		<td><input name="type[AJOU]" id="type[AJOU]" class="checkbox" value="1" type="checkbox" {if is_array($ppo->filtre->type) AND $ppo->filtre->type.AJOU eq 1}checked{/if} /><label for="type[AJOU]"> D&eacute;j&agrave; ajourn&eacute;s</label></td>
		<td><input name="type[NOUV]" id="type[NOUV]" class="checkbox" value="1" type="checkbox" {if is_array($ppo->filtre->type) AND $ppo->filtre->type.NOUV eq 1}checked{/if} /><label for="type[NOUV]"> Nouveaux</label></td>
		
		<td align="right"><input type="submit" class="formSubmit" value="Chercher" /></td>
	</tr>
	
	
	</table>
	
	

	
	</div>
</fieldset>

</form>


<div class="default-form">
<fieldset>
	<legend>Dossiers</legend>
	
	{foreach from=$ppo->dossiers item=item}
		
		<div class="comm_dossier" id="comm_dossier_{$item->id}">
		{copixzone process=instruction|commission_dossier rDossier=$item rCommission=$ppo->rCommission}
		</div>
		
	{/foreach}
		
</fieldset>
</div>

















